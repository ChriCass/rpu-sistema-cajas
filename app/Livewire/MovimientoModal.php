<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TipoDeCaja;
use App\Models\Mes;
use App\Models\Apertura;
 
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
        'nuevo_año' => 'required|string',
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
        $this->validate();
    
        // Asegurarse de que la fecha esté en formato yyyy-mm-dd
        try {
            $this->nueva_fecha = date('Y-m-d', strtotime($this->nueva_fecha));
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error al formatear la fecha.');
            return;
        }
    
        // Verificar si la apertura ya existe en la base de datos
        $existingApertura = Apertura::where('id_tipo', $this->tipo_caja)
                                    ->where('año', $this->nuevo_año)
                                    ->where('id_mes', $this->nuevo_mes)
                                    ->where('numero', $this->nuevo_numero)
                                    ->where('fecha', $this->nueva_fecha)
                                    ->first();
    
        if ($existingApertura) {
            // Emitir mensaje de error si la apertura ya existe
            session()->flash('error', 'La apertura ya existe en la base de datos.');
            return;
        }
    
        try {
            // Crear una nueva apertura
            Apertura::create([
                'id_tipo' => $this->tipo_caja,
                'numero' => $this->nuevo_numero,
                'año' => $this->nuevo_año,
                'id_mes' => $this->nuevo_mes,
                'fecha' => $this->nueva_fecha,
            ]);
    
            // Emitir el evento para refrescar la tabla
            $this->dispatch('apertura-created');
    
            // Limpiar campos después de insertar
            $this->reset(['tipo_caja', 'nuevo_numero', 'nuevo_año', 'nuevo_mes', 'nueva_fecha']);
    
            // Emitir un evento o mensaje de éxito
            session()->flash('message', 'Apertura creada exitosamente.');
    
        } catch (\Exception $e) {
            // Emitir mensaje de error en caso de excepción
            session()->flash('error', 'Ocurrió un error al crear la apertura.');
        }
    }
    
    
    public function mount()
    {
        $this->tipoCajas = TipoDeCaja::all();
        $this->meses = Mes::all();
        $años = Apertura::select('año')->distinct()->pluck('año')->toArray();
        
        // Formatear los años como un array de objetos con las claves 'key' y 'year'
        $this->años = array_map(function ($año) {
            return ['key' => $año, 'year' => $año];
        }, $años);
 
    }


    public function render()
    {
        return view('livewire.movimiento-modal');
    }
}
