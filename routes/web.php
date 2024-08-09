<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// User Routes
Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class);
});

// Topic Routes
Route::middleware('auth')->group(function () {
    Route::resource('topics', TopicController::class);
});

// Article Routes
Route::middleware('auth')->group(function () {
    Route::resource('articles', ArticleController::class);
    Route::patch('articles/{article}/reset-image', [ArticleController::class, 'resetImage'])->name('articles.resetImage');
});

// Comment Routes
Route::middleware('auth')->group(function () {
    Route::post('articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::patch('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    // Add other admin routes here
});

require __DIR__.'/auth.php';
