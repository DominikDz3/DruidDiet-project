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

        User::create([
                'email' => 'akowalczyk@example.com',
                'password' => bcrypt('password'),
                'name' => 'Agnieszka',
                'surname' => 'Kowalczyk',
                'role' => 'user',
                'loyalty_points' => 50,
                'allergens' => '',
        ]);

        User::create([
                'email' => 'mlewandowski@example.com',
                'password' => bcrypt('password'),
                'name' => 'Michał',
                'surname' => 'Lewandowski',
                'role' => 'user',
                'loyalty_points' => 75,
                'allergens' => 'orzechy',
        ]);

        User::create([
                'email' => 'ezajac@example.com',
                'password' => bcrypt('password'),
                'name' => 'Ewa',
                'surname' => 'Zając',
                'role' => 'user',
                'loyalty_points' => 20,
                'allergens' => 'jajka',
        ]);

        User::create([
                'email' => 'pkaczmarek@example.com',
                'password' => bcrypt('password'),
                'name' => 'Piotr',
                'surname' => 'Kaczmarek',
                'role' => 'user',
                'loyalty_points' => 120,
                'allergens' => '',
        ]);

        User::create([
                'email' => 'dwozniak@example.com',
                'password' => bcrypt('password'),
                'name' => 'Dorota',
                'surname' => 'Woźniak',
                'role' => 'user',
                'loyalty_points' => 80,
                'allergens' => 'ryby',
        ]);

        User::create([
                'email' => 'ggorski@example.com',
                'password' => bcrypt('password'),
                'name' => 'Grzegorz',
                'surname' => 'Górski',
                'role' => 'user',
                'loyalty_points' => 45,
                'allergens' => 'pszenica',
        ]);

        User::create([
                'email' => 'kzielinska@example.com',
                'password' => bcrypt('password'),
                'name' => 'Katarzyna',
                'surname' => 'Zielińska',
                'role' => 'user',
                'loyalty_points' => 90,
                'allergens' => '',
        ]);

    }
}
