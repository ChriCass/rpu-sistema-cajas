<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Documento;
use App\Models\DDetalleDocumento;
use App\Models\Producto;
use App\Models\Detalle;
use App\Models\MovimientoDeCaja;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DeleteAperturaModal extends Component
{
    public $openModal = false;
    public $numMov;
    public $aperturaId;
    public $familias;

    public function mount($numMov, $aperturaId, $familias)
    {
        Log::info("Montando el componente con numMov: {$numMov} y aperturaId: {$aperturaId}");
        $this->numMov = $numMov;
        $this->aperturaId = $aperturaId;
        $this->familias = $familias;
    }

    public function deleteMovimiento()
    {
        DB::beginTransaction(); // Iniciar la transacción
        try {
            // Eliminar el movimiento y los documentos asociados
            if ($this->familias !== 'MOVIMIENTOS') {
                Log::info("Eliminando documentos y movimientos relacionados con numMov: {$this->numMov}");
                
                MovimientoDeCaja::where('id_documentos', $this->numMov)->delete();
                DDetalleDocumento::where('id_referencia', $this->numMov)->delete();
                Documento::where('id', $this->numMov)->delete();
            } else {
                Log::info("Eliminando solo movimientos de caja con mov: {$this->numMov}");
                
                MovimientoDeCaja::where('mov', $this->numMov)
                                ->where('id_apertura', $this->aperturaId)
                                ->delete();
            }

            DB::commit(); // Confirmar la transacción
            session()->flash('message', 'Movimiento eliminado exitosamente.');
            Log::info("Movimiento eliminado exitosamente: numMov={$this->numMov}");
            
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error
            Log::error("Error al eliminar el movimiento: {$e->getMessage()}");
            session()->flash('error', 'Ocurrió un error al eliminar el movimiento.');
        }

        // Emitir el evento para actualizar la tabla
        $this->dispatch('actualizar-tabla-apertura', $this->aperturaId);

        // Redireccionar como SPA
        return $this->redirect(route('apertura.edit', ['aperturaId' => $this->aperturaId]), navigate: true);
    }

    

    // Cerrar el modal
    public function closeModal()
    {
        $this->openModal = false;
        $this->reset(['openModal', 'numMov', 'aperturaId']);
        Log::info("Modal cerrado y propiedades reseteadas. Valores actuales: numMov={$this->numMov}, aperturaId={$this->aperturaId}");
    }
    


    public function render()
    {
        return view('livewire.delete-apertura-modal');
    }
}
