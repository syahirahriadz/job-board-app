<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\User;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalJobs = Job::count();
        $totalUsers = User::count();

        if (Auth::check()) {
            $userEmail = Auth::user()->email;

            if (Auth::user()->role === 'admin') {
                // Admin → count all applications
                $totalApplications = JobApplication::count();
                $pendingApplications = null;
                $approvedApplications = null;
            } else {
                // Applicant → count their own applications
                $totalApplications = JobApplication::where('email', $userEmail)->count();
                $pendingApplications = JobApplication::where('email', $userEmail)
                    ->where('status', 'pending')
                    ->count();
                $approvedApplications = JobApplication::where('email', $userEmail)
                    ->where('status', 'approved')
                    ->count();
            }
        } else {
            $totalApplications = 0; // Not logged in
        }

        return view('dashboard', compact('totalJobs', 'totalUsers', 'totalApplications', 'pendingApplications', 'approvedApplications'));
    }
}
