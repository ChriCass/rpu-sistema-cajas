<?php

namespace App\Livewire;

use Livewire\Component;

class DeleteAplicacionesModal extends Component
{   public $openModal;
    public $detalles;
    public function mount($detalles)
    {
        $this->detalles = $detalles;
        
    }
    public function render()
    {
        return view('livewire.delete-aplicaciones-modal');
    }
}
