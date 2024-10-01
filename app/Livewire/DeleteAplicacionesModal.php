<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MovimientoDeCaja;

class DeleteAplicacionesModal extends Component
{   public $openModal;
    public $detalles;
    public function mount($detalles)
    {
        $this->detalles = $detalles;
           
    }

    public function deleteAplication(){
        MovimientoDeCaja::where('id_libro',4)
                        ->where('mov',$this->detalles)
                        ->delete();
        session()->flash('message', 'Movimiento eliminado exitosamente.');
    }
    public function render()
    {
        return view('livewire.delete-aplicaciones-modal');
    }
}
