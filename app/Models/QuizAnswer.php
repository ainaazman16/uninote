<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    protected $fillable = [
        'quiz_attempt_id',
        'quiz_question_id',
        'selected_answer',
        'is_correct'
    ];

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }
}
