<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TipoDeCaja;
use App\Models\Mes;
use App\Models\Apertura;
use Livewire\Attributes\On;
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
    public $montoInicial;
    public $totalCalculado;

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
    }

    #[On('monto-inicial')]
    public function recibirMontoInicial($montoInicial)
    {
        // Formatear el monto inicial a dos decimales
        $this->montoInicial = number_format($montoInicial, 2, '.', '');
    }
    
    #[On('total-calculado')]
    public function recibirTotalCalculado($totalCalculado)
    {
        // Formatear el total calculado a dos decimales
        $this->totalCalculado = number_format($totalCalculado, 2, '.', '');
    }
    

    public function render()
    {
        return view('livewire.detalle-apertura', [
            'montoInicial' => $this->montoInicial,
            'totalCalculado' => $this->totalCalculado,
        ]);
    }
}
