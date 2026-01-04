<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'student_id',
        'provider_id',
        'rating',
        'comment',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}

