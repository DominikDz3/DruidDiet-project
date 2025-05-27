<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Catering extends Model
{
    protected $primaryKey = 'catering_id';
    public $timestamps = false;

    protected $fillable = [
        'title', 'description', 'type', 'elements',
        'price', 'photo', 'allergens'
    ];


    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'catering_id', 'catering_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'catering_id', 'catering_id');
    }
}
