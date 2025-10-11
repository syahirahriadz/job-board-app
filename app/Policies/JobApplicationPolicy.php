<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\User;

class JobApplicationPolicy
{
    /**
     * View all applications (admin and employers can view applications).
     */
    public function viewAny(User $user): bool
    {
        // Admin can view all applications
        // Employers can view applications for their jobs
        // Regular users can view their own applications
        return in_array($user->role, ['admin', 'employer']) || $user->role === 'guest';
    }

    /**
     * Determine if the user can view their own applications (for menu visibility).
     * Only guests/job seekers should see the "Job Applied" menu.
     */
    public function viewOwnApplications(User $user): bool
    {
        return $user->role === 'guest';
    }

    /**
     * View a single application.
     * Admin can view all, employer can view applications for their jobs, guest only their own.
     */
    public function view(User $user, JobApplication $application): bool
    {
        // Admin can view all applications
        if ($user->role === 'admin') {
            return true;
        }

        // Employer can view applications for their jobs
        if ($user->role === 'employer') {
            return $application->job->user_id === $user->id;
        }

        // Guest can view their own applications
        return $user->email === $application->email;
    }

    /**
     * Update application.
     */
    public function update(User $user, JobApplication $application): bool
    {
        // Only the applicant can update their application
        return $user->email === $application->email;
    }

    /**
     * Delete application.
     */
    public function delete(User $user, JobApplication $application): bool
    {
        // Admin can delete any application
        if ($user->role === 'admin') {
            return true;
        }

        // Employer can delete applications for their jobs
        if ($user->role === 'employer') {
            return $application->job->user_id === $user->id;
        }

        // Guest can delete their own applications
        return $user->email === $application->email;
    }

    /**
     * Determine if the user can approve the application
     */
    public function approve(User $user, JobApplication $application): bool
    {
        // Both admin and employer can approve non-approved applications
        if ($application->status === 'approved') {
            return false;
        }

        if ($user->role === 'admin') {
            return true;
        }

        // Employer can only approve applications for their own jobs
        return $user->role === 'employer' && $application->job->user_id === $user->id;
    }

    /**
     * Determine if the user can reject the application
     */
    public function reject(User $user, JobApplication $application): bool
    {
        // Both admin and employer can reject non-rejected applications
        if ($application->status === 'rejected') {
            return false;
        }

        if ($user->role === 'admin') {
            return true;
        }

        // Employer can only reject applications for their own jobs
        return $user->role === 'employer' && $application->job->user_id === $user->id;
    }

    /**
     * Determine if the user can change the application status
     */
    public function updateStatus(User $user, JobApplication $application): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        // Employer can update status for their own job applications
        return $user->role === 'employer' && $application->job->user_id === $user->id;
    }
}
