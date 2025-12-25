<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class StudentProviderController extends Controller
{
    public function show(User $user)
    {
        // Ensure only approved providers are visible
        if ($user->role !== 'provider') {
            abort(404);
        }

         $provider = $user->provider;
        $notes = $provider->notes()
            ->where('status', 'approved')
            ->latest()
            ->get();

        $isSubscribed = Auth::user()
            ->subscriptions()
            ->where('provider_id', $provider->id)
            ->exists();

        return view('student.providers.show', compact(
            'user',
            'provider',
            'notes',
            'isSubscribed'
        ));
}
}