<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use App\Models\User;
use App\Models\NoteRating;

class StudentProviderController extends Controller
{
    public function show(User $user)
    {
        // Ensure only providers are visible
        if ($user->role !== 'provider') {
            abort(404);
        }

        $provider = $user->provider;

        // Approved notes by provider
        $notes = $provider->notes()
            ->where('status', 'approved')
            ->latest()
            ->get();

        // Active subscription
        $subscription = Subscription::where('student_id', auth()->id())
            ->where('provider_id', $provider->id)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('ended_at')
                  ->orWhere('ended_at', '>', now());
            })
            ->latest()
            ->first();

        $expiresAt = $subscription ? $subscription->expiresAt() : null;
        $daysRemaining = $expiresAt
            ? max(0, now()->diffInDays($expiresAt, false))
            : null;

        $isSubscribed = $subscription && $subscription->isActive();

        return view('student.providers.show', compact(
            'user',
            'provider',
            'notes',
            'isSubscribed',
            'subscription',
            'daysRemaining'
        ));
    }
}
