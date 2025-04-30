<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::create([
            'user_id' => \App\Models\User::where('email', 'jkowalski@example.com')->first()->user_id,
            'order_date' => Carbon::now(),
            'total_price' => 43.50,
            'status' => 'w przygotowaniu',
        ]);
    }
}
