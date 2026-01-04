<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Transaction;


class ToyyibPayController extends Controller
{
   public function createBill(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:1'
    ]);

    $user = auth()->user();

    $response = Http::asForm()->post(
        config('services.toyyibpay.url') . '/index.php/api/createBill',
        [
            'userSecretKey' => config('services.toyyibpay.secret'),
            'categoryCode'  => config('services.toyyibpay.category'),
            'billName'      => 'Wallet Top Up',
            'billDescription' => 'Wallet top up for '.$user->name,
            'billPriceSetting' => 1,
            'billPayorInfo' => 1,
            'billAmount'    => $request->amount * 100,
            'billReturnUrl' => route('student.wallet.index'),
            'billCallbackUrl' => route('student.wallet.topup.callback'),
            'billExternalReferenceNo' => uniqid('TOPUP_'),
            'billTo'        => $user->name,
            'billEmail'     => $user->email,
        ]
    );

    if (!isset($response[0]['BillCode'])) {
        dd($response->body()); // DEBUG if ToyyibPay fails
    }

    return redirect(
        config('services.toyyibpay.url') . '/' . $response[0]['BillCode']
    );
}

    public function callback(Request $request)
{
    if ($request->status_id != 1) {
        return response('FAILED', 400);
    }

    $user = User::where('email', $request->billEmail)->first();

    DB::transaction(function () use ($user, $request) {

        $user->wallet()->increment(
            'balance',
            $request->billAmount / 100
        );

        Transaction::create([
            'student_id'  => $user->id,
            'provider_id' => null,
            'amount'      => $request->billAmount / 100,
            'type'        => 'topup',
        ]);
    });

    return response('OK', 200);
}
}
