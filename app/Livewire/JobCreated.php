<?php

namespace App\Livewire;

use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class JobCreated extends Component
{
    // properties
    #[Validate('required|string|max:255')]
    public $title = '';

    #[Validate('required|string|max:255')]
    public $company = '';

    #[Validate('required|string|max:255')]
    public $location;

    #[Validate('nullable|string')]
    public $description;

    public $showModal = false;

    // method

    #[On('openJobCreate')]
    public function openModal()
    {
        // $this->reset(['title', 'company', 'location', 'description']);
        $this->showModal = true;
    }

    #[On('ai-response-generated')]
    public function handleAiResponse($response)
    {
        $this->description = $response;
    }

    public function openAiModal()
    {
        $this->dispatch('openAiModal');
    }

    public function save()
    {
        $this->validate();

        // Always create unpublished job first - employers must pay for each job individually
        $job = Job::create([
            'title' => $this->title,
            'company' => $this->company,
            'location' => $this->location,
            'description' => $this->description,
            'user_id' => Auth::id(),
            'is_published' => false, // Always start as unpublished
        ]);

        // Store job ID in session for after payment
        session(['pending_job_id' => $job->id]);

        $this->dispatch('jobCreated', $job->id);
        $this->closeModal();

        // Show success message and redirect to payment
        session()->flash('job_created_unpaid', 'Job created successfully! Please complete payment to publish your job listing.');
        $this->dispatch('redirect-to-checkout');
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
