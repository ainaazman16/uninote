<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = ['provider_id', 'amount', 'status', 'bank_name', 'account_number', 'account_name'];

   public function provider()
{
    return $this->belongsTo(User::class, 'provider_id');
}
}
