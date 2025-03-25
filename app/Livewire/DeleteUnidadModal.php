<?php

namespace App\Livewire;

use App\Models\Unidad;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Log;

class DeleteUnidadModal extends ModalComponent
{
    public $unidadId;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount($unidadId)
    {
        $this->unidadId = $unidadId;
    }

    public function delete()
    {
        try {
            $unidad = Unidad::findOrFail($this->unidadId);
            $unidad->delete();
            
            $this->dispatch('unidadDeleted');
            $this->closeModal();
            session()->flash('message', 'Unidad eliminada exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error deleting unidad: ' . $e->getMessage());
            session()->flash('error', 'Error al eliminar la unidad: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.delete-unidad-modal');
    }
} 