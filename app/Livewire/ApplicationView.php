<?php

namespace App\Livewire;

use App\Models\JobApplication;
use Livewire\Attributes\On;
use Livewire\Component;

class ApplicationView extends Component
{
    public ?JobApplication $application = null;
    public bool $showModal = false;

    #[On('applicationViewed')]
    public function viewApplication($applicationId)
    {
        $this->application = JobApplication::with('job')->find($applicationId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->application = null;
    }

    public function render()
    {
        return view('livewire.application-view', [
            'application' => $this->application,
        ]);
    }
}
