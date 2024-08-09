<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController; // Importa o HomeController

// Rota da página inicial usando o HomeController
Route::get('/', [HomeController::class, 'welcome'])->name('home');

// Rota do Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Rotas de Perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rotas de Usuários
Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class);
});

// Rotas de Tópicos
Route::middleware('auth')->group(function () {
    Route::resource('topics', TopicController::class);
});

// Rotas de Artigos
Route::middleware('auth')->group(function () {
    Route::resource('articles', ArticleController::class);
    Route::patch('articles/{article}/reset-image', [ArticleController::class, 'resetImage'])->name('articles.resetImage');
});

// Rotas de Comentários
Route::middleware('auth')->group(function () {
    Route::post('articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::patch('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
});

// Rotas de Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    // Outras rotas de admin aqui
});

require __DIR__.'/auth.php';
