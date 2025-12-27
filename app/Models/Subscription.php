<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'student_id',
        'provider_id',
        'price',
        'status',
        'ended_at',
    ];

    protected $dates = ['started_at','ended_at'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

     // 30-day expiry
    public function isActive()
    {
         return $this->status === 'active'
            && ($this->ended_at === null || $this->ended_at->isFuture());
    }

    public function expiresAt()
    {
        return $this->created_at->addDays(30);
    }

public function remainingDays()
    {
        if (!$this->ended_at) {
            return 0;
        }

        return max(
        0,
        Carbon::now()->diffInDays(Carbon::parse($this->ended_at), false)
    );
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
        'status' => 'cancelled',
        'expires_at' => now() // end immediately
    ]);

    return back()->with('success', 'Subscription cancelled successfully.');
}

}
