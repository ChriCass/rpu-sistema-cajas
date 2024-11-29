<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Mes;
use App\Services\DiarioMatrizService;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReporteDiarioMatrizExport;
use Illuminate\Support\Facades\Log;


class ReporteDiarioMatrizView extends Component
{

    public $años;
    public $meses;
    public $año;
    public $mes;
    public $registros;
    protected $diarioMatrizService;

    public function mount(){
        $this->inicializarDatos();
    }

    public function hydrate(DiarioMatrizService $diarioMatrizService)
    {
        $this->diarioMatrizService = $diarioMatrizService;
    }

    private function inicializarDatos()
    {
        $currentYear = now()->year;
        $this->años = [$currentYear, $currentYear + 1, $currentYear + 2];
        $this->meses = Mes::all();
    }



    public function exportExcel()
    {
        if (empty($this->año) || empty($this->mes)) {
            session()->flash('error', 'Parametros de año y mes son obligarios.');
            return;
        }
        try {
            
            $this->registros = collect($this->diarioMatrizService->Diario($this->mes,$this->año));
            return Excel::download(new ReporteDiarioMatrizExport($this->registros), 'DiarioMatriz.xlsx');
            session()->flash('message', 'Reporte procesado exitosamente');
            
        } catch (\Exception $e) {
            Log::error($e);
            session()->flash('error', 'Hubo un error al procesar el reporte');
        }
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
        return view('livewire.reporte-diario-matriz-view')->layout('layouts.app');
    }
}
