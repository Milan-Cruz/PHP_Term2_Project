<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Article $article)
    {
        // Validate the form inputs
        $request->validate([
            'content' => 'required|string',
            'captcha' => 'required'
        ]);

        // CAPTCHA verification
        if (session('captcha_code') !== $request->input('captcha')) {
            return redirect()->back()->withInput()->withErrors(['captcha' => 'The CAPTCHA is incorrect.']);
        }

        // Store the comment
        Comment::create([
            'content' => $request->input('content'),
            'user_id' => Auth::id(),
            'article_id' => $article->id,
        ]);

        return redirect()->route('articles.show', $article)->with('success', 'Comment added successfully.');
    }

    public function edit(Comment $comment)
    {
        $this->authorize('update', $comment);

        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update([
            'content' => $request->content,
            'updated_at' => now(),
        ]);

        return redirect()->route('articles.show', $comment->article_id)->with('success', 'Comment updated successfully.');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
}
