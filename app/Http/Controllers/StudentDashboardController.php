<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\User;

class StudentDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        return $this->index($request);
    }

    public function index(Request $request)
{
    $student = Auth::user();

    // Fetch all subscriptions of the student with provider details
    $subscriptions = Subscription::with('provider')
        ->where('student_id', $student->id)
        ->get();

    // Total subscribed providers
    $totalSubscriptions = $student->subscriptions()->count();

    // Fetch subscribed providers (with their user data)
   $subscribedProviders = $student->subscriptions()
        ->with('provider.user')
        ->get()
        ->map(function ($subscription) {
            return $subscription->provider->user;
        });

    // For search
    $query = $request->input('query');
    $providers = Provider::with('user')
        ->when($query, function($q) use ($query) {
            $q->whereHas('user', function($q2) use ($query) {
                $q2->where('name', 'like', "%$query%")
                   ->orWhere('email', 'like', "%$query%");
            });
        })
        ->get()
        ->pluck('user');

    return view('dashboard', compact(
        'totalSubscriptions',
        'subscribedProviders',
        'providers',
        'subscriptions',
    ));
}

    public function searchProviders(Request $request)
{
    $query = $request->input('query');

    $providers = User::where('role', 'provider')
        ->where(function ($q) use ($query) {
            $q->where('name', 'like', "%$query%")
              ->orWhere('email', 'like', "%$query%");
        })
        ->get();

    // If there is at least one matching provider, show the first provider's profile
    if ($providers->isNotEmpty()) {
        $user = $providers->first();
        return view('student.providers.show', compact('user'));
    }

    // No results: redirect back with a friendly message
    return redirect()->back()->with('error', 'No providers matched your search.');
}
}
