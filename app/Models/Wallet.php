<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'balance'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public static function ensure(User $user)
{
    return self::firstOrCreate(
        ['user_id' => $user->id],
        ['balance' => 0]
    );
}

}