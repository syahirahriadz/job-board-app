<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class UserCreate extends Component
{
    public bool $showModal = false;

    #[Validate('required|min:3', message: [
        'required' => 'Please enter a name.',
        'min' => 'Name must be at least 3 characters.',
    ])]
    public string $name = '';

    #[Validate('required|email|unique:users', message: [
        'required' => 'Please enter an email address.',
        'email' => 'Please enter a valid email address.',
        'unique' => 'This email is already registered.',
    ])]
    public string $email = '';

    #[Validate('required|min:8', message: [
        'required' => 'Please enter a password.',
        'min' => 'Password must be at least 8 characters.',
    ])]
    public string $password = '';

    #[Validate('required|in:guest,admin,employer', message: [
        'required' => 'Please select a role.',
        'in' => 'Please select a valid role.',
    ])]
    public string $role = 'guest';

    #[On('showCreateModal')]
    public function openModal()
    {
        $this->showModal = true;
    }

    public function create()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        $this->dispatch('userCreated', $user->id);
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'email', 'password', 'role']);
    }

    public function render()
    {
        return view('livewire.user-create');
    }
}
