<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'student_id',
        'provider_id',
        'amount',
        'description',
        'type',
        'status',
        'reference',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }


}
