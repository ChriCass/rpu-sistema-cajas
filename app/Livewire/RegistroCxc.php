<?php

namespace App\Livewire;

use Livewire\Component;

class RegistroCxc extends Component
{  public $aperturaId;

    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;
    }
    public function render()
    {
        return view('livewire.registro-cxc')->layout('layouts.app');
    }
}
