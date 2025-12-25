<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'student_id',
        'provider_id',
        'price',
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
        return $this->created_at->addDays(30)->isFuture();
    }

    public function expiresAt()
    {
        return $this->created_at->addDays(30);
    }
}
