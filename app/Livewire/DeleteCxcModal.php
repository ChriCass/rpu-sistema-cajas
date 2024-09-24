<?php

namespace App\Livewire;

use Livewire\Component;

class DeleteCxcModal extends Component
{   public $openModal = false;
    public $idcxc;

    public function mount($idcxc)
    {
        $this->idcxc = $idcxc;
    }


    public function deleteCXC()
    {

        /// implementar logica para eliminar CXP


        ///
        // Mensaje de éxito
        session()->flash('message', 'Movimiento eliminado exitosamente.');
    
        // Redireccionar a la ruta 'cxp'
        return $this->redirect(route('cxp'), navigate: true);
    }
    public function render()
    {
        return view('livewire.delete-cxc-modal');
    }
}
