<?php

namespace App\Models;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Subscription extends Model
{
    protected $fillable = [
        'student_id',
        'provider_id',
        'price',
        'status',
        'ended_at',
    ];

    protected $casts = [
        'ended_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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
        $endedAt = $this->ended_at;
        if (is_string($endedAt)) {
            $endedAt = Carbon::parse($endedAt);
        }

        return $this->status === 'active'
            && (!$endedAt instanceof CarbonInterface || $endedAt->isFuture());
    }

    public function expiresAt()
    {
        return $this->created_at->addDays(30);
    }

public function remainingDays()
    {
        $endedAt = $this->ended_at;
        if (is_string($endedAt)) {
            $endedAt = Carbon::parse($endedAt);
        }

        if (!$endedAt) {
            return 0;
        }

        return max(
        0,
        Carbon::now()->diffInDays($endedAt, false)
    );
    }

    public function cancel(Subscription $subscription)
{
    if ($subscription->student_id !== Auth::id()) {
        abort(403);
    }

    if ($subscription->status !== 'active') {
        return back()->with('error', 'Subscription is not active.');
    }

    $subscription->update([
        'status' => 'cancelled',
        'ended_at' => now() // end immediately
    ]);

    return back()->with('success', 'Subscription cancelled successfully.');
}

}
