<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Diet;
use App\Models\OrderItem;


class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $order1 = Order::first();
        $item1 = Diet::where('title', 'Dieta Vege')->first();

        OrderItem::create([
            'order_id' => $order1->order_id,
            'diet_id' => $item1->diet_id,
            'quantity' => 1,
            'price_per_item' => 25.50
        ]);
    }
}
