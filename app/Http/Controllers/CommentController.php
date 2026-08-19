<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Menyimpan komentar baru untuk artikel
     */
    public function store(Request $request, $articleId)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        // Pastikan artikel ada
        $article = Article::findOrFail($articleId);

        // Buat komentar baru
        Comment::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'article_id' => $article->id
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan');
    }

    /**
     * Menghapus komentar
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        
        // Pastikan user yang menghapus adalah pemilik komentar atau admin
        if (Auth::id() !== $comment->user_id && Auth::user()->role !== 'admin') {
            return back()->with('error', 'Anda tidak berhak menghapus komentar ini');
        }
        
        $comment->delete();
        return back()->with('success', 'Komentar berhasil dihapus');
    }
}
