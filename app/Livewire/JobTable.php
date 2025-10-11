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

    #[On('jobCreated')]
    public function handleJobCreated($jobId)
    {
        $this->refreshJobs();
    }

    #[On('jobUpdated')]
    public function handleJobUpdated()
    {
        $this->refreshJobs();
    }

    // public function viewJob($jobId)
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
    }

    public function deleteJob($jobId)
    {
        $job = Job::find($jobId);
        if ($job) {
            $job->delete();
            $this->refreshJobs();
        }
    }

    #[On('searchUpdated')]
    public function handleSearchUpdated($search)
    {
        $this->currentSearch = $search;
        $this->refreshJobs();
    }

    protected function refreshJobs()
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

        return $this->usePagination
            ? $query->latest()->paginate($this->perPage)
            : $query->latest()->get();
    }

    #[On('loadMore')]
    public function loadMore()
    {
        $this->perPage += 5;
    }

    public function render()
    {
        $jobs = $this->refreshJobs();

        return view('livewire.job-table', [
            'jobs' => $jobs,
        ]);
    }
}
