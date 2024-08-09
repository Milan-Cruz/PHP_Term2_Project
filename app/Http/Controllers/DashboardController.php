<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $articles = Article::latest()->take(5)->get();
        return view('dashboard', compact('user', 'articles'));
    }
}
