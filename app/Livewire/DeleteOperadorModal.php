<?php

namespace App\Livewire;

use App\Models\Operador;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Log;
use App\Traits\WithNotifications;

class DeleteOperadorModal extends ModalComponent
{
    use WithNotifications;

    public $operadorId;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount($operadorId)
    {
        $this->operadorId = $operadorId;
    }

    public function delete()
    {
        try {
            $operador = Operador::findOrFail($this->operadorId);
            $operador->delete();
            
            $this->dispatch('operadorDeleted');
            $this->closeModal();
            $this->notify('success', 'Operador eliminado exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error deleting operador: ' . $e->getMessage());
            $this->notify('error', 'Error al eliminar el operador: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.delete-operador-modal');
    }
} 