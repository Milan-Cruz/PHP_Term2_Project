<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class HomeController extends Controller
{
    public function welcome()
    {
        // Fetching the 3 most recent articles
        $articles = Article::orderBy('created_at', 'desc')->take(3)->get();

        // Returning the view and passing the articles
        return view('welcome', compact('articles'));
    }
}
