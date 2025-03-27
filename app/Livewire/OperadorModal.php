<?php

namespace App\Livewire;

use App\Models\Operador;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Log;
use App\Traits\WithNotifications;

class OperadorModal extends ModalComponent
{
    use WithNotifications;

    public $nombre;
    public $estado = 1;

    protected $rules = [
        'nombre' => 'required|min:3',
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
            Operador::create([
                'nombre' => strtoupper($this->nombre),
                'estado' => $this->estado
            ]);
            
            $this->dispatch('operadorCreated');
            $this->closeModal();
            $this->notify('success', 'Operador creado exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error creating operador: ' . $e->getMessage());
            $this->notify('error', 'Error al crear el operador: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.operador-modal');
    }
} 