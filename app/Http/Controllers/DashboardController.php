<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        if (! $user) {
            abort(403);
        }

        $data = [];

        if ($user->isAdmin()) {
            // Admin sees everything
            $data['totalJobs'] = Job::count();
            $data['totalUsers'] = User::count();
            $data['totalApplications'] = JobApplication::count();
            $data['newApplications'] = JobApplication::where('status', 'pending')->count();
            $data['pendingApplications'] = JobApplication::where('status', 'pending')->count();
            $data['approvedApplications'] = JobApplication::where('status', 'approved')->count();

        } elseif ($user->isEmployer()) {
            // Employer sees their own jobs and related applications
            $data['totalJobs'] = Job::where('user_id', $user->id)->count();
            $data['totalApplications'] = JobApplication::whereHas('job', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count();

            $data['newApplications'] = JobApplication::whereHas('job', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', 'pending')->count();

            $data['pendingApplications'] = JobApplication::whereHas('job', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', 'pending')->count();

            $data['approvedApplications'] = JobApplication::whereHas('job', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', 'approved')->count();

        } else {
            // Job seeker sees their applications
            $data['totalJobs'] = Job::count(); // For consistency
            $data['totalUsers'] = 0; // Not applicable
            $data['totalApplications'] = JobApplication::where('email', $user->email)->count();
            $data['pendingApplications'] = JobApplication::where('email', $user->email)
                ->where('status', 'pending')
                ->count();
            $data['approvedApplications'] = JobApplication::where('email', $user->email)
                ->where('status', 'approved')
                ->count();
            $data['newApplications'] = 0; // Job seekers don't have new applications to review
        }

        return view('dashboard', $data);
    }
}
