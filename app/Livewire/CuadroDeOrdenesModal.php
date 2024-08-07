<?php

namespace App\Livewire;

use Livewire\Component;

class CuadroDeOrdenesModal extends Component
{    public $openModal = false;
    public function render()
    {
        return view('livewire.cuadro-de-ordenes-modal');
    }
}
