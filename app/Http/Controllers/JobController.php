<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $employerOnly = $user && $user->role === 'employer';

        return view('jobs.index', compact('employerOnly'));
    }
}
