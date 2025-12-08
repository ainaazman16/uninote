<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
