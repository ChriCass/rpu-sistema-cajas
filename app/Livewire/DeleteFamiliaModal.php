<?php

namespace App\Livewire;

use App\Models\Familia;
use App\Models\MovimientoDeCaja;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use App\Models\SubFamilia;
use App\Models\Detalle;
use App\Models\Producto;
use App\Models\Documento;
 use App\Models\DDetalleDocumento;
class DeleteFamiliaModal extends ModalComponent
{
    
    public $openModal = false;
    public $numMov;
    public $familiaId;
    public $hasMovimientos = false;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    
    public function mount($familiaId)
    {
        $this->hasMovimientos();
        $this->familiaId = $familiaId;
    }

    public function hasMovimientos()
    {
        // Verifica si la familia tiene una subfamilia asociada
        $subFamilia = SubFamilia::where('id_familias', $this->familiaId)->first();
        if (!$subFamilia) {
            $this->hasMovimientos = false;
            return; // Si no tiene subfamilia, no tiene movimientos
        }
    
        // Verifica que tanto la familia como la subfamilia estén presentes en el modelo Detalle
        $detalle = Detalle::where('id_familias', $this->familiaId)
                          ->where('id_subfamilia', $subFamilia->id)
                          ->first();
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
    

    public function deleteFamilia()
    {
        // Verifica si hay movimientos antes de eliminar
        if ($this->hasMovimientos) {
            session()->flash('error', 'No se puede eliminar la familia porque tiene movimientos asociados.');
            return;
        }
        
        // Si no hay movimientos, elimina la familia
        $familia = Familia::find($this->familiaId);
        if ($familia) {
            $familia->delete();
            session()->flash('message', 'Familia eliminada con éxito.');
    
            // Redirige a la ruta 'familias'
            return redirect()->route('familias');
        } else {
            session()->flash('error', 'Familia no encontrada.');
            return redirect()->route('familias');
        }
    }
    
    public function render()
    {
        return view('livewire.delete-familia-modal');
    }
}
