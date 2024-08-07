<?php

namespace App\Livewire;

use Livewire\Component;

class CuadroAplicaciones extends Component
{    public $aperturaId;

    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;
    }
    public function render()
    {
        return view('livewire.cuadro-aplicaciones')->layout('layouts.app');
    }
}
