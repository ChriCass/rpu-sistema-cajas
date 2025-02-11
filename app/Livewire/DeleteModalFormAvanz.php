<?php

namespace App\Livewire;

use App\Models\DDetalleDocumento;
use App\Models\Documento;
use App\Models\MovimientoDeCaja;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DeleteModalFormAvanz extends Component
{
    public $openModal;
    public $IdDocumento;
    public $origen; // Declarar la propiedad pública

    public function mount($origen, $IdDocumento)
    {
        $this->origen = $origen; // Inicializar el parámetro recibido
        $this->IdDocumento = $IdDocumento;
    }

    public function delete()
    {
     
     // Comprobación si el documento tiene movimientos en los libros 3 o 4
     $comprobacion = MovimientoDeCaja::whereIn('id_libro', ['3', '4'])
     ->where('id_documentos', $this->IdDocumento)
     ->get()
     ->toArray();

     

    Log::info('Cantidad de movimientos encontrados: ' . count($comprobacion));

    if (count($comprobacion) > 0) {
        session()->flash('error', 'No se puede eliminar el documento de caja porque tiene movimientos de caja.');
        return $this->redirect(route(substr($this->origen,-3)), navigate: true);
    }

    // Usar una transacción para asegurar atomicidad en la eliminación de los registros
    DB::beginTransaction();

    try {
        // Eliminar los movimientos de caja asociados
        MovimientoDeCaja::where('id_documentos', $this->IdDocumento)->delete();

        // Eliminar los detalles del documento asociados
        DDetalleDocumento::where('id_referencia', $this->IdDocumento)->delete();

        // Eliminar el documento en sí
        Documento::where('id', $this->IdDocumento)->delete();

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
    return $this->redirect(route(substr($this->origen,-3)), navigate: true);   

        
    }
    public function render()
    {
        return view('livewire.delete-modal-form-avanz');
    }
}
