<?php

namespace App\Livewire;

use Livewire\Component;

class RegistroDocumentosIngreso extends Component
{
    public $aperturaId;

    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;
    }
    public function render()
    {
        return view('livewire.registro-documentos-ingreso')->layout('layouts.app');
    }
}
