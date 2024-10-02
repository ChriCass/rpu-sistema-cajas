<?php

namespace App\Livewire;

use App\Models\MovimientoDeCaja;
use App\Models\Apertura;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class DeleteAperturaPrincipalModal
extends ModalComponent
{
    public $openModal = false;
    public $numMov;
    public $aperturaId;
    public $familias;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount($aperturaId)
    {

        $this->aperturaId = $aperturaId;
    }

    public function delete()
    {
         $movimientos = MovimientoDeCaja::where('id_apertura',$this->aperturaId)
                        -> get()
                        ->toarray();
        if(count($movimientos) <> 0){
            session() -> flash('error','No se puede eliminar la apertura por que tiene movimientos en caja');
            return $this->redirect(route('movimientos'), navigate: true);
        }

        Apertura::where('id',$this->aperturaId) -> delete();
        

        session() -> flash('message','Apertura eliminada con exito');
        // Redireccionar como SPA
        return $this->redirect(route('movimientos'), navigate: true);
    }


    public function render()
    {
        return view('livewire.delete-apertura-principal-modal');
    }
}
