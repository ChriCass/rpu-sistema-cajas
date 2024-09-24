<?php

namespace App\Livewire;

use Livewire\Component;

class DeleteCxpModal extends Component
{
    public $openModal = false;
    public $idcxp;

    public function mount($idcxp)
    {
        $this->idcxp = $idcxp;
    }


    public function deleteCXP()
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
        return view('livewire.delete-cxp-modal');
    }
}
