<?php

namespace App\Livewire;

use App\Models\Job;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class JobList extends Component
{
    // public Collection $jobs;
    // public $jobs = [];
    public $currentSearch = '';

    public $perPage = 5;

    #[On('jobCreated')]
    public function handleJobCreated($jobId)
    {
        // $this->jobs[] = Job::find($jobId);
        // $this->jobs->add(Job::find($jobId));

        // if ($job = Job::find($jobId)) {
        //     $this->jobs[] = $job->toArray();
        // }

        $this->refreshJobs();

    }

    #[On('jobUpdated')]
    public function handleJobUpdated()
    {
        // $this->jobs = Job::all()->toArray();
        $this->refreshJobs();
    }

    public function viewJob($jobId)
    {
        // event
        $this->dispatch('jobViewed', $jobId);
    }

    public function editJob($jobId)
    {
        $this->dispatch('editJob', $jobId);
    }

    // to load all job
    public function mount()
    {
        // $this->jobs = Job::all()->toArray();
        $this->refreshJobs();
    }

    public function deleteJob($jobId)
    {
        // check if job available
        // if (isset($this->jobs[$jobId])){

        //     unset($this->jobs[$jobId]);       // remove job by index
        //     $this->jobs[] = array_values($this->jobs[]); // reindex array
        // }

        // delete using Collection
        // $job = Job::find($jobId);
        // if ($job) {
        //     $job->delete();
        //     $this->refreshJobs();
        //     return $this->jobs->filter(fn($j) => $j->id !== $jobId);
        // }

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
        if (empty($this->currentSearch)) {
            return Job::where('is_published', true)->latest()->paginate($this->perPage);
        } else {
            return Job::where('is_published', true)
                ->where(function ($query) {
                    $query->where('title', 'like', '%'.$this->currentSearch.'%')
                        ->orWhere('company', 'like', '%'.$this->currentSearch.'%')
                        ->orWhere('location', 'like', '%'.$this->currentSearch.'%');
                })
                ->latest()
                ->paginate($this->perPage);
        }

        // $query = Job::query();

        // if (!empty($this->currentSearch)) {
        //     $query->where('title', 'like', '%' . $this->currentSearch . '%')
        //         ->orWhere('company', 'like', '%' . $this->currentSearch . '%')
        //         ->orWhere('location', 'like', '%' . $this->currentSearch . '%');
        // }

        // $this->jobs = $query->latest()->get()->toArray();
    }

    #[On('loadMore')]
    public function loadMore()
    {
        $this->perPage += 5;
    }

    public function render()
    {
        $jobs = $this->refreshJobs();

        return view('livewire.job-list', [
            'jobs' => $jobs,
        ]);
    }
}
