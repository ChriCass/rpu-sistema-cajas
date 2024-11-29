<?php

namespace App\Livewire;

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


    public function exportCaja()
    {
        // Respeta el principio SOLID de responsabilidad única.
        // Este método podría encargarse de generar y exportar un archivo relacionado con los reportes de caja.
        // Ya he creado los exports en caso los necesites, están en la dirección:
        // App/Exports/Nombredelreporteenespecifico.php
        // Si no los necesitas, puedes ignorarlos. 
        // Recuerda que esta lógica debe mantenerse exclusiva a este componente.
    }

    public function exportarPDF()
    {
        // Respeta el principio SOLID de responsabilidad única.
        // Este método podría encargarse de generar un reporte en formato PDF.
        // Ya están creadas las vistas concretas para el PDF en los archivos correspondientes.
        // Puedes tomar de referencia las otras exportaciones que consideres necesarias.
        // Si sientes que necesitas ayuda o prefieres no hacerlo, avísame y lo puedo desarrollar por ti.
    }

    public function render()
    {
        return view('livewire.balance-cuentas-view')->layout('layouts.app');
    }
}
