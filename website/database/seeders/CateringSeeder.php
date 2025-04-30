<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Catering;

class CateringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Catering::create([
            'title' => 'Katering osiemnstka',
            'description' => 'Pełny katering na imprezę osiemnastkową',
            'type' => 'Katering impreza',
            'elements' => 'Mini kanapki z różnymi pastami, wędlinami i serami, Pizza w różnych wariantach, np. z kurczakiem, pieczarkami, serem, Sałatka (grecka, cezar, z kurczakiem)',
            'price' => 2000,
            'photo' => '',
            'allergens' => 'gluten'
        ]);

        Catering::create([
            'title' => 'Katering grill',
            'description' => 'Idealny katering na domowe spotkanie przy grillu z naszym kateringiem',
            'type' => 'dieta białkowa',
            'elements' => 'Grillowane warzywa (papryka, cukinia, bakłażan, ziemniaki), Grillowana pierś kurczaka, Sosy (sos tatarski, sos z suszonymi pomidorami), Pieczone ziemniaki z ziołami i serem',
            'price' => 1000,
            'photo' => '',
            'allergens' => 'laktoza'
        ]);
    }
}
