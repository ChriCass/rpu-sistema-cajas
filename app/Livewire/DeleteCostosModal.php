<?php

namespace App\Livewire;

use App\Models\CentroDeCostos;
use App\Models\DDetalleDocumento;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Log;

class DeleteCostosModal extends ModalComponent
{
    public $centroDeCostosId; // ID del centro de costos
    public $hasMovimientos = false; // Indica si el centro de costos tiene movimientos

    public static function modalMaxWidth(): string
    {
        return 'lg'; // Tamaño del modal
    }

    public function mount($costoId)
    {
        Log::info("Mounting DeleteCostosModal with centroDeCostosId: {$costoId}");

        $this->centroDeCostosId = $costoId;

        // Verificar si el centro de costos tiene movimientos
        $this->hasMovimientos();
    }

    public function hasMovimientos()
    {
        // Verifica si el centro de costos está siendo utilizado en DDetalleDocumento
        $this->hasMovimientos = DDetalleDocumento::where('id_centroDeCostos', $this->centroDeCostosId)->exists();
    }

    public function deleteCentroDeCostos()
    {
        // Verifica si hay movimientos antes de eliminar
        if ($this->hasMovimientos) {
            session()->flash('error', 'No se puede eliminar el centro de costos porque tiene movimientos asociados.');
            return;
        }

        // Si no hay movimientos, elimina el centro de costos
        $centroDeCostos = CentroDeCostos::find($this->centroDeCostosId);
        if ($centroDeCostos) {
            $centroDeCostos->delete();
            session()->flash('message', 'Centro de costos eliminado con éxito.');

            // Redirige a la ruta 'centros-de-costos'
            return redirect()->route('centros-de-costos');
        } else {
            session()->flash('error', 'Centro de costos no encontrado.');
            return redirect()->route('centros-de-costos');
        }
    }

    public function render()
    {
        return view('livewire.delete-costos-modal');
    }
}