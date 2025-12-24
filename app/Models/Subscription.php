<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'student_id',
        'provider_id',
        'status',
        'started_at',
        'ended_at'
    ];
}