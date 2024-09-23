<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Documento;
use App\Models\DDetalleDocumento;
use App\Models\Producto;
use App\Models\Detalle;
use App\Models\MovimientoDeCaja;

class DeleteAperturaModal extends Component
{
    public $openModal = false;
    public $numMov;
    public $aperturaId;
    public function mount($numMov, $aperturaId)
    {
        $this->numMov = $numMov;
        $this->aperturaId = $aperturaId;
    }
    public function deleteMovimiento()
    {
        MovimientoDeCaja::where('id_documentos',$this->numMov)->delete();
        DDetalleDocumento::where('id_referencia',$this->numMov)->delete();
        Documento::where('id',$this->numMov)->delete();
        // Muestra un mensaje de éxito después de eliminar el movimiento
        session()->flash('message', 'Movimiento eliminado exitosamente.');
    
        // Emite un evento 'actualizar-tabla-apertura' para refrescar la tabla en la interfaz
        $this->dispatch('actualizar-tabla-apertura', $this->aperturaId);
    
        // Cierra el modal de eliminación
        $this->closeModal();
    }
    
    // Cerrar el modal
    public function closeModal()
    {
        $this->openModal = false;
    }


    public function render()
    {
        return view('livewire.delete-apertura-modal');
    }
}
