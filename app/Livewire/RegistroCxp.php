<?php

namespace App\Livewire;

use Livewire\Component;

class RegistroCxp extends Component
{   public $aperturaId;

    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;
    }
    public function render()
    {
        return view('livewire.registro-cxp')->layout('layouts.app');
    }
}
