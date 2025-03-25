<?php

namespace App\Livewire;

use App\Models\Unidad;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Log;

class UnidadModal extends ModalComponent
{
    public $numero;
    public $descripcion;
    public $estado = 1;

    protected $rules = [
        'numero' => 'required|min:1',
        'descripcion' => 'required|min:3',
        'estado' => 'required|boolean'
    ];

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function save()
    {
        $this->validate();

        try {
            Unidad::create([
                'numero' => $this->numero,
                'descripcion' => $this->descripcion,
                'estado' => $this->estado
            ]);
            
            $this->dispatch('unidadCreated');
            $this->closeModal();
            session()->flash('message', 'Unidad creada exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error creating unidad: ' . $e->getMessage());
            session()->flash('error', 'Error al crear la unidad: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.unidad-modal');
    }
} 