<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;

class JobListPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->role === 'admin') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Job $job): bool
    {
        if ($user->role === 'admin') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // return Auth::user()->id !== null;
        if ($user->role === 'admin') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Job $job): bool
    {
        // Admin can update any post
        if ($user->role === 'admin') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Job $job): bool
    {
        // Admin can delete any post
        if ($user->role === 'admin') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Job $job): bool
    {
        // Only admins can restore posts
        if ($user->role === 'admin') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Job $job): bool
    {
        // Only admins can permanently delete posts
        if ($user->role === 'admin') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can manage posts (admin dashboard).
     */
    public function manage(User $user): bool
    {
        // Only admins can access post management features
        if ($user->role === 'admin') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can publish/unpublish posts.
     */
    public function publish(User $user, Job $job): bool
    {
        // Admin can publish/unpublish any post
        if ($user->role === 'admin') {
            return true;
        } else {
            return false;
        }

        // Authors can publish/unpublish their own posts
        // return $user->hasRole('author') && $post->user_id === $user->id;
    }
}
