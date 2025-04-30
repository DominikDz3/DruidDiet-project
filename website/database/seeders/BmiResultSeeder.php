<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BmiResult;

class BmiResultSeeder extends Seeder
{
    public function run(): void
    {
        Bmiresult::create([
            'user_id' => \App\Models\User::where('email', 'anowak@example.com')->first()->user_id,
            'bmi_value' => 23.5,
            'created_at' => now()->toDateString()
        ]);

        Bmiresult::create([
            'user_id' => \App\Models\User::where('email', 'jkowalski@example.com')->first()->user_id,
            'bmi_value' => 21.2,
            'created_at' => now()->toDateString()
        ]);
    }
}