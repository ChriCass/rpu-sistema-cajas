<?php

namespace App\Livewire;

use App\Models\TipoDeCaja;
use App\Models\TipoDeMoneda;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CajaModal extends Component
{
    public $openModal = false;
    public $descripcion;
    public $t04_tipodemoneda; // Nuevo campo para el tipo de moneda
    public $tiposDeMoneda; // Lista de tipos de moneda para el select

    protected $rules = [
        'descripcion' => 'required|string|max:255',
        't04_tipodemoneda' => 'required|exists:tabla04_tipodemoneda,id', // Validar que el tipo de moneda exista
    ];

    protected $messages = [
        'descripcion.required' => 'El campo descripción es obligatorio.',
        'descripcion.max' => 'La descripción no puede tener más de 255 caracteres.',
        't04_tipodemoneda.required' => 'Debe seleccionar un tipo de moneda.',
        't04_tipodemoneda.exists' => 'El tipo de moneda seleccionado no es válido.',
    ];

    public function mount()
    {
        // Obtener todos los tipos de moneda para el campo de selección
        $this->tiposDeMoneda = TipoDeMoneda::all();
    }

    public function insertNewCaja()
    {
        // Validar los datos del formulario
        $this->validate();

        Log::info("Iniciando inserción de nueva caja...");
        Log::info("Datos recibidos:", [
            'descripcion' => $this->descripcion,
            't04_tipodemoneda' => $this->t04_tipodemoneda,
        ]);

        DB::transaction(function () {
            // Verificar si la caja ya existe con la misma descripción
            Log::info("Verificando si la caja ya existe...");
            $existingCaja = TipoDeCaja::where('descripcion', $this->descripcion)
                ->first();

            if ($existingCaja) {
                Log::warning("Caja duplicada encontrada:", $existingCaja->toArray());
                session()->flash('error', 'Esta caja ya está registrada con la misma descripción.');
                throw new \Exception('Caja duplicada.');
            }

            // Bloquear fila para evitar lecturas concurrentes
            Log::info("Obteniendo nuevo ID para la caja...");
            $newId = TipoDeCaja::lockForUpdate()->max('id') + 1;
            Log::info("Nuevo ID generado: {$newId}");

            // Insertar la nueva caja
            Log::info("Insertando nueva caja...");
            Log::info("Datos recibidos antes de crear la caja:", [
                'descripcion' => $this->descripcion,
                't04_tipodemoneda' => $this->t04_tipodemoneda,
            ]);

            $caja = TipoDeCaja::create([
                'id' => $newId,
                'descripcion' => $this->descripcion,
                't04_tipodemoneda' => $this->t04_tipodemoneda, // Incluir el tipo de moneda
            ]);

            Log::info("Caja creada exitosamente:", $caja->toArray());

            // Emitir el evento para refrescar la tabla
            Log::info("Emitting caja-created event...");
            $this->dispatch('caja-created');

            // Limpiar campos después de insertar
            Log::info("Limpiando campos del formulario...");
            $this->reset(['descripcion', 't04_tipodemoneda']);

            // Emitir un mensaje de éxito
            Log::info("Mostrando mensaje de éxito...");
            session()->flash('message', 'Caja creada exitosamente.');
        }, 5); // Tiempo de espera de la transacción (5 segundos)

        Log::info("Inserción de caja finalizada.");
    }

    public function closeModal()
    {
        $this->resetValidation();
        $this->reset(['descripcion', 't04_tipodemoneda']);
        $this->openModal = false;
    }

    public function clearFields()
    {
        $this->resetValidation();
        $this->reset(['descripcion', 't04_tipodemoneda']);
    }
    public function render()
    {
        return view('livewire.caja-modal');
    }
}
