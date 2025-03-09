<?php

namespace App\Livewire;

use App\Models\CentroDeCostos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CostosModal extends Component
{
    public $openModal = false;
    public $descripcion;
    public $abrev; // Nuevo campo para la abreviatura

    protected $rules = [
        'descripcion' => 'required|string|max:255',
        'abrev' => 'required|string|max:10', // Validar la abreviatura
    ];

    protected $messages = [
        'descripcion.required' => 'El campo descripción es obligatorio.',
        'descripcion.max' => 'La descripción no puede tener más de 255 caracteres.',
        'abrev.required' => 'El campo abreviatura es obligatorio.',
        'abrev.max' => 'La abreviatura no puede tener más de 10 caracteres.',
    ];

    public function insertNewCosto()
    {
        // Validar los datos del formulario
        $this->validate();

        Log::info("Iniciando inserción de nuevo centro de costos...");
        Log::info("Datos recibidos:", [
            'descripcion' => $this->descripcion,
            'abrev' => $this->abrev,
        ]);

        DB::transaction(function () {
            // Verificar si el centro de costos ya existe con la misma descripción o abreviatura
            Log::info("Verificando si el centro de costos ya existe...");
            $existingCosto = CentroDeCostos::where('descripcion', $this->descripcion)
                ->orWhere('abrev', $this->abrev)
                ->first();

            if ($existingCosto) {
                Log::warning("Centro de costos duplicado encontrado:", $existingCosto->toArray());
                session()->flash('error', 'Este centro de costos ya está registrado con la misma descripción o abreviatura.');
                throw new \Exception('Centro de costos duplicado.');
            }

            // Bloquear fila para evitar lecturas concurrentes
            Log::info("Obteniendo nuevo ID para el centro de costos...");
            $newId = CentroDeCostos::lockForUpdate()->max('id') + 1;
            Log::info("Nuevo ID generado: {$newId}");

            // Insertar el nuevo centro de costos
            Log::info("Insertando nuevo centro de costos...");
            Log::info("Datos recibidos antes de crear el centro de costos:", [
                'descripcion' => $this->descripcion,
                'abrev' => $this->abrev,
            ]);

            $costo = CentroDeCostos::create([
                'id' => $newId,
                'descripcion' => $this->descripcion,
                'abrev' => $this->abrev,
            ]);

            Log::info("Centro de costos creado exitosamente:", $costo->toArray());

            // Emitir el evento para refrescar la tabla
            Log::info("Emitting costo-created event...");
            $this->dispatch('costo-created');

            // Limpiar campos después de insertar
            Log::info("Limpiando campos del formulario...");
            $this->reset(['descripcion', 'abrev']);

            // Emitir un mensaje de éxito
            Log::info("Mostrando mensaje de éxito...");
            session()->flash('message', 'Centro de costos creado exitosamente.');
        }, 5); // Tiempo de espera de la transacción (5 segundos)

        Log::info("Inserción de centro de costos finalizada.");
    }

    public function closeModal()
    {
        $this->resetValidation();
        $this->reset(['descripcion', 'abrev']);
        $this->openModal = false;
    }

    public function clearFields()
    {
        $this->resetValidation();
        $this->reset(['descripcion', 'abrev']);
    }
    public function render()
    {
        return view('livewire.costos-modal');
    }
}
