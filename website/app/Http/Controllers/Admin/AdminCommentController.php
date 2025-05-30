<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class AdminCommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'catering', 'diet'])->orderBy('created_at', 'desc');

        // Proste wyszukiwanie po treści komentarza lub nazwie użytkownika
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('comment_text', 'like', "%{$searchTerm}%")
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', "%{$searchTerm}%")
                                ->orWhere('surname', 'like', "%{$searchTerm}%");
                  });
            });
        }

        $comments = $query->paginate(15); // Paginacja

        return view('admin.comments.index', compact('comments'));
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('admin.comments.index')->with('success', 'Komentarz został pomyślnie usunięty.');
    }
}