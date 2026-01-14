<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WalletTopup;
use App\Models\Wallet;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentWalletController extends Controller
{
public function index()
    {
        abort_if(auth()->user()->role === 'admin', 403, 'Admins cannot access wallet.');
        $user = Auth::user();

        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );

        $transactions = Transaction::where('student_id', $user->id)
            ->latest()
            ->get();

        $pendingTopups = WalletTopup::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        $rejectedTopups = WalletTopup::where('user_id', $user->id)
            ->where('status', 'rejected')
            ->latest()
            ->get();

        return view('student.wallet.index', compact(
            'wallet',
            'transactions',
            'pendingTopups',
            'rejectedTopups'
        ));
    }

    public function create()
    {
        return view('student.wallet.topup');
    }

    public function store(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:10',
        'payment_method' => 'required|in:transfer,qr',
        'proof'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    // Store uploaded proof
    $proofPath = $request->file('proof')
        ->store('topup-proofs', 'public');

    // Create pending top-up
    WalletTopup::create([
        'user_id' => auth()->id(),
        'amount'     => $request->amount,
        'payment_method'     => $request->payment_method,
        'proof_path' => $proofPath,
        'status'     => 'pending',
    ]);

    return redirect()
        ->route('student.wallet.index')
        ->with('success', 'Top-up submitted. Waiting for admin approval.');
}
}

