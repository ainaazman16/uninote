<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
        abort_if(auth()->user()->role === 'admin', 403, 'Admins cannot subscribe to providers.');
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
                'status' => 'active',
    'ended_at' => now()->addDays(30),
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
    public function cancel(Subscription $subscription)
{
    if ($subscription->student_id !== auth()->id()) {
        abort(403);
    }

    if ($subscription->status !== 'active') {
        return back()->with('error', 'Subscription is not active.');
    }

    $subscription->update([
        'status' => 'expired',
        'ended_at' => now()
    ]);

    return back()->with('success', 'Subscription cancelled successfully.');
}
    public function mySubscriptions()
    {
        $subscriptions = Subscription::with(['provider.user'])
            ->where('student_id', Auth::id())
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('ended_at')
                ->orWhere('ended_at', '>', now());
            })
            ->latest()
            ->get();

        return view(
            'student.subscriptions.index',
            compact('subscriptions')
        );
    }
}
