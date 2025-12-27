<?php

namespace App\Http\Controllers;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class ProviderWithdrawalController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        $provider = Auth::user();
        $wallet = $provider->wallet;

        if (!$wallet || $wallet->balance < $request->amount) {
            return back()->with('error', 'Insufficient wallet balance.');
        }

        Withdrawal::create([
            'provider_id' => $provider->id,
            'amount' => $request->amount,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Withdrawal request submitted.');
    }
}
