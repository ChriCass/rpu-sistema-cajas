<?php

namespace App\Livewire;

use Livewire\Component;

class AperturaEditParent extends Component
{    public $aperturaId;

    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;
    }
    public function render()
    {
        return view('livewire.apertura-edit-parent')->layout('layouts.app');
    }
}
