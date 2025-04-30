<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Diet extends Model
{
    protected $primaryKey = 'diet_id';
    public $timestamps = false;

    protected $fillable = [
        'title', 'description', 'type', 'calories', 'elements',
        'price', 'photo', 'allergens'
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'diet_id', 'diet_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'diet_id', 'diet_id');
    }
}
