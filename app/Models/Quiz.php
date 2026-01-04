<?php

namespace App\Models;
use App\Models\Note;
use App\Models\QuizQuestion;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['note_id', 'provider_id'];
    public function note()
{
    return $this->belongsTo(Note::class);
}

public function questions()
{
    return $this->hasMany(QuizQuestion::class);
}

}
