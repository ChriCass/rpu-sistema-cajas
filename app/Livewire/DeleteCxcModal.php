<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MovimientoDeCaja;
use App\Models\DDetalleDocumento;
use App\Models\Documento;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DeleteCxcModal extends Component
{   public $openModal = false;
    public $idcxc;

    public function mount($idcxc)
    {
        $this->idcxc = $idcxc;
    }


    public function deleteCXC()
    {
        // Comprobación si el documento tiene movimientos en los libros 3 o 4
        $comprobacion = MovimientoDeCaja::whereIn('id_libro', ['3', '4'])
            ->where('id_documentos', $this->idcxc)
            ->get()
            ->toArray();

        Log::info('Cantidad de movimientos encontrados: ' . count($comprobacion));

        if (count($comprobacion) > 0) {
            session()->flash('error', 'No se puede eliminar el documento de caja porque tiene movimientos de caja.');
            return $this->redirect(route('cxc'), navigate: true);
        }

        // Usar una transacción para asegurar atomicidad en la eliminación de los registros
        DB::beginTransaction();

        try {
            // Eliminar los movimientos de caja asociados
            MovimientoDeCaja::where('id_documentos', $this->idcxc)->delete();

            // Eliminar los detalles del documento asociados
            DDetalleDocumento::where('id_referencia', $this->idcxc)->delete();

            // Eliminar el documento en sí
            Documento::where('id', $this->idcxc)->delete();

            // Confirmar la transacción si todo fue exitoso
            DB::commit();

            // Mensaje de éxito
            session()->flash('message', 'Movimiento eliminado exitosamente.');
        } catch (\Exception $e) {
            // Revertir los cambios si hubo un error
            DB::rollBack();
            Log::error('Error eliminando el documento de CXC: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al intentar eliminar el documento. Intente de nuevo.');
        }

        // Redireccionar a la ruta 'cxc'
        return $this->redirect(route('cxc'), navigate: true);
    }
    public function render()
    {
        return view('livewire.delete-cxc-modal');
    }
}
