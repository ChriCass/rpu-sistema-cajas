<?php

namespace App\Livewire;

use App\Models\DDetalleDocumento;
use App\Models\Detalle;
use App\Models\Documento;
use App\Models\Producto;
use App\Models\SubFamilia;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class DeleteDetalleModal extends ModalComponent
{
   
    public $openModal = false;
    public $detalleId; // Cambiado de subFamiliaId a detalleId
    public $hasMovimientos = false;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount($detalleId) // Cambiado de subfamiliaId a detalleId
    {
        $this->detalleId = $detalleId;
        $this->hasMovimientos();
    }

    public function hasMovimientos()
    {
        // Verifica si el detalle está asociado a algún producto
        $producto = Producto::where('id_detalle', $this->detalleId)->first();
        if (!$producto) {
            $this->hasMovimientos = false;
            return; // Si no se encuentra el producto, no hay movimientos
        }

        // Verifica si el DetalleDocumento tiene el id del producto
        $detalleDocumento = DDetalleDocumento::where('id_producto', $producto->id)->first();
        if (!$detalleDocumento) {
            $this->hasMovimientos = false;
            return; // Si no se encuentra el detalleDocumento, no hay movimientos
        }

        // Verifica si el Documento tiene el id del DetalleDocumento
        $documento = Documento::where('id', $detalleDocumento->id_referencia)->first();
        if (!$documento) {
            $this->hasMovimientos = false;
            return; // Si no se encuentra el documento, no hay movimientos
        }

        // Si todas las verificaciones pasaron, cambia hasMovimientos a true
        $this->hasMovimientos = true;
    }

    public function deleteDetalle() // Cambiado de deleteSubFamilia a deleteDetalle
    {
        // Verifica si hay movimientos antes de eliminar
        if ($this->hasMovimientos) {
            session()->flash('error', 'No se puede eliminar el detalle porque tiene movimientos asociados.');
            return;
        }

        // Si no hay movimientos, elimina el detalle
        $detalle = Detalle::find($this->detalleId); // Cambiado de SubFamilia a Detalle
        if ($detalle) {
            $detalle->delete();
            session()->flash('message', 'Detalle eliminado con éxito.');

            // Redirige a la ruta 'detalles'
            return redirect()->route('detalles');
        } else {
            session()->flash('error', 'Detalle no encontrado.');
            return redirect()->route('detalles');
        }
    }
    public function render()
    {
        return view('livewire.delete-detalle-modal');
    }
}
