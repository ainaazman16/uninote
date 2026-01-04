<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WalletTopup;
use App\Models\Wallet;

class AdminWalletController extends Controller
{
    public function index()
    {
        $topups = WalletTopup::with('user')
            ->latest()
            ->get();

        return view('admin.wallet.topups', compact('topups'));
    }

    public function approve(WalletTopup $topup)
    {
        if ($topup->status !== 'pending') return back();

        $wallet = Wallet::ensure($topup->user);

        $wallet->increment('balance', $topup->amount);

        $topup->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Top-up approved and wallet updated.');
    }

    public function reject(WalletTopup $topup)
    {
        $topup->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('error', 'Top-up rejected.');
    }
}

