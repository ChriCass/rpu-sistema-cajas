<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MovimientoDeCaja;
use Illuminate\Support\Facades\Log;
class AplicacionTable extends Component
{   

    public $aplicaciones;

    public function mount()
    {
        $this->aplicaciones = MovimientoDeCaja::selectRaw(
                "'APLICACIONES' as apl, DATE_FORMAT(fec, '%d/%m/%Y') as fec, mov, SUM(CASE WHEN id_dh = '1' THEN monto ELSE 0 END) as debe, SUM(CASE WHEN id_dh = '2' THEN monto ELSE 0 END) as haber, SUM(CASE WHEN montodo IS NULL THEN ' ' ELSE montodo END) as do"
            )
            ->leftJoin('cuentas', 'movimientosdecaja.id_cuentas', '=', 'cuentas.id')
            ->where('id_libro', '4')
            ->groupBy('apl', 'fec', 'mov')
            ->get()
            ->map(function($item) {
                return [
                    'apl' => $item->apl,
                    'fec' => $item->fec,
                    'mov' => $item->mov,
                    'monto' => ($item->debe + $item->haber) / 2,
                    'monto_do' => $item->do,
                ];
            });
    }

   
    
    public function render()
    {
        return view('livewire.aplicacion-table', [
            'aplicaciones' => $this->aplicaciones
        ]);
    }
}
