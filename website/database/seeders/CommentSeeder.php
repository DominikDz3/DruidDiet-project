<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Diet;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $item1 = Diet::where('title', 'Dieta Vege')->first();

        Comment::create([
            'user_id' => \App\Models\User::where('email', 'jkowalski@example.com')->first()->user_id,
            'diet_id' => $item1->diet_id,
            'rating' => 5,
            'comment_text' => 'Bardzo smaczne danie!',
            'created_at' => now()
        ]);

        Comment::create([
            'user_id' => \App\Models\User::where('email', 'anowak@example.com')->first()->user_id,
            'diet_id' => $item1->diet_id,
            'rating' => 1,
            'comment_text' => 'Nie smakowało mi, średniawka',
            'created_at' => now()
        ]);
    }
}
