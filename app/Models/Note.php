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
        'rejection_reason',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function ratings()
    {
        return $this->hasMany(NoteRating::class);
    }

    public function averageRating()
    {
        return round($this->ratings()->avg('rating'), 1);
    }

    public function ratingCount()
    {
        return $this->ratings()->count();
    }
    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }


    public function isSubscribedBy($user)
        {
            if (!$user) return false;

            return Subscription::where('student_id', $user->id)
                ->where('provider_id', $this->provider_id)
                ->where('status', 'active')
                ->exists();
        }

}
