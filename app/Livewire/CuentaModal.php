<?php

namespace App\Livewire;

use App\Models\Cuenta;
use App\Models\TipoDeCuenta;
use App\Models\TipoFamilia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CuentaModal extends Component
{
    public $openModal = false;
    public $cuentaId;
    public $descripcion;
    public $idTipoCuenta;
    public $tipoCuentas;

    protected $rules = [
        'descripcion' => 'required|string|max:255',
        'idTipoCuenta' => 'required|exists:tipodecuenta,id',
    ];

    protected $messages = [
        'descripcion.required' => 'El campo descripción es obligatorio.',
        'descripcion.max' => 'La descripción no puede tener más de 255 caracteres.',
        'idTipoCuenta.required' => 'Debe seleccionar un tipo de cuenta.',
        'idTipoCuenta.exists' => 'El tipo de cuenta seleccionado no es válido.',
    ];

    public function mount()
    {
        // Obtener todos los tipos de cuentas para el campo de selección
        $this->tipoCuentas = TipoDeCuenta::all()->map(function ($tipoCuenta) {
            return ['id' => $tipoCuenta->id, 'descripcion' => $tipoCuenta->descripcion];
        })->toArray();
    }

    public function insertNewCuenta()
    {
        // Validar los datos del formulario
        $this->validate();
    
        Log::info("Iniciando inserción de nueva cuenta...");
        Log::info("Datos recibidos:", [
            'descripcion' => $this->descripcion,
            'idTipoCuenta' => $this->idTipoCuenta,
        ]);
    
        DB::transaction(function () {
            // Verificar si la cuenta ya existe con la misma descripción y tipo de cuenta
            Log::info("Verificando si la cuenta ya existe...");
            $existingCuenta = Cuenta::where('descripcion', $this->descripcion)
                ->where('id_tcuenta', $this->idTipoCuenta)
                ->first();
    
            if ($existingCuenta) {
                Log::warning("Cuenta duplicada encontrada:", $existingCuenta->toArray());
                session()->flash('error', 'Esta cuenta ya está registrada con la misma descripción y tipo.');
                throw new \Exception('Cuenta duplicada.');
            }
    
            // Bloquear fila para evitar lecturas concurrentes
            Log::info("Obteniendo nuevo ID para la cuenta...");
            $newId = Cuenta::lockForUpdate()->max('id') + 1;
            Log::info("Nuevo ID generado: {$newId}");
    
            // Insertar la nueva cuenta
            Log::info("Insertando nueva cuenta...");
            Log::info("Datos recibidos antes de:", [
                'descripcion' => $this->descripcion,
                'idTipoCuenta' => $this->idTipoCuenta,
            ]);
            $cuenta = Cuenta::create([
                'id' => $newId,
                'descripcion' => $this->descripcion,
                'id_tcuenta' => $this->idTipoCuenta,
            ]);
    
            Log::info("Cuenta creada exitosamente:", $cuenta->toArray());
    
            // Emitir el evento para refrescar la tabla
            Log::info("Emitting cuenta-created event...");
            $this->dispatch('cuenta-created');
    
            // Limpiar campos después de insertar
            Log::info("Limpiando campos del formulario...");
            $this->reset(['descripcion', 'idTipoCuenta']);
    
            // Emitir un mensaje de éxito
            Log::info("Mostrando mensaje de éxito...");
            session()->flash('message', 'Cuenta creada exitosamente.');
        }, 5); // Tiempo de espera de la transacción (5 segundos)
    
        Log::info("Inserción de cuenta finalizada.");
    }

    public function closeModal()
    {
        $this->resetValidation();
        $this->reset(['descripcion', 'idTipoCuenta']);
        $this->openModal = false;
    }

    public function clearFields()
    {
        $this->resetValidation();
        $this->reset(['descripcion', 'idTipoCuenta']);
    }

       public function render()
    {
        return view('livewire.cuenta-modal');
    }
}
