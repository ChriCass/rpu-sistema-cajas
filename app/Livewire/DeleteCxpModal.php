<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MovimientoDeCaja;
use App\Models\DDetalleDocumento;
use App\Models\Documento;
use Illuminate\Support\Facades\Log;

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

        $comprobacion = MovimientoDeCaja::whereIn('id_libro', ['3', '4'])
                        ->where('id_documentos',$this->idcxp)
                        ->get()
                        ->toarray();
        Log::info(count($comprobacion));
        if(count($comprobacion) <> 0){
            session()->flash('error', 'No se puede eliminar el documento de caja por que tiene movimientos de caja.');    
            return $this->redirect(route('cxp'), navigate: true);    
        }

        MovimientoDeCaja::where('id_documentos',$this->idcxp)->delete();
        DDetalleDocumento::where('id_referencia',$this->idcxp)->delete();
        Documento::where('id',$this->idcxp)->delete();


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
