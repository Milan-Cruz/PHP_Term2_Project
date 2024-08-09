<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Topic;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $recentArticles = Article::latest()->take(5)->get();
            $recentTopics = Topic::latest()->take(5)->get();
            $view->with(compact('recentArticles', 'recentTopics'));
        });
    }
}
