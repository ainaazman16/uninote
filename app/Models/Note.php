<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
        protected $fillable = [
        'provider_id',
        'subject_id',
        'title',
        'description',
        'file_path',
        'is_premium',
        'status',
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
