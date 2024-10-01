<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MovimientoDeCaja;
use App\Models\DDetalleDocumento;
use App\Models\Documento;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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
        // Comprobación si el documento tiene movimientos en los libros 3 o 4
        $comprobacion = MovimientoDeCaja::whereIn('id_libro', ['3', '4'])
            ->where('id_documentos', $this->idcxp)
            ->get()
            ->toArray();
    
        Log::info('Cantidad de movimientos encontrados: ' . count($comprobacion));
    
        if (count($comprobacion) > 0) {
            session()->flash('error', 'No se puede eliminar el documento de caja porque tiene movimientos de caja.');
            return $this->redirect(route('cxp'), navigate: true);
        }
    
        // Usar una transacción para asegurar que todas las eliminaciones sean consistentes
        DB::beginTransaction();
    
        try {
            // Eliminar los movimientos de caja asociados
            MovimientoDeCaja::where('id_documentos', $this->idcxp)->delete();
    
            // Eliminar los detalles del documento asociados
            DDetalleDocumento::where('id_referencia', $this->idcxp)->delete();
    
            // Eliminar el documento en sí
            Documento::where('id', $this->idcxp)->delete();
    
            // Confirmar la transacción si todo fue exitoso
            DB::commit();
    
            // Mensaje de éxito
            session()->flash('message', 'Movimiento eliminado exitosamente.');
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            Log::error('Error eliminando el documento de CXP: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al intentar eliminar el documento. Intente de nuevo.');
        }
    
        // Redireccionar a la ruta 'cxp'
        return $this->redirect(route('cxp'), navigate: true);
    }
    
    
    public function render()
    {
        return view('livewire.delete-cxp-modal');
    }
}
