<?php

namespace App\Livewire;

use App\Models\TipoVenta;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Log;

class DeleteTipoVentaModal extends ModalComponent
{
    public $tipoVentaId;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount($tipoVentaId)
    {
        $this->tipoVentaId = $tipoVentaId;
    }

    public function delete()
    {
        try {
            $tipoVenta = TipoVenta::findOrFail($this->tipoVentaId);
            $tipoVenta->delete();
            
            $this->dispatch('tipoVentaDeleted');
            $this->closeModal();
            session()->flash('message', 'Tipo de venta eliminado exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error deleting tipo venta: ' . $e->getMessage());
            session()->flash('error', 'Error al eliminar el tipo de venta: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.delete-tipo-venta-modal');
    }
} 