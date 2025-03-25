<?php

namespace App\Livewire;

use App\Models\Unidad;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Log;

class EditUnidadModal extends ModalComponent
{
    public $unidadId;
    public $numero;
    public $descripcion;
    public $estado;

    protected $rules = [
        'numero' => 'required|min:1',
        'descripcion' => 'required|min:3',
        'estado' => 'required|boolean'
    ];

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount($unidadId)
    {
        $unidad = Unidad::findOrFail($unidadId);
        $this->unidadId = $unidad->id;
        $this->numero = $unidad->numero;
        $this->descripcion = $unidad->descripcion;
        $this->estado = (int)$unidad->estado;
    }

    public function save()
    {
        $this->validate();

        try {
            $unidad = Unidad::findOrFail($this->unidadId);
            $unidad->update([
                'numero' => $this->numero,
                'descripcion' => $this->descripcion,
                'estado' => (int)$this->estado
            ]);
            
            $this->dispatch('unidadUpdated');
            $this->closeModal();
            session()->flash('message', 'Unidad actualizada exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error updating unidad: ' . $e->getMessage());
            session()->flash('error', 'Error al actualizar la unidad: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.edit-unidad-modal');
    }
} 