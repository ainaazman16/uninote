<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Note;
use App\Models\Subscription;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = User::where('role', 'provider')->get();

        return view('provider.index', compact('providers'));
    }
    public function show($providerId)
{
    $provider = User::where('role', 'provider')
        ->where('id', $providerId)
        ->firstOrFail();

    $notes = Note::where('provider_id', $providerId)
        ->where('status', 'approved')
        ->get();

    $isSubscribed = Subscription::where('student_id', auth()->id())
        ->where('provider_id', $providerId)
        ->where('status', 'active')
        ->exists();

    return view('provider.show', compact(
        'provider',
        'notes',
        'isSubscribed'
    ));
}

}
