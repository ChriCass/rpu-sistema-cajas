<?php

namespace App\Livewire;

use Livewire\Component;

class EditVaucherDePagoVentas extends Component
{
    public $aperturaId;
    public $numMov;



    public function mount($numeroMovimiento, $aperturaId)
    {   
        $this->aperturaId = $aperturaId;
        $this->numMov = $numeroMovimiento;

    }
    public function render()
    {
        return view('livewire.edit-vaucher-de-pago-ventas');
    }
}
