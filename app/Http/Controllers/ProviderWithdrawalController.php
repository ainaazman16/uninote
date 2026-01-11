<?php

namespace App\Http\Controllers;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class ProviderWithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = Withdrawal::where('provider_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('provider.withdrawals.index', compact('withdrawals'));
    }

    public function store(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:1',
        'bank_name' => 'required|string|max:255',
        'account_number' => 'required|string|max:50',
        'account_name' => 'required|string|max:255',
    ]);

    $wallet = auth()->user()->wallet;

    if ($request->amount > $wallet->balance) {
        return back()->with('error', 'Insufficient wallet balance.');
    }

    Withdrawal::create([
        'provider_id' => auth()->id(),
        'amount' => $request->amount,
        'bank_name' => $request->bank_name,
        'account_number' => $request->account_number,
        'account_name' => $request->account_name,
        'status' => 'pending',
    ]);

    return back()->with('success', 'Withdrawal request submitted for approval.');
}

}
