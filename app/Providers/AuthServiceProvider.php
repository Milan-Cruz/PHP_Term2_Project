<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Topic;
use App\Models\Comment;
use App\Policies\ArticlePolicy;
use App\Policies\TopicPolicy;
use App\Policies\CommentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Article::class => ArticlePolicy::class,
        Topic::class => TopicPolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
