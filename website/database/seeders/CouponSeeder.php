<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::create([
            'code' => 'DISCOUNT10',
            'user_id' => \App\Models\User::where('email', 'anowak@example.com')->first()->user_id,
            'discount_value' => 0.1,
            'is_used' => false,
            'created_at' => now()
        ]);

        Coupon::create([
            'code' => 'DISCOUNT30',
            'user_id' => \App\Models\User::where('email', 'jkowalski@example.com')->first()->user_id,
            'discount_value' => 0.3,
            'is_used' => false,
            'created_at' => now()
        ]);
    }
}