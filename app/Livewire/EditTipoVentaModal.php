<?php

namespace App\Livewire;

use App\Models\TipoVenta;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Log;
use App\Traits\WithNotifications;

class EditTipoVentaModal extends ModalComponent
{
    use WithNotifications;

    public $tipoVentaId;
    public $descripcion;
    public $estado;

    protected $rules = [
        'descripcion' => 'required|min:3',
        'estado' => 'required|boolean'
    ];

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount($tipoVentaId)
    {
        $tipoVenta = TipoVenta::findOrFail($tipoVentaId);
        $this->tipoVentaId = $tipoVenta->id;
        $this->descripcion = $tipoVenta->descripcion;
        $this->estado = (int)$tipoVenta->estado;
    }

    public function save()
    {
        $this->validate();

        try {
            $tipoVenta = TipoVenta::findOrFail($this->tipoVentaId);
            $tipoVenta->update([
                'descripcion' => strtoupper($this->descripcion),
                'estado' => (int)$this->estado
            ]);
            
            $this->dispatch('tipoVentaUpdated');
            $this->closeModal();
            $this->notify('success', 'Tipo de venta actualizado exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error updating tipo venta: ' . $e->getMessage());
            $this->notify('error', 'Error al actualizar el tipo de venta: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.edit-tipo-venta-modal');
    }
} 