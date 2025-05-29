<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'email', 'password', 'name', 'surname', 'role',
        'TOTP_secret', 'loyalty_points', 'allergens'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class, 'user_id', 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id', 'user_id');
    }

    public function bmiResults(): HasMany
    {
        return $this->hasMany(BMIResult::class, 'user_id', 'user_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }
}