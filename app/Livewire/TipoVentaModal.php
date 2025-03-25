<?php

namespace App\Livewire;

use App\Models\TipoVenta;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Log;

class TipoVentaModal extends ModalComponent
{
    public $descripcion;
    public $estado = 1;

    protected $rules = [
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
            TipoVenta::create([
                'descripcion' => $this->descripcion,
                'estado' => $this->estado
            ]);
            
            $this->dispatch('tipoVentaCreated');
            $this->closeModal();
            session()->flash('message', 'Tipo de venta creado exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error creating tipo venta: ' . $e->getMessage());
            session()->flash('error', 'Error al crear el tipo de venta: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.tipo-venta-modal');
    }
} 