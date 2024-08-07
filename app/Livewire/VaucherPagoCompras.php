<?php

namespace App\Livewire;

use Livewire\Component;

class VaucherPagoCompras extends Component
{    public $aperturaId;

    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;
    }
    public function render()
    {
        return view('livewire.vaucher-pago-compras')->layout('layouts.app');
    }
}
