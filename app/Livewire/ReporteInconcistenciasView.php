<?php

namespace App\Livewire;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
class ReporteInconcistenciasView extends Component
{
    public function procesarReporte()
    {
        // Respeta el principio SOLID de responsabilidad única.
        // Definición: El principio de responsabilidad única establece que una clase, método o componente 
        // debe tener una única razón para cambiar, es decir, debe estar enfocado en realizar una sola tarea o propósito.
     
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
