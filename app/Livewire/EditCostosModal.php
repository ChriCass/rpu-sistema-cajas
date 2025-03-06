<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CentroDeCostos;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Log;

class EditCostosModal extends ModalComponent
{
    public $centroDeCostosId; // ID del centro de costos
    public $descripcion; // Descripción del centro de costos
    public $abrev; // Abreviatura del centro de costos

    public static function modalMaxWidth(): string
    {
        return 'lg'; // Tamaño del modal
    }

    public function mount(int $costoId)
    {
        Log::info("Mounting EditCostosModal with centroDeCostosId: {$costoId}");

        $this->centroDeCostosId = $costoId;

        // Obtener el centro de costos por su ID
        $centroDeCostos = CentroDeCostos::findOrFail($costoId);
        $this->descripcion = $centroDeCostos->descripcion;
        $this->abrev = $centroDeCostos->abrev; // Cargar la abreviatura
    }

    public function save()
    {
        Log::info("Attempting to save centro de costos with id: {$this->centroDeCostosId}");

        // Validar los datos
        $this->validate([
            'descripcion' => 'required|string|max:255',
            'abrev' => 'required|string|max:10', // Validar la abreviatura
        ]);

        // Actualizar el centro de costos
        $centroDeCostos = CentroDeCostos::findOrFail($this->centroDeCostosId);
        $centroDeCostos->descripcion = $this->descripcion;
        $centroDeCostos->abrev = $this->abrev; // Actualizar la abreviatura
        $centroDeCostos->save();

        Log::info("Successfully saved centro de costos: ", $centroDeCostos->toArray());

        // Mostrar mensaje de éxito y cerrar el modal
        session()->flash('message', 'Centro de costos actualizado exitosamente.');
        $this->dispatch('centroDeCostosUpdated');
    }

    public function render()
    {
        return view('livewire.edit-costos-modal');
    }
}