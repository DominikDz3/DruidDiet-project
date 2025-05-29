<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BmiResult extends Model
{
    protected $primaryKey = 'bmi_result_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'bmi_value', 'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
