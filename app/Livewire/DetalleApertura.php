<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TipoDeCaja;
use App\Models\Mes;
use App\Models\Apertura;

class DetalleApertura extends Component
{
    public $aperturaId;
    public $apertura;
    public $tipoCajas;
    public $meses;
    public $años;
    public $caja;
    public $año;
    public $mes;
    public $numero;
    public $fecha;

    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;
        $this->apertura = Apertura::findOrFail($aperturaId);
        $this->caja = $this->apertura->id_tipo;
        $this->año = $this->apertura->año;
        $this->mes = $this->apertura->id_mes;
        $this->numero = $this->apertura->numero;
        $this->fecha = $this->apertura->fecha;

        $this->tipoCajas = TipoDeCaja::all()->map(function ($tipoCaja) {
            return ['id' => $tipoCaja->id, 'descripcion' => $tipoCaja->descripcion];
        })->toArray();

        $this->meses = Mes::all()->map(function ($mes) {
            return ['id' => $mes->id, 'descripcion' => $mes->descripcion];
        })->toArray();

        $años = Apertura::select('año')->distinct()->pluck('año')->toArray();
        $this->años = array_map(function ($año) {
            return ['key' => $año, 'year' => $año];
        }, $años);

          // Emitir el evento con los datos de la apertura
          $this->dispatch('aperturaLoaded', [
            'aperturaId' => $this->aperturaId,
            'caja' => $this->caja,
            'año' => $this->año,
            'mes' => $this->mes,
            'numero' => $this->numero,
            'fecha' => $this->fecha,
        ]);
    }

     
    public function render()
    {
        return view('livewire.detalle-apertura');
    }
}
