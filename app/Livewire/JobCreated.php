<?php

namespace App\Livewire;

use App\Models\Job;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;


class JobCreated extends Component
{
    //properties
    #[Validate('required|string|max:255')]
    public $title = '';

    #[Validate('required|string|max:255')]
    public $company = '';

    #[Validate('required|string|max:255')]
    public $location;

    #[Validate('nullable|string|max:255')]
    public $description;

    public $showModal = false;

    //method

    #[On('openJobCreate')]
    public function openModal()
    {
        // $this->reset(['title', 'company', 'location', 'description']);
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $job = Job::create([
            'title' => $this->title,
            'company' => $this->company,
            'location' => $this->location,
            'description' => $this->description,
        ]);

        // $this->dispatch('jobCreated', job: [
        //     'title' => $this->title,
        //     'company' => $this->company,
        //     'location' => $this->location,
        //     'description' => $this->description,
        // ]);

        $this->dispatch('jobCreated', $job->id);

        // Reset fields and close modal
        // $this->reset(['title', 'company', 'location', 'description']);
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['title', 'company', 'location', 'description']);
    }

    // public function clear()
    // {
    //     $this->title = '';
    //     $this->company = '';
    //     $this->location = '';
    //     $this->description = '';
    // }

    public function render()
    {
        return view('livewire.job-created');
    }
}
