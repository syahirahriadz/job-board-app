<?php

namespace App\Livewire;

use Livewire\Component;

class ApplicationSearch extends Component
{
    public $searchApplication = '';

    public function updated($property, $value)
    {
        if ($property === 'searchApplication') {
            $this->dispatch('applicationSearchUpdated', $value);
        }
    }

    public function render()
    {
        return view('livewire.application-search');
    }
}
