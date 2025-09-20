<?php

namespace App\Livewire;

use App\Models\Job;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class JobList extends Component
{
    public $jobs = [];
    // public Collection $jobs;

    #[On('jobCreated')]
    public function handleJobCreated($jobId)
    {
        // $this->jobs[] = Job::find($jobId);
        // $this->jobs->add(Job::find($jobId));

        if ($job = Job::find($jobId)) {
            $this->jobs[] = $job->toArray();
        }
    }

     #[On('jobUpdated')]
    public function handleJobUpdated()
    {
        $this->jobs = Job::all()->toArray();
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
        $this->jobs = Job::all()->toArray();
    }

    public function deleteJob($jobId)
    {
        //check if job available
        // if (isset($this->jobs[$jobId])){

        //     unset($this->jobs[$jobId]);       // remove job by index
        //     $this->jobs[] = array_values($this->jobs[]); // reindex array
        // }

        // $job = Job::find($jobId);
        // if ($job) {
        //     $job->delete();
        //     $this->jobs = $this->jobs->filter(fn($j) => $j->id !== $jobId);
        // }

        // Delete from database
        if ($job = Job::find($jobId)) {
            $job->delete();
        }

        // Remove from the array Alpine is entangled with
        $this->jobs = array_values(
            array_filter(
                $this->jobs,                  // $this->jobs is an array of arrays
                fn($j) => $j['id'] !== $jobId // keep all except the deleted id
            )
        );
    }

    public function render()
    {
        return view('livewire.job-list');
    }
}
