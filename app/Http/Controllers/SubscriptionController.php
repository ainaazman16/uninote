<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Subscription;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use function Symfony\Component\Translation\t;

class SubscriptionController extends Controller
{
    public function store(Provider $provider)
    {
        $student = Auth::user();
        $price = 10; // example fixed price

        // Ensure wallets exist
        $studentWallet = Wallet::ensure($student);
        $providerUser = $provider->user;
        $providerWallet = Wallet::ensure($providerUser);

        // Prevent subscribing to self
        if ($provider->user_id === $student->id) {
            return back()->with('error', 'You cannot subscribe to yourself.');
        }
        // Prevent multiple active subscriptions (30 days)
                $activeSubscription = Subscription::where('student_id', $student->id)
            ->where('provider_id', $provider->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->exists();

        if ($activeSubscription) {
            return back()->with('error', 'You already have an active subscription.');
        }


        // Prevent duplicate subscriptions
        $alreadySubscribed = Subscription::where('student_id', $student->id)
            ->where('provider_id', $provider->id)
            ->exists();

        if ($alreadySubscribed) {
            return back()->with('error', 'Already subscribed.');
        }

        // 3️⃣ Check wallet using ensured wallet instance
        if ($studentWallet->balance < $price) {
            return back()->with('error', 'Insufficient wallet balance. Please top up before subscribing.');
        }

        DB::transaction(function () use ($student, $studentWallet, $provider, $providerWallet, $price) {

            // 4️⃣ Deduct student wallet
            $studentWallet->decrement('balance', $price);

            // 5️⃣ Add credits to provider
            $providerWallet->increment('balance', $price);
           
            // 6️⃣ Create subscription
            Subscription::create([
                'student_id'  => $student->id,
                'provider_id' => $provider->id,
                'price'       => $price,
            ]);

            Transaction::create([
                'student_id' => $student->id,
                'provider_id'  => $provider->id,
            'amount'       => $price,
            'type'         => 'subscription',
            // 'status'       => 'completed',
            ]);

        });

        return back()->with('success', 'Subscribed successfully!');
    }
}
