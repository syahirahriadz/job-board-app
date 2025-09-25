<?php

namespace App\Livewire;

use Livewire\Component;

class JobSearch extends Component
{
    public $search = '';
    public $log = [];

    public function mount()
    {
        $this->log[] = "[" . now()->format('H:i:s.u') . "] 🚀 mount() called - search component initialized";
    }

    public function hydrate()
    {
        $this->log[] = "[" . now()->format('H:i:s.u') . "] 🔄 hydrate() called";
    }

    public function updating($property, $value)
    {
        $this->log[] = "[" . now()->format('H:i:s.u') . "] ✏️ updating() called on {$property}, new value: {$value}";
    }

    public function updated($property, $value)
    {
        $this->log[] = "[" . now()->format('H:i:s.u') . "] ✅ updated() called on {$property}, new value: {$value}";

        if ($property === 'search') {
            $this->dispatch('searchUpdated', $value);
        }
    }

    public function dehydrate()
    {
        $this->log[] = "[" . now()->format('H:i:s.u') . "] 💤 dehydrate() called";
    }

    public function render()
    {
        return view('livewire.job-search');
    }
}
