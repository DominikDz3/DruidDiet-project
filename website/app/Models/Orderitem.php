<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $primaryKey = 'order_item_id';
    public $timestamps = false;

    protected $fillable = [
        'order_id', 'catering_id', 'diet_id', 'quantity', 'price_per_item'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function diet(): BelongsTo
    {
        return $this->belongsTo(Diet::class, 'diet_id', 'diet_id');
    }

    public function catering(): BelongsTo
    {
        return $this->belongsTo(Catering::class, 'catering_id', 'catering_id');
    }
}
