<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WalletTopup;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class AdminWalletTopupController extends Controller
{
    public function index()
    {
        $pendingTopups = WalletTopup::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.wallet_topups.index', compact('pendingTopups'));
    }

    public function approve(WalletTopup $topup)
    {
        if ($topup->status !== 'pending') {
            return back()->with('error', 'Top-up already processed.');
        }

        DB::transaction(function () use ($topup) {
            $topup->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            $wallet = Wallet::firstOrCreate(
                ['user_id' => $topup->user_id],
                ['balance' => 0]
            );

            $wallet->increment('balance', $topup->amount);

            Transaction::create([
                'student_id' => $topup->user_id,
                'provider_id' => null,
                'amount' => $topup->amount,
                'type' => 'topup',
                'status' => 'success',
                'description' => 'Wallet Top-Up (Approved)',
                'reference' => 'TOPUP-' . $topup->id,
            ]);
        });

        return back()->with('success', 'Top-up approved and wallet updated.');
    }


    public function reject(WalletTopup $topup)
    {
        if ($topup->status !== 'pending') {
            return back()->with('error', 'Top-up already processed.');
        }

        $topup->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Top-up rejected.');
    }
}
