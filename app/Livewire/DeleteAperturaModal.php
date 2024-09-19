<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Documento;
use App\Models\DDetalleDocumento;
use App\Models\Producto;
use App\Models\Detalle;

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
        // Elimina el registro en la tabla 'documentos' basado en el ID del movimiento almacenado en $numMov
        Documento::where('id', $this->numMov)->delete();
    
        // Obtiene el 'id_producto' relacionado a través de la tabla 'd_detalledocumentos' usando el 'id_referencia' (que es el movimiento)
        $id_producto = DDetalleDocumento::select('id_producto')->where('id_referencia', $this->numMov);
    
        // Obtiene el 'id_detalle' desde la tabla 'productos' usando el 'id_producto' obtenido en el paso anterior
        $id_detalle = Producto::select('id_detalle')->where('id', $id_producto);
    
        // Elimina los detalles relacionados en la tabla 'd_detalledocumentos' basados en el 'id_referencia'
        DDetalleDocumento::where('id_referencia', $this->numMov)->delete();
    
        // Elimina el producto relacionado en la tabla 'productos' usando el 'id_producto' obtenido anteriormente
        Producto::where('id', $id_producto)->delete();
    
        // Elimina el detalle relacionado en la tabla 'detalle' usando el 'id_detalle' obtenido en el paso anterior
        Detalle::where('id', $id_detalle)->delete();
    
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
