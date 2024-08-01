<?php

namespace App\Livewire;

use Livewire\Component;

class AplicacionModal extends Component
{   
    public $openModal = false;
    public function render()
    {
        return view('livewire.aplicacion-modal');
    }
}
