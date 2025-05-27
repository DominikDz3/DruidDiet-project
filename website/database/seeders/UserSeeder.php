<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'name' => 'Admin',
                'surname' => 'System',
                'role' => 'admin',
                'loyalty_points' => 0,
                'allergens' => '',
        ]);

        User::create([
                'email' => 'anowak@example.com',
                'password' => bcrypt('password'),
                'name' => 'Anna',
                'surname' => 'Nowak',
                'role' => 'user',
                'loyalty_points' => 30,
                'allergens' => 'laktoza',
        ]);

        User::create([
                'email' => 'jkowalski@example.com',
                'password' => bcrypt('password'),
                'name' => 'Jan',
                'surname' => 'Kowalski',
                'role' => 'user',
                'loyalty_points' => 100,
                'allergens' => 'laktoza, gluten',
        ]);
    }
}
