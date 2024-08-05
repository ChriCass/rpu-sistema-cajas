<?php

namespace App\Livewire;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use App\Models\TipoDeCaja;
use App\Models\Mes;
use App\Models\Apertura;

class EditMovimientosModal extends ModalComponent
{
    public $tipoCajas;
    public $años;
    public $meses;
    public $fecha;
    public $caja;
    public $año;
    public $mes;
    public $numero;
    public $aperturaId;

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public function mount(int $aperturaId)
    {
         $this->aperturaId = $aperturaId;
         $apertura = Apertura::findOrFail($aperturaId);
         $this->caja = $apertura->id_tipo;
         $this->año = $apertura->año;
         $this->mes = $apertura->id_mes;
         $this->numero = $apertura->numero;
         $this->fecha = $apertura->fecha;

         $this->meses = Mes::all()->map(function ($mes) {
            return ['id' => $mes->id, 'descripcion' => $mes->descripcion];
        })->toArray();

        $this->tipoCajas = TipoDeCaja::all()->map(function ($tipoCaja) {
            return ['id' => $tipoCaja->id, 'descripcion' => $tipoCaja->descripcion];
        })->toArray();

         
         $años = Apertura::select('año')->distinct()->pluck('año')->toArray();
        


        // Formatear los años como un array de objetos con las claves 'key' y 'year'
        $this->años = array_map(function ($año) {
            return ['key' => $año, 'year' => $año];
        }, $años);
 
    }

    public function render()
    {
        return view('livewire.edit-movimientos-modal');
    }
}
