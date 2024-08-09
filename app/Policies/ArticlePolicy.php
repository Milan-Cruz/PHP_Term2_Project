<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Article $article)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->role === 'Admin' || $user->role === 'Writer';
    }

    public function update(User $user, Article $article)
    {
        return $user->id === $article->created_by || $user->role === 'Admin';
    }

    public function delete(User $user, Article $article)
    {
        return $user->id === $article->created_by || $user->role === 'Admin';
    }
}
