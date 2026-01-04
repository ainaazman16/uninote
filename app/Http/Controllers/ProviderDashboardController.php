<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Wallet;
use App\Models\NoteRating;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class ProviderDashboardController extends Controller
{
    
 public function index()
    {
        $user = Auth::user();
        $providerId = optional($user->provider)->id;

        /* =======================
           Notes statistics
        ======================= */
        $totalNotes = $providerId
            ? Note::where('provider_id', $providerId)->count()
            : 0;

        $approvedNotes = $providerId
            ? Note::where('provider_id', $providerId)->where('status', 'approved')->count()
            : 0;

        $pendingNotes = $providerId
            ? Note::where('provider_id', $providerId)->where('status', 'pending')->count()
            : 0;

        $rejectedNotes = $providerId
            ? Note::where('provider_id', $providerId)->where('status', 'rejected')->count()
            : 0;

        $totalDownloads = $providerId
            ? Note::where('provider_id', $providerId)->sum('download_count')
            : 0;

        /* =======================
           Earnings
        ======================= */
        $totalEarnings = Transaction::where('provider_id', $providerId)->sum('amount');

        $monthlyEarnings = Transaction::where('provider_id', $providerId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $recentTransactions = Transaction::with('student')
            ->where('provider_id', $providerId)
            ->latest()
            ->take(5)
            ->get();

        /* =======================
           Wallet
        ======================= */
        $walletBalance = Wallet::ensure($user)->balance;

        /* =======================
           ⭐ Ratings logic (NEW)
        ======================= */

        // Notes with ratings
        $notesWithRatings = Note::withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->where('provider_id', $providerId)
            ->where('status', 'approved')
            ->get();

        // Overall provider average rating (from all notes)
        $overallRating = NoteRating::whereHas('note', function ($q) use ($providerId) {
            $q->where('provider_id', $providerId);
        })->avg('rating');

        $overallRatingCount = NoteRating::whereHas('note', function ($q) use ($providerId) {
            $q->where('provider_id', $providerId);
        })->count();

        // Alias for view
        $notes = $notesWithRatings;

        return view('provider.dashboard', compact(
            'totalNotes',
            'approvedNotes',
            'pendingNotes',
            'rejectedNotes',
            'totalDownloads',
            'walletBalance',
            'totalEarnings',
            'monthlyEarnings',
            'recentTransactions',
            'notes',
            'overallRating',
            'overallRatingCount'
        ));
    }
}
