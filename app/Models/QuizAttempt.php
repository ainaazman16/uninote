<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
     protected $fillable = [
        'quiz_id',
        'student_id',
        'score',
        'total_questions'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }
}
