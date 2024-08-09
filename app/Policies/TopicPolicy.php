<?php

namespace App\Policies;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any topics.
     */
    public function viewAny(User $user)
    {
        return true; // All authenticated users can view topics
    }

    /**
     * Determine whether the user can view the topic.
     */
    public function view(User $user, Topic $topic)
    {
        return true; // All authenticated users can view a topic
    }

    /**
     * Determine whether the user can create topics.
     */
    public function create(User $user)
    {
        return $user->role === 'Admin'; // Only admins can create topics
    }

    /**
     * Determine whether the user can update the topic.
     */
    public function update(User $user, Topic $topic)
    {
        return $user->role === 'Admin'; // Only admins can update topics
    }

    /**
     * Determine whether the user can delete the topic.
     */
    public function delete(User $user, Topic $topic)
    {
        return $user->role === 'Admin'; // Only admins can delete topics
    }

    // Other policy methods as needed...
}
