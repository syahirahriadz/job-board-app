<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class UserEdit extends Component
{
    public $userId;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|max:255')]
    public $email;

    #[Validate('required|string|max:255')]
    public $role;

    public $showModal = false;

    #[On('editUser')]
    public function openEditModal($userId)
    {
        $this->userId = $userId;
        $user = User::find($userId);
        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['userId', 'name', 'email', 'role']);
    }

    public function update()
    {
        $this->validate();

        $user = User::find($this->userId);
        if ($user) {
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
            ]);
        }

        $this->dispatch('userUpdated'); //'userUpdated' can be any name but related
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.user-edit');
    }
}
