<?php

namespace App\Livewire;

use App\Exports\ReporteInconcistenciasExport;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\InconsistenciasService;

class ReporteInconcistenciasView extends Component
{

    public $registros;
    protected $inconsistenciasService;

    public function hydrate(InconsistenciasService $inconsistenciasService)
    {
        $this->inconsistenciasService = $inconsistenciasService;
    }

    public function procesarReporte()
    {
        try {
            $this->registros = collect($this->inconsistenciasService->InconsistenciasReporte());
            session()->flash('message', 'Reporte procesado exitosamente');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Hubo un error al procesar el reporte');
        }
    }
    


    public function exportInconsistencias()
    {
        try {
            // Verificar si hay registros para exportar
            if (empty($this->registros)) {
                session()->flash('error', 'No hay datos para exportar.');
                return;
            }
            $nombreArchivo = 'reporte_inconsistencias_' . $this->año . '.xlsx';


            // Llamar al servicio de exportación (si ya tienes un exportador específico para este reporte)
            return Excel::download(new ReporteInconcistenciasExport($this->registros),  $nombreArchivo);
            
        } catch (\Exception $e) {
         
            session()->flash('error', 'Hubo un error al exportar los datos.');
        }
    }
    

    public function render()
    {
        return view('livewire.reporte-inconcistencias-view')->layout('layouts.app');
    }
}
