<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator; // Dodane do walidacji
use Carbon\Carbon; // Dodane do obsługi daty

class CommentController extends Controller
{
    public function store(Request $request, Catering $catering)
    {
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Musisz być zalogowany, aby dodać komentarz.');
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment_text' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        Comment::create([
            'user_id' => Auth::id(),
            'catering_id' => $catering->catering_id,
            'diet_id' => null, // Komentarz dotyczy cateringu
            'rating' => $request->input('rating'),
            'comment_text' => $request->input('comment_text'),
            'created_at' => Carbon::now()->toDateString(), // Ustawiamy datę ręcznie
        ]);

        return redirect()->route('caterings.show', $catering->catering_id)
                         ->with('success', 'Dziękujemy za dodanie opinii!');
    }
}