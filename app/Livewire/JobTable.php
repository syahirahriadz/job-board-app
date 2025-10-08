<?php

namespace App\Livewire;

use App\Models\Job;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class JobTable extends Component
{
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
        //event
        $this->dispatch('openJobCreate');
    }

    public function editJob($jobId)
    {
        $this->dispatch('editJob', $jobId);
    }

    //to load all job
    public function mount()
    {
        // $this->refreshJobs();
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
        // if (empty($this->currentSearch)) {
        //     return Job::latest()->paginate($this->perPage);
        // } else {
        //     return Job::where('title', 'like', '%' . $this->currentSearch . '%')
        //         ->orWhere('company', 'like', '%' . $this->currentSearch . '%')
        //         ->orWhere('location', 'like', '%' . $this->currentSearch . '%')
        //         ->latest()
        //         ->paginate($this->perPage);
        // }
        $query = Job::query();

        if (!empty($this->currentSearch)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->currentSearch . '%')
                ->orWhere('company', 'like', '%' . $this->currentSearch . '%')
                ->orWhere('location', 'like', '%' . $this->currentSearch . '%');
            });
        }

        return $this->usePagination
            ? $query->latest()->paginate($this->perPage)
            : $query->latest()->get();
    }

    #[On('loadMore')]
    public function loadMore()
    {
        $this->perPage +=5;
    }

    public function render()
    {
        $jobs = $this->refreshJobs();
        return view('livewire.job-table', [
            'jobs' => $jobs,
        ]);
    }
}
