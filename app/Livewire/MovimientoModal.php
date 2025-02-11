<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TipoDeCaja;
use App\Models\Mes;
use App\Models\Apertura;
 use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MovimientoModal extends Component
{     
    public $openModal = false;
    public $tipoCajas;
    public $años;
    public $meses;
    public $nueva_fecha;
    public $tipo_caja;
    public $nuevo_año;
    public $nuevo_mes;
    public $nuevo_numero;

    protected $rules = [
        'tipo_caja' => 'required',
        'nuevo_año' => 'required',
        'nueva_fecha' => 'required|date',
        'nuevo_mes' => 'required',
        'nuevo_numero' => 'required',
    ];
    
    protected $messages = [
        'tipo_caja.required' => 'El campo tipo de caja es obligatorio.',
        'nuevo_año.required' => 'El campo año es obligatorio.',
        'nueva_fecha.required' => 'El campo fecha es obligatorio.',
        'nueva_fecha.date' => 'El campo fecha debe ser una fecha válida.',
        'nuevo_mes.required' => 'El campo mes es obligatorio.',
        'nuevo_numero.required' => 'El campo número es obligatorio.',
    ];

  
public function insertNewApertura()
{
    // Validar los datos
    Log::info('Iniciando la validación de los datos para una nueva apertura.');
    $this->validate();

    // Asegurarse de que la fecha esté en formato yyyy-mm-dd
    try {
        $this->nueva_fecha = date('Y-m-d', strtotime($this->nueva_fecha));
        Log::info('Fecha formateada correctamente.', ['fecha' => $this->nueva_fecha]);
    } catch (\Exception $e) {
        Log::error('Error al formatear la fecha.', ['error' => $e->getMessage()]);
        session()->flash('error', 'Ocurrió un error al formatear la fecha.');
        return;
    }

    DB::beginTransaction();
    Log::info('Transacción iniciada para insertar nueva apertura.');

    try {
        // Verificar si la apertura ya existe en la base de datos
        Log::info('Verificando si la apertura ya existe.', [
            'id_tipo' => $this->tipo_caja,
            'año' => $this->nuevo_año,
            'id_mes' => $this->nuevo_mes,
            'numero' => $this->nuevo_numero,
            'fecha' => $this->nueva_fecha,
        ]);

        $existingApertura = Apertura::lockForUpdate()
            ->where('id_tipo', $this->tipo_caja)
            ->where('año', $this->nuevo_año)
            ->where('id_mes', $this->nuevo_mes)
            ->where('numero', $this->nuevo_numero)
            ->where('fecha', $this->nueva_fecha)
            ->first();

        if ($existingApertura) {
            Log::warning('La apertura ya existe en la base de datos.', [
                'id_tipo' => $this->tipo_caja,
                'año' => $this->nuevo_año,
                'id_mes' => $this->nuevo_mes,
                'numero' => $this->nuevo_numero,
                'fecha' => $this->nueva_fecha,
            ]);
            session()->flash('error', 'La apertura ya existe en la base de datos.');
            DB::rollBack();
            Log::info('Transacción revertida debido a duplicidad.');
            return;
        }

        // Crear una nueva apertura
        Apertura::create([
            'id_tipo' => $this->tipo_caja,
            'numero' => $this->nuevo_numero,
            'año' => $this->nuevo_año,
            'id_mes' => $this->nuevo_mes,
            'fecha' => $this->nueva_fecha,
        ]);

        DB::commit();
        Log::info('Apertura creada y transacción confirmada.', [
            'id_tipo' => $this->tipo_caja,
            'numero' => $this->nuevo_numero,
            'año' => $this->nuevo_año,
            'id_mes' => $this->nuevo_mes,
            'fecha' => $this->nueva_fecha,
        ]);

        // Emitir el evento para refrescar la tabla
        $this->dispatch('apertura-created');
        Log::info('Evento "apertura-created" emitido.');

        // Limpiar campos después de insertar
        $this->reset(['tipo_caja', 'nuevo_numero', 'nuevo_año', 'nuevo_mes', 'nueva_fecha']);
        Log::info('Campos reseteados tras la creación de la apertura.');

        // Emitir un evento o mensaje de éxito
        session()->flash('message', 'Apertura creada exitosamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al crear la apertura.', ['error' => $e->getMessage()]);
        session()->flash('error', 'Ocurrió un error al crear la apertura.');
    }
}
    
    public function mount()
    {
        $this->tipoCajas = TipoDeCaja::all();
        $this->meses = Mes::all();
        $currentYear = now()->year;
        $this->años = [$currentYear - 1,$currentYear, $currentYear + 1, $currentYear + 2];
 
    }


    public function render()
    {
        return view('livewire.movimiento-modal');
    }
}
