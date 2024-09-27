<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MovimientoDeCaja;
use App\Models\DDetalleDocumento;
use App\Models\Documento;
use Illuminate\Support\Facades\Log;


class DeleteCxcModal extends Component
{   public $openModal = false;
    public $idcxc;

    public function mount($idcxc)
    {
        $this->idcxc = $idcxc;
    }


    public function deleteCXC()
    {

        $comprobacion = MovimientoDeCaja::whereIn('id_libro', ['3', '4'])
                        ->where('id_documentos',$this->idcxc)
                        ->get()
                        ->toarray();
        Log::info(count($comprobacion));
        if(count($comprobacion) <> 0){
            session()->flash('error', 'No se puede eliminar el documento de caja por que tiene movimientos de caja.');    
            return $this->redirect(route('cxc'), navigate: true);    
        }

        MovimientoDeCaja::where('id_documentos',$this->idcxc)->delete();
        DDetalleDocumento::where('id_referencia',$this->idcxc)->delete();
        Documento::where('id',$this->idcxc)->delete();


        ///
        // Mensaje de éxito
        session()->flash('message', 'Movimiento eliminado exitosamente.');
    
        // Redireccionar a la ruta 'cxp'
        return $this->redirect(route('cxc'), navigate: true);
    }
    public function render()
    {
        return view('livewire.delete-cxc-modal');
    }
}
