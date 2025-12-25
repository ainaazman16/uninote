<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Provider;
use App\Models\Wallet;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'name',
    'email',
    'password',
    'university',
    'programme',
    'year_of_study',
    'bio',
    'profile_photo',
];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
        public function provider()
    {
        return $this->hasOne(Provider::class);
    }
    public function wallet()
{
    return $this->hasOne(Wallet::class);
}
    public function subscriptions()
{
    return $this->hasMany(Subscription::class, 'student_id');
}

    public function providerApplication()
    {
        return $this->hasOne(ProviderApplication::class);
    }

    public function providerSubscriptions()
{
    return $this->hasMany(Subscription::class, 'provider_id');
}
    protected static function booted()
        {
            static::created(function ($user) {
                $user->wallet()->create(['balance' => 0]);
            });
        }



}
