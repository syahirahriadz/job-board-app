<?php

namespace App\Livewire;

use App\Models\Job;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\Validate;

class JobEdit extends Component
{
    public $jobId;

    #[Validate('required|string|max:255')]
    public $title = '';

    #[Validate('required|string|max:255')]
    public $company;

    #[Validate('required|string|max:255')]
    public $location;

    #[Validate('nullable|string|max:255')]
    public $description;

    public $showModal = false;

    #[On('editJob')]
    public function openEditModal($jobId)
    {
        $this->jobId = $jobId;
        $job = Job::find($jobId);
        if ($job) {
            $this->title = $job->title;
            $this->company = $job->company;
            $this->location = $job->location;
            $this->description = $job->description;
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['jobId', 'title', 'company', 'location', 'description']);
    }

    public function update()
    {
        $this->validate();

        $job = Job::find($this->jobId);
        if ($job) {
            $job->update([
                'title' => $this->title,
                'company' => $this->company,
                'location' => $this->location,
                'description' => $this->description,
            ]);
        }

        $this->dispatch('jobUpdated'); //'jobUpdated' can be any name but related
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.job-edit');
    }
}
