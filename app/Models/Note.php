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
        return $this->belongsTo(Provider::class, 'provider_id');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
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
