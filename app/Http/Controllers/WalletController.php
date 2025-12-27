<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $wallet = Auth::user()->wallet;

        return view('student.wallet.index', compact('wallet'));
    }
     public function topup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:5'
        ]);

        $user = Auth::user();
        $wallet = $user->wallet;

        DB::transaction(function () use ($wallet, $request, $user) {

            // Increase wallet balance
            $wallet->increment('balance', $request->amount);

            // Record transaction
            Transaction::create([
                'student_id'  => $user->id,
                'provider_id' => null,
                'amount'      => $request->amount,
                'type'        => 'topup'
            ]);
        });

        return back()->with('success', 'Wallet topped up successfully.');
    }
}
