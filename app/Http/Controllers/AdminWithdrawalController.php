<?php

namespace App\Http\Controllers;
use App\Models\Withdrawal;
use App\Models\Transaction;
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
    public function approve(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Invalid withdrawal status.');
        }

        DB::transaction(function () use ($withdrawal) {

            // Update withdrawal
            $withdrawal->update(['status' => 'approved']);

            // Get the user (provider user)
            $user = $withdrawal->provider;
            if (!$user) {
                throw new \Exception('User not found for this withdrawal.');
            }
            
            // Get the provider record
            $provider = $user->provider;
            if (!$provider) {
                throw new \Exception('Provider record not found for this user.');
            }
            
            // Deduct wallet
            $wallet = $user->wallet;
            if (!$wallet) {
                throw new \Exception('Wallet not found for this user.');
            }
            
            $wallet->decrement('balance', $withdrawal->amount);

            // Record transaction
            Transaction::create([
                'student_id'  => null,
                'provider_id' => $provider->id,
                'amount'      => $withdrawal->amount,
                'type'        => 'withdrawal'
            ]);
        });

        return back()->with('success', 'Withdrawal approved.');
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
