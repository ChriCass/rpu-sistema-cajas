<?php

namespace App\Livewire;

use App\Models\DDetalleDocumento;
use App\Models\Detalle;
use App\Models\Documento;
use App\Models\Familia;
use App\Models\Producto;
use App\Models\SubFamilia;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class DeleteSubFamiliaModal extends ModalComponent
{
    public $openModal = false;
    public $subFamiliaId;
    public $hasMovimientos = false;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount($subfamiliaId)
    {
        $this->subFamiliaId = $subfamiliaId;
        $this->hasMovimientos();
    }

    public function hasMovimientos()
    {
        // Verifica si la subfamilia está asociada a algún detalle
        $detalle = Detalle::where('id_subfamilia', $this->subFamiliaId)->first();
        if (!$detalle) {
            $this->hasMovimientos = false;
            return; // Si no se encuentra el detalle, no hay movimientos
        }

        // Verifica si el Producto tiene el id de detalle
        $producto = Producto::where('id_detalle', $detalle->id)->first();
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

    public function deleteSubFamilia()
    {
        // Verifica si hay movimientos antes de eliminar
        if ($this->hasMovimientos) {
            session()->flash('error', 'No se puede eliminar la subfamilia porque tiene movimientos asociados.');
            return;
        }

        // Si no hay movimientos, elimina la subfamilia
        $subFamilia = SubFamilia::find($this->subFamiliaId);
        if ($subFamilia) {
            $subFamilia->delete();
            session()->flash('message', 'Subfamilia eliminada con éxito.');

            // Redirige a la ruta 'subfamilias'
            return redirect()->route('subfamilias');
        } else {
            session()->flash('error', 'Subfamilia no encontrada.');
            return redirect()->route('subfamilias');
        }
    }
    public function render()
    {
        return view('livewire.delete-sub-familia-modal');
    }
}
