<?php

namespace App\Livewire;

use App\Models\Job;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class JobTable extends Component
{
    use AuthorizesRequests;

    public $currentSearch = '';

    public $perPage = 5;

    public bool $usePagination = true;

    public $statusFilter = 'all'; // 'all', 'published', 'pending'

    #[On('jobCreated')]
    public function handleJobCreated($jobId)
    {
        // Component will auto-refresh
    }

    #[On('jobUpdated')]
    public function handleJobUpdated()
    {
        // Component will auto-refresh
    }

    #[On('paymentCompleted')]
    public function handlePaymentCompleted()
    {
        // Component will auto-refresh
    }

    #[On('redirect-to-checkout')]
    public function redirectToCheckout()
    {
        // Get the pending job ID from session
        $pendingJobId = session('pending_job_id');

        if ($pendingJobId) {
            return redirect()->route('checkout', $pendingJobId);
        }

        // Fallback to checkout without job ID
        return redirect()->route('checkout');
    }

    public function refreshJobTable()
    {
        // Force refresh of the component
        $this->dispatch('$refresh');
    }    // public function viewJob($jobId)
    // {
    //     //event
    //     $this->dispatch('jobViewed', $jobId);
    // }

    public function createJob()
    {
        // event
        $this->dispatch('openJobCreate');
    }

    public function editJob($jobId)
    {
        $this->dispatch('editJob', $jobId);
    }

    public function mount()
    {
        // Disable pagination if not on index route
        $this->usePagination = ! request()->routeIs('jobs.index');

        // Check for filter parameter in URL
        if (request()->has('filter')) {
            $filter = request()->get('filter');
            if (in_array($filter, ['all', 'published', 'pending'])) {
                $this->statusFilter = $filter;
            }
        }
    }

    public function deleteJob($jobId)
    {
        $job = Job::find($jobId);
        if ($job) {
            $job->delete();
        }
    }

    #[On('searchUpdated')]
    public function handleSearchUpdated($search)
    {
        $this->currentSearch = $search;
    }

    public function setStatusFilter($filter)
    {
        $this->statusFilter = $filter;
    }

    #[On('loadMore')]
    public function loadMore()
    {
        $this->perPage += 5;
    }

    public function render()
    {
        $jobs = $this->getJobs();

        return view('livewire.job-table', [
            'jobs' => $jobs,
            'totalJobsCount' => $this->getTotalJobsCount(),
            'publishedJobsCount' => $this->getPublishedJobsCount(),
            'pendingJobsCount' => $this->getPendingJobsCount(),
        ]);
    }

    protected function getJobs()
    {
        $query = Job::query()->withCount('jobApplications');

        // 🔑 Role-based filtering (similar to ApplicationTable pattern)
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'employer') {
                // Employers → only their jobs
                $query->where('user_id', $user->id);
            }
            // Admin → no filter, see all jobs
            // Job seekers → see all jobs (they can browse all available jobs)
        }

        // 🔍 Search filter
        if (! empty($this->currentSearch)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->currentSearch.'%')
                    ->orWhere('company', 'like', '%'.$this->currentSearch.'%')
                    ->orWhere('location', 'like', '%'.$this->currentSearch.'%');
            });
        }

        // 📊 Status filter
        if ($this->statusFilter === 'published') {
            $query->where('is_published', true);
        } elseif ($this->statusFilter === 'pending') {
            $query->where('is_published', false);
        }
        // 'all' shows both published and pending

        return $this->usePagination
            ? $query->latest()->paginate($this->perPage)
            : $query->latest()->get();
    }

    // Methods to get total counts (independent of current filter)
    public function getTotalJobsCount()
    {
        $query = Job::query();

        // Apply same role-based filtering but no status/search filters
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'employer') {
                $query->where('user_id', $user->id);
            }
        }

        return $query->count();
    }

    public function getPublishedJobsCount()
    {
        $query = Job::query()->where('is_published', true);

        // Apply same role-based filtering
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'employer') {
                $query->where('user_id', $user->id);
            }
        }

        return $query->count();
    }

    public function getPendingJobsCount()
    {
        $query = Job::query()->where('is_published', false);

        // Apply same role-based filtering
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'employer') {
                $query->where('user_id', $user->id);
            }
        }

        return $query->count();
    }
}
