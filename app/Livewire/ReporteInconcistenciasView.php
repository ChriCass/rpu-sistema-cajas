<?php

namespace App\Livewire;

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
        return view('livewire.reporte-inconcistencias-view')->layout('layouts.app');
    }
}
