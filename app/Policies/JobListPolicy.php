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
        // Admin and employers can view jobs
        return $user->isAdmin() || $user->isEmployer();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Job $job): bool
    {
        // Admin can view any job
        if ($user->isAdmin()) {
            return true;
        }

        // Employer can view their own jobs
        if ($user->isEmployer()) {
            return $job->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Both admin and employers can create jobs
        return $user->isAdmin() || $user->isEmployer();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Job $job): bool
    {
        // Admin can update any job
        if ($user->isAdmin()) {
            return true;
        }

        // Employer can update their own jobs
        if ($user->isEmployer()) {
            return $job->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Job $job): bool
    {
        // Admin can delete any job
        if ($user->isAdmin()) {
            return true;
        }

        // Employer can delete their own jobs
        if ($user->isEmployer()) {
            return $job->user_id === $user->id;
        }

        return false;
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
        // Both admin and employers can manage jobs
        return $user->isAdmin() || $user->isEmployer();
    }

    /**
     * Determine whether the user can manage their jobs (employer dashboard)
     */
    public function manageJobs(User $user): bool
    {
        // Both admin and employers can manage jobs
        return $user->isAdmin() || $user->isEmployer();
    }

    /**
     * Determine whether the user can publish/unpublish posts.
     */
    public function publish(User $user, Job $job): bool
    {
        // Admin can publish/unpublish any job
        if ($user->isAdmin()) {
            return true;
        }

        // Employer can publish/unpublish their own jobs
        if ($user->isEmployer()) {
            return $job->user_id === $user->id;
        }

        return false;
    }
}
