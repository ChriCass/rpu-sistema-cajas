<?php

namespace App\Livewire;

use Livewire\Component;

class RegistroDeIngresoAvanz extends Component
{   
    public $aperturaId;

    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;
    }
    public function render()
    {
        return view('livewire.registro-de-ingreso-avanz')->layout('layouts.app');
    }
}
