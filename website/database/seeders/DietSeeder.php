<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Diet;

class DietSeeder extends Seeder
{
    public function run()
    {
        Diet::create([
            'title' => 'Dieta Vege',
            'description' => '',
            'type' => 'dieta wegetariańska',
            'calories' => 2000,
            'elements' => 'Kukurydza z piekarnika, Gnocchi z batatów, Makaron z sosem brokułowym',
            'price' => 50,
            'photo' => '',
            'allergens' => 'gluten'
        ]);

        Diet::create([
            'title' => 'Dieta wysokobiałkowa',
            'description' => '',
            'type' => 'dieta białkowa',
            'calories' => 3000,
            'elements' => 'Twaróg z dodatkiem owoców i orzechów, Chrupiący kurczak w sosie Sweet Chili, Zupa Tikka Masala',
            'price' => 49.00,
            'photo' => '',
            'allergens' => 'laktoza'
        ]);
    }
}
