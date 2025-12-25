<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class ProviderDashboardController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        $providerId = optional($user->provider)->id;

        $totalNotes = $providerId ? Note::where('provider_id', $providerId)->count() : 0;
        $approvedNotes = $providerId ? Note::where('provider_id', $providerId)->where('status', 'approved')->count() : 0;
        $pendingNotes = $providerId ? Note::where('provider_id', $providerId)->where('status', 'pending')->count() : 0;
        $rejectedNotes = $providerId ? Note::where('provider_id', $providerId)->where('status', 'rejected')->count() : 0;

        $totalDownloads = $providerId ? Note::where('provider_id', $providerId)->sum('download_count') : 0;

        // Ensure wallet and get balance for this provider's user
        $walletBalance = Wallet::ensure($user)->balance;

        return view('provider.dashboard', compact(
            'totalNotes',
            'approvedNotes',
            'pendingNotes',
            'rejectedNotes',
            'totalDownloads',
            'walletBalance'
        ));
        
    }
}
