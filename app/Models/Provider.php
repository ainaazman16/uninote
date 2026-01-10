<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Note;
use App\Models\Subscription;
use Illuminate\Notifications\Notifiable;

class Provider extends Model
{
    use HasFactory;
    use Notifiable;
    
    protected $fillable = [
        'user_id',
        'status',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function notes()
    {
        return $this->hasMany(Note::class);
    }
    public function subscriptions()
{
    return $this->hasMany(Subscription::class);
}

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
