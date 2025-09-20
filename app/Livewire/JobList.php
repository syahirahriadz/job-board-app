<?php

namespace App\Livewire;

use App\Models\Job;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class JobList extends Component
{
    // public $jobs = [];
    public Collection $jobs;

    #[On('jobCreated')]
    public function handleJobCreated($jobId)
    {
        // $this->jobs[] = Job::find($jobId);
        $this->jobs->add(Job::find($jobId));
    }

     #[On('jobUpdated')]
    public function handleJobUpdated()
    {
        $this->jobs = Job::all();
    }

    public function viewJob($jobId)
    {
        //event
        $this->dispatch('jobViewed', $jobId);
    }

    public function editJob($jobId)
    {
        $this->dispatch('editJob', $jobId);
    }

    //to load all job
    public function mount()
    {
        $this->jobs = Job::all();
    }

    public function deleteJob($jobId)
    {
        // //check if job available
        // if (isset($this->jobs[$index])){

        //     unset($this->jobs[$index]);       // remove job by index
        //     $this->jobs = array_values($this->jobs); // reindex array
        // }

        $job = Job::find($jobId);
        if ($job) {
            $job->delete();
            $this->jobs = $this->jobs->filter(fn($j) => $j->id !== $jobId);
        }
    }

    public function render()
    {
        return view('livewire.job-list');
    }
}
