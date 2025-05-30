<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $primaryKey = 'comment_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'diet_id', 'catering_id', 'rating', 'comment_text', 'created_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function diet(): BelongsTo // Zmieniono nazwę z diet_id() dla spójności
    {
        return $this->belongsTo(Diet::class, 'diet_id', 'diet_id');
    }

    // DODANA RELACJA
    public function catering(): BelongsTo
    {
        return $this->belongsTo(Catering::class, 'catering_id', 'catering_id');
    }
}