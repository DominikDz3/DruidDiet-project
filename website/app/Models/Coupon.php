<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    protected $primaryKey = 'coupon_id';
    public $timestamps = false;

    protected $fillable = [
        'code', 'user_id', 'discount_value', 'is_used', 'created_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
