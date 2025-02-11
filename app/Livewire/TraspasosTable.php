<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MovimientoDeCaja;
use Illuminate\Support\Facades\Log;
use App\Models\Mes;
use Carbon\Carbon;
class TraspasosTable extends Component
{
    public $traspasos;
    public $meses;
    public $anios;
    public $selectedMonth;
    public $selectedYear;

    public function mount()
    {
        $this->meses = Mes::all();
        $this->anios = collect(range(Carbon::now()->year, Carbon::now()->year + 2))->map(function($year) {
            return [
                'anio' => $year
            ];
        });
        $this->selectedMonth = '';  
        $this->selectedYear = ''; 
        $this->loadAplicaciones(); 
    }

    public function loadAplicaciones()
    {
        $query = MovimientoDeCaja::selectRaw(
            "'TRASPASOS' as apl, DATE_FORMAT(fec, '%d/%m/%Y') as fec, mov, 
            SUM(CASE WHEN id_dh = '1' THEN monto ELSE 0 END) as debe, 
            SUM(CASE WHEN id_dh = '2' THEN monto ELSE 0 END) as haber, 
            SUM(CASE WHEN montodo IS NULL THEN ' ' ELSE montodo END) as do"
        )
        ->leftJoin('cuentas', 'movimientosdecaja.id_cuentas', '=', 'cuentas.id')
        ->where('id_libro', '6')
        ->groupBy('apl', 'fec', 'mov');

        if (!empty(trim($this->selectedMonth))) {
            $query->whereRaw("MONTH(fec) = ?", [$this->selectedMonth]);
        }

        if (!empty(trim($this->selectedYear))) {
            $query->whereRaw("YEAR(fec) = ?", [$this->selectedYear]);
        }

        $this->traspasos = $query->get()
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

    public function filterByMonth($month)
    {
        $this->selectedMonth = $month;
        $this->loadAplicaciones(); // Vuelve a cargar los datos aplicando el filtro
    }

    public function filterByYear($year)
    {
        $this->selectedYear = $year;
        $this->loadAplicaciones(); // Vuelve a cargar los datos aplicando el filtro
    }

    


    public function render()
    {
        return view('livewire.traspasos-table', [
            'traspasos' => $this->traspasos
        ]);
    }
}
