<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $wallet = Auth::user()->wallet;
        $transactions = Transaction::where('student_id', Auth::id())
            ->latest()
            ->get();

        return view('student.wallet.index', compact('wallet', 'transactions'));
    }
public function processTopUp(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:1',
    ]);

    $amount = $request->amount * 100; // ToyyibPay wants cents
    $user = Auth::user();

    $secretKey = config('services.toyyibpay.secret');
    $categoryCode = config('services.toyyibpay.category');
    $baseUrl = config('services.toyyibpay.url'); // sandbox or live

    $response = Http::asForm()->post($baseUrl . '/index.php/api/createBill', [
        'userSecretKey' => $secretKey,
        'categoryCode'  => $categoryCode,
        'billName'      => 'Wallet Top Up for ' . $user->name,
        'billDescription' => 'Top up wallet credits',
        'billPriceSetting' => 1,
        'billPayorInfo' => 1,
        'billAmount'    => $amount,
        'billReturnUrl' => route('student.wallet.topup.success'),
        'billCallbackUrl' => route('student.wallet.topup.callback'),
        'billExternalReferenceNo' => uniqid('TOPUP_'),
    ]);

    $data = $response->json();

    // ToyyibPay returns an array of objects with BillCode
    if (isset($data[0]['BillCode'])) {
        $billCode = $data[0]['BillCode'];
        // Redirect to payment page
        return redirect($baseUrl . '/' . $billCode);
    }

    // If something went wrong
    return back()->with('error', 'Failed to create payment bill: ' . json_encode($data));
}

public function callback(Request $request)
{
    if ($request->status_id != 1) {
        return response('FAILED', 400);
    }

    $user = User::where('email', $request->billEmail)->firstOrFail();

    DB::transaction(function () use ($user, $request) {

        $user->wallet->increment('balance', $request->billAmount / 100);

        Transaction::create([
            'student_id'  => $user->id,
            'provider_id' => null,
            'amount'      => $request->billAmount / 100,
            'type'        => 'topup'
        ]);
    });

    return response('OK', 200);
}

    public function topUpForm()
    {
        return view('student.wallet.topup');
    }
}
