<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Wallet;
use App\Models\Transaction;
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

        // Total earnings (credits earned)
        $totalEarnings = Transaction::where('provider_id', $providerId)
            ->sum('amount');

        // Earnings this month
        $monthlyEarnings = Transaction::where('provider_id', $providerId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // Recent transactions
        $recentTransactions = Transaction::with('student')
            ->where('provider_id', $providerId)
            ->latest()
            ->take(5)
            ->get();

        // Ensure wallet and get balance for this provider's user
        $walletBalance = Wallet::ensure($user)->balance;

        return view('provider.dashboard', compact(
            'totalNotes',
            'approvedNotes',
            'pendingNotes',
            'rejectedNotes',
            'totalDownloads',
            'walletBalance',
            'totalEarnings',
            'monthlyEarnings',
            'recentTransactions'
        ));
        
    }
}
