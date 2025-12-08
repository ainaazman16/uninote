<?php

namespace App\Http\Controllers;

use App\Models\Note;

class ProviderDashboardController extends Controller
{
    public function index()
    {
        $provider = auth()->user()->provider;

        $totalNotes = Note::where('provider_id', $provider->id)->count();
        $approvedNotes = Note::where('provider_id', $provider->id)->where('status', 'approved')->count();
        $pendingNotes = Note::where('provider_id', $provider->id)->where('status', 'pending')->count();
        $rejectedNotes = Note::where('provider_id', $provider->id)->where('status', 'rejected')->count();

        return view('provider.dashboard.index', compact(
            'totalNotes',
            'approvedNotes',
            'pendingNotes',
            'rejectedNotes'
        ));
    }
}
