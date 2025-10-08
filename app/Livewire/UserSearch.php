<?php

namespace App\Livewire;

use Livewire\Component;

class UserSearch extends Component
{
    public $searchUser = '';

    public function updated($property, $value)
    {
        if ($property === 'searchUser') {
            $this->dispatch('userSearchUpdated', $value);
        }
    }

    public function render()
    {
        return view('livewire.user-search');
    }
}
