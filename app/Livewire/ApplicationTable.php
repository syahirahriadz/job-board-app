<?php

namespace App\Livewire;

use App\Models\JobApplication;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ApplicationTable extends Component
{
    use AuthorizesRequests;

    public $currentSearch = '';

    public $statusFilter = '';

    public $perPage = 5;

    public bool $usePagination = true;

    #[On('applicationUpdated')]
    public function handleApplicationUpdated()
    {
        $this->refreshApplications();
    }

    public function viewApplication($applicationId)
    {
        // event
        $this->dispatch('applicationViewed', $applicationId);
    }

    public function editApplication($applicationId)
    {
        $this->dispatch('editApplication', $applicationId);
    }

    public function mount()
    {
        // disable pagination if not on index route
        $this->usePagination = ! request()->routeIs('applications.index');

        // Set initial status filter from URL parameter
        $this->statusFilter = request()->get('status', '');
    }

    #[On('applicationSearchUpdated')]
    public function handleSearchUpdated($searchApplication)
    {
        $this->currentSearch = $searchApplication;
        $this->refreshApplications();
    }

    public function approve($applicationId)
    {
        $application = JobApplication::findOrFail($applicationId);
        $this->authorize('approve', $application);

        $application->update(['status' => 'approved']);
        $this->refreshApplications();
    }

    public function reject($applicationId)
    {
        $application = JobApplication::findOrFail($applicationId);
        $this->authorize('reject', $application);

        $application->update(['status' => 'rejected']);
        $this->refreshApplications();
    }

    public function deleteApplication($applicationId)
    {
        $application = JobApplication::findOrFail($applicationId);
        $this->authorize('delete', $application);
        $application->delete();
        $this->refreshApplications();
    }

    protected function refreshApplications()
    {
        $query = JobApplication::query();

        // 🔑 Role-based filtering
        if (Auth::check()) {
            $userRole = Auth::user()->role;

            if ($userRole === 'admin') {
                // Admin → no filter, see all applications

            } elseif ($userRole === 'employer') {
                // Employer → only applications for their jobs
                $query->whereHas('job', function ($q) {
                    $q->where('user_id', Auth::id());
                });

            } else {
                // Guest/Applicant → only their applications
                $query->where('email', Auth::user()->email);
            }
        }

        // 🎯 Status filter
        if (! empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        // 🔍 Search filter
        if (! empty($this->currentSearch)) {
            $query->where(function ($q) {
                $q->where('full_name', 'like', '%'.$this->currentSearch.'%')
                    ->orWhere('email', 'like', '%'.$this->currentSearch.'%')
                    ->orWhere('phone_number', 'like', '%'.$this->currentSearch.'%');
            })
                ->orWhereHas('job', function ($q) {
                    $q->where('title', 'like', '%'.$this->currentSearch.'%');
                });
        }

        return $this->usePagination
            ? $query->latest()->paginate($this->perPage)
            : $query->latest()->get();
    }

    #[On('loadMore')]
    public function loadMore()
    {
        $this->perPage += 5;
    }

    public function render()
    {
        $applications = $this->refreshApplications();

        return view('livewire.application-table', [
            'applications' => $applications,
        ]);
    }
}
