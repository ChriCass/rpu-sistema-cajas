<?php

namespace App\Livewire;

use Livewire\Component;

class FormRegistroCxp extends Component
{
    public $aperturaId;

    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;
    }
    public function render()
    {
        return view('livewire.form-registro-cxp')->layout('layouts.app');
    }
}
