<?php

namespace App\Http\Controllers;
use App\Models\Withdrawal;
use App\Models\Transaction;
use App\Notifications\WithdrawalApprovedNotification;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class AdminWithdrawalController extends Controller
{
     public function index()
    {
        $withdrawals = Withdrawal::with('provider')
            ->latest()
            ->get();

        return view('admin.withdrawals.index', compact('withdrawals'));
    }
    public function approve(Request $request, Withdrawal $withdrawal)
{
    if ($withdrawal->status !== 'pending') {
        return back()->with('error', 'Invalid withdrawal status.');
    }

    $request->validate([
        'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    // Store proof
    $path = $request->file('payment_proof')
                    ->store('withdrawal_proofs', 'public');

    // Deduct provider wallet
    $wallet = $withdrawal->provider->wallet;

    if ($wallet->balance < $withdrawal->amount) {
        return back()->with('error', 'Insufficient provider wallet balance.');
    }

    $wallet->decrement('balance', $withdrawal->amount);

    // Update withdrawal
    $withdrawal->update([
        'status' => 'approved',
        'payment_proof' => $path,
        'approved_at' => now(),
    ]);

    // Refresh withdrawal to get updated payment_proof
    $withdrawal->refresh();
    
    $withdrawal->provider->notify(
        new WithdrawalApprovedNotification($withdrawal)
    );
    return back()->with('success', 'Withdrawal approved and proof uploaded.');
}

    public function reject(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Invalid withdrawal status.');
        }

        $withdrawal->update(['status' => 'rejected']);

        return back()->with('success', 'Withdrawal rejected.');
    }
}
