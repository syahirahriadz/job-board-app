<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JobApplication;

class JobApplicationPolicy
{
    /**
     * View all applications (admin only).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * View a single application.
     * Admin can view all, guest only their own.
     */
    public function view(User $user, JobApplication $application): bool
    {
        if ($user->role === 'admin' || $user->email === $application->email) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update application.
     */
    public function update(User $user, JobApplication $application): bool
    {
        return $user->email === $application->email;
    }

    /**
     * Delete application.
     */
    public function delete(User $user, JobApplication $application): bool
    {
        return $user->email === $application->email;
    }

    /**
     * Determine if the user can approve the application
     */
    public function approve(User $user, JobApplication $application): bool
    {
        return $user->role === 'admin' && $application->status !== 'approved';
    }

    /**
     * Determine if the user can reject the application
     */
    public function reject(User $user, JobApplication $application): bool
    {
        return $user->role === 'admin' && $application->status !== 'rejected';
    }

    /**
     * Determine if the user can change the application status
     */
    public function updateStatus(User $user, JobApplication $application): bool
    {
        return $user->role === 'admin';
    }
}
