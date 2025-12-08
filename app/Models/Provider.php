<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
