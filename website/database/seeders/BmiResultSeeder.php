<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BmiResult;

class BmiResultSeeder extends Seeder
{
    public function run(): void
    {
        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'anowak@example.com')->first()->user_id,
            'bmi_value' => 23.5,
            'created_at' => now()->subDays(10)->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'anowak@example.com')->first()->user_id,
            'bmi_value' => 24.0,
            'created_at' => now()->subDays(20)->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'anowak@example.com')->first()->user_id,
            'bmi_value' => 23.0,
            'created_at' => now()->subDays(2)->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'jkowalski@example.com')->first()->user_id,
            'bmi_value' => 21.2,
            'created_at' => now()->subDays(5)->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'jkowalski@example.com')->first()->user_id,
            'bmi_value' => 22.5,
            'created_at' => now()->subDays(50)->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'jkowalski@example.com')->first()->user_id,
            'bmi_value' => 21.0,
            'created_at' => now()->subDays(1)->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'akowalczyk@example.com')->first()->user_id,
            'bmi_value' => 26.5, 
            'created_at' => now()->subDays(30)->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'akowalczyk@example.com')->first()->user_id,
            'bmi_value' => 25.8, 
            'created_at' => now()->subDays(15)->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'mlewandowski@example.com')->first()->user_id,
            'bmi_value' => 20.1, 
            'created_at' => now()->subDays(7)->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'mlewandowski@example.com')->first()->user_id,
            'bmi_value' => 20.5, 
            'created_at' => now()->subDays(40)->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'ezajac@example.com')->first()->user_id,
            'bmi_value' => 17.9, 
            'created_at' => now()->subDays(20)->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'ezajac@example.com')->first()->user_id,
            'bmi_value' => 18.5, 
            'created_at' => now()->subDays(10)->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'pkaczmarek@example.com')->first()->user_id,
            'bmi_value' => 28.3, 
            'created_at' => now()->subDays(12)->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'pkaczmarek@example.com')->first()->user_id,
            'bmi_value' => 27.1, 
            'created_at' => now()->subDays(9)->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'dwozniak@example.com')->first()->user_id,
            'bmi_value' => 31.0, 
            'created_at' => now()->subDays(1)->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'dwozniak@example.com')->first()->user_id,
            'bmi_value' => 32.0, 
            'created_at' => now()->subDays(10)->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'dwozniak@example.com')->first()->user_id,
            'bmi_value' => 31.5, 
            'created_at' => now()->subDays(5)->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'ggorski@example.com')->first()->user_id,
            'bmi_value' => 22.9, 
            'created_at' => now()->subDays(8)->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'kzielinska@example.com')->first()->user_id,
            'bmi_value' => 24.5, 
            'created_at' => now()->toDateString()
        ]);

        BmiResult::create([
            'user_id' => \App\Models\User::where('email', 'kzielinska@example.com')->first()->user_id,
            'bmi_value' => 25.0, 
            'created_at' => now()->subDays(8)->toDateString()
        ]);
    }
}