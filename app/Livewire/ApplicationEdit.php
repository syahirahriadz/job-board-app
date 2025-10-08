<?php

namespace App\Livewire;

use App\Models\JobApplication;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class ApplicationEdit extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public ?JobApplication $application = null;
    public $applicationId;


    // Personal Information
    #[Validate('required|string|max:255')]
    public $full_name = '';

    #[Validate('required|email|max:255')]
    public $email = '';

    #[Validate('required|string|max:20')]
    public $phone_number = '';

    // Professional Info
    #[Validate('nullable|file|mimes:pdf,doc,docx|max:10240')] // max 10MB
    public $resume; // optional new upload
    public $resume_path; // existing stored path

    #[Validate('nullable|string|max:2000')]
    public $cover_letter = '';

    public $cover_letter_path;
    #[Validate('nullable|file|mimes:pdf,doc,docx|max:10240')] // max 10MB
    public $cover_letter_file; // optional new upload

    #[Validate('nullable|url|max:255')]
    public $linkedin_url = '';

    #[Validate('nullable|url|max:255')]
    public $portfolio_url = '';

    // Application-specific
    #[Validate('required|string|max:1000')]
    public $why_interested = '';

    #[Validate('nullable|string|max:100')]
    public $expected_salary = '';

    #[Validate('required|date|after:yesterday')]
    public $available_start_date = '';

    #[Validate('boolean')]
    public $willing_to_relocate = false;

    #[On('editApplication')]
    public function openEditModal($applicationId)
    {
        $this->applicationId = $applicationId;
        $application = JobApplication::find($applicationId);

        if ($application) {
            $this->full_name = $application->full_name;
            $this->email = $application->email;
            $this->phone_number = $application->phone_number;
            $this->resume_path = $application->resume_path;
            $this->cover_letter = $application->cover_letter;
            $this->cover_letter_path = $application->cover_letter_path;
            $this->linkedin_url = $application->linkedin_url;
            $this->portfolio_url = $application->portfolio_url;
            $this->why_interested = $application->why_interested;
            $this->expected_salary = $application->expected_salary;
            $this->available_start_date = $application->available_start_date;
            $this->willing_to_relocate = $application->willing_to_relocate;

            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->application = null;
        $this->resetForm();
        $this->resetValidation();
    }



    public function update()
    {
        $this->validate();

        $application = JobApplication::find($this->applicationId);
        if ($application) {
            // Handle resume file upload
            if ($this->resume) {
                // Delete old file if exists
                if ($application->resume_path && Storage::disk('public')->exists($application->resume_path)) {
                    Storage::disk('public')->delete($application->resume_path);
                }
                $resumePath = $this->resume->store('resumes', 'public');
            } else {
                $resumePath = $this->resume_path;
            }

            // Handle cover letter file upload
            if ($this->cover_letter_file) {
                // Delete old file if exists
                if ($application->cover_letter_path && Storage::disk('public')->exists($application->cover_letter_path)) {
                    Storage::disk('public')->delete($application->cover_letter_path);
                }
                $coverLetterFilePath = $this->cover_letter_file->store('cover-letters', 'public');
            } else {
                $coverLetterFilePath = $this->cover_letter_path;
            }

            // Update application with new data
            $application->update([
                'full_name' => $this->full_name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'resume_path' => $resumePath,
                'cover_letter' => $this->cover_letter,
                'cover_letter_path' => $coverLetterFilePath,
                'linkedin_url' => $this->linkedin_url,
                'portfolio_url' => $this->portfolio_url,
                'why_interested' => $this->why_interested,
                'expected_salary' => $this->expected_salary,
                'available_start_date' => $this->available_start_date,
                'willing_to_relocate' => $this->willing_to_relocate,
            ]);
        }

        $this->dispatch('applicationUpdated');
        $this->closeModal();

        session()->flash('message', 'Application updated successfully.');
    }

    public function resetForm()
    {
        $this->full_name = '';
        $this->email = '';
        $this->phone_number = '';
        $this->resume = null;
        $this->resume_path = null;
        $this->cover_letter = '';
        $this->cover_letter_file = null;
        $this->cover_letter_path = null;
        $this->linkedin_url = '';
        $this->portfolio_url = '';
        $this->why_interested = '';
        $this->expected_salary = '';
        $this->available_start_date = '';
        $this->willing_to_relocate = false;
    }

    public function render()
    {
        return view('livewire.application-edit');
    }
}
