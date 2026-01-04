<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteRating extends Model
{
    protected $fillable = [
        'note_id',
        'student_id',
        'rating',
        'comment',
    ];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
