<?php

namespace App\Livewire;

use Livewire\Component;

class RegistroDocumentosEgreso extends Component
{
    public $aperturaId;

    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;
    }
    public function render()
    {
        return view('livewire.registro-documentos-egreso')->layout('layouts.app');
    }
}
