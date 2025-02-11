<?php

namespace App\Livewire;

use App\Exports\BalanceCuentasExport;
use Livewire\Component;
use App\Models\Mes;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\BalanceService;
use Illuminate\Support\Facades\Log;

class BalanceCuentasView extends Component
{
    public $años;
    public $meses;
    public $año;
    public $mes;
    public $registros;
    public $totales;

    protected $balanceService;

    public function mount(){
        $this->inicializarDatos();
    }
    public function procesarReporte()
    {
        if (empty($this->año) || empty($this->mes)) {
            session()->flash('error', 'Parametros de año y mes son obligarios.');
            return;
        }
        
        try {
            
            $this->registros = collect($this->balanceService->Balance($this->mes,$this->año));
            session()->flash('message', 'Reporte procesado exitosamente');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Hubo un error al procesar el reporte');
        }
     
    }

    public function hydrate(BalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
    }



    private function inicializarDatos()
    {
        $currentYear = now()->year;
        $this->años = [$currentYear, $currentYear + 1, $currentYear + 2];
        $this->meses = Mes::all();
    }


    public function exportBalanceCuentas()
    {
        try {
            // Verificar si hay registros para exportar
            if (empty($this->registros)) {
                session()->flash('error', 'No hay datos para exportar.');
                return;
            }
            $nombreArchivo = 'reporte_balance_cuentas' . $this->año . '_' . str_pad($this->mes, 2, '0', STR_PAD_LEFT) . '.xlsx';
            // Llamar al servicio de exportación (si ya tienes un exportador específico para este reporte)
            return Excel::download(new BalanceCuentasExport($this->registros),  $nombreArchivo);
            
        } catch (\Exception $e) {
         
            session()->flash('error', 'Hubo un error al exportar los datos.');
        }
    }

 
    public function render()
    {
        return view('livewire.balance-cuentas-view')->layout('layouts.app');
    }
}
