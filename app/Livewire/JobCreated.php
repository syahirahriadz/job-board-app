<?php

namespace App\Livewire;

use App\Models\Job;
use Livewire\Component;
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

    //method
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

        $this->clear();
    }

    public function clear()
    {
        $this->title = '';
        $this->company = '';
        $this->location = '';
        $this->description = '';
    }

    public function render()
    {
        return view('livewire.job-created');
    }
}
