<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            DietSeeder::class,
            CateringSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            CouponSeeder::class,
            CommentSeeder::class,
            BMIResultSeeder::class,
        ]);
    }
}