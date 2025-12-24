<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class ProviderDashboardController extends Controller
{
    
    public function index()
    {
        $providerId = Auth::id();

        $totalNotes = Note::where('provider_id', $providerId)->count();
        $approvedNotes = Note::where('provider_id', $providerId)->where('status', 'approved')->count();
        $pendingNotes = Note::where('provider_id', $providerId)->where('status', 'pending')->count();
        $rejectedNotes = Note::where('provider_id', $providerId)->where('status', 'rejected')->count();

        $totalDownloads = Note::where('provider_id', $providerId)->sum('download_count');

        return view('provider.dashboard', compact(
            'totalNotes',
            'approvedNotes',
            'pendingNotes',
            'rejectedNotes',
            'totalDownloads'
        ));
        
    }
}
