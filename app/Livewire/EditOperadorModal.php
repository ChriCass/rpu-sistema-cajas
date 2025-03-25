<?php

namespace App\Livewire;

use App\Models\Operador;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Log;

class EditOperadorModal extends ModalComponent
{
    public $operadorId;
    public $nombre;
    public $estado;

    protected $rules = [
        'nombre' => 'required|min:3',
        'estado' => 'required|boolean'
    ];

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount($operadorId)
    {
        $operador = Operador::findOrFail($operadorId);
        $this->operadorId = $operador->id;
        $this->nombre = $operador->nombre;
        $this->estado = (int)$operador->estado;
        
        // Log para debug
        Log::info('Estado cargado:', ['estado' => $this->estado]);
    }

    public function updatedEstado($value)
    {
        // Log para debug
        Log::info('Estado actualizado:', ['estado' => $value]);
    }

    public function save()
    {
        $this->validate();

        try {
            $operador = Operador::findOrFail($this->operadorId);
            $operador->update([
                'nombre' => $this->nombre,
                'estado' => (int)$this->estado
            ]);
            
            $this->dispatch('operadorUpdated');
            $this->closeModal();
            session()->flash('message', 'Operador actualizado exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error updating operador: ' . $e->getMessage());
            session()->flash('error', 'Error al actualizar el operador: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.edit-operador-modal');
    }
} 