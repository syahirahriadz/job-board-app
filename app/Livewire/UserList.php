<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;

class UserList extends Component
{
    use AuthorizesRequests;
    public $currentSearch = '';
    public $perPage = 5;
    public bool $usePagination = true;

     #[On('userUpdated')]
    public function handleUserUpdated()
    {
        $this->refreshUsers();
    }

    // public function viewUser($userId)
    // {
    //     //event
    //     $this->dispatch('userViewed', $userId);
    // }

    public function editUser($userId)
    {
        $user = User::findOrFail($userId);
        $this->authorize('update', $user);
        $this->dispatch('editUser', $userId);
    }

    public function mount()
    {
        $this->authorize('viewAny', User::class);
        $this->usePagination = ! request()->routeIs('users.index');
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        $this->authorize('delete', $user);
        $user->delete();
        $this->refreshUsers();
    }

    #[On('userSearchUpdated')]
    public function handleSearchUpdated($searchUser)
    {
        $this->currentSearch = $searchUser;
        $this->refreshUsers();
    }

    protected function refreshUsers()
    {
        try {
            $this->authorize('viewAny', User::class);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return collect();
        }

        $query = User::query();

        if (!empty($this->currentSearch)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->currentSearch . '%')
                ->orWhere('email', 'like', '%' . $this->currentSearch . '%')
                ->orWhere('role', 'like', '%' . $this->currentSearch . '%');
            });
        }

        return $this->usePagination
            ? $query->latest()->paginate($this->perPage)
            : $query->latest()->get();
    }

    #[On('loadMore')]
    public function loadMore()
    {
        $this->perPage +=5;
    }

    public function render()
    {
        $users = $this->refreshUsers();
        return view('livewire.user-list', [
            'users' => $users,
        ]);
    }
}
