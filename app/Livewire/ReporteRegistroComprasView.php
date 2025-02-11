<?php

namespace App\Livewire;

use App\Exports\ReporteRegistroComprasExport;
use Livewire\Component;
use App\Models\Mes;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\RegistroComprasVentasService;
use Illuminate\Support\Facades\Log;

class ReporteRegistroComprasView extends Component
{

    public $años;
    public $meses;
    public $año;
    public $mes;
    public $registros;
    public $totales;
    protected $registroComprasVentasService;


    public function mount(){
        $this->inicializarDatos();
    }

    public function hydrate(RegistroComprasVentasService $registroComprasVentasService)
    {
        $this->registroComprasVentasService = $registroComprasVentasService;
    }

    public function procesarReporte()
    {
        if (empty($this->año) || empty($this->mes)) {
            session()->flash('error', 'Parametros de año y mes son obligarios.');
            return;
        }

        try {
            
            $this->registros = collect($this->registroComprasVentasService->RComprasVentas(2,$this->mes,$this->año));
            $this->totales = $this->registroComprasVentasService->Totales($this->registros);
            session()->flash('message', 'Reporte procesado exitosamente');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Hubo un error al procesar el reporte');
        }
    }
    
    private function inicializarDatos()
    {
        $currentYear = now()->year;
        $this->años = [$currentYear, $currentYear + 1, $currentYear + 2];
        $this->meses = Mes::all();
    }

    public function exportCompras()
    {
        try {
            // Verificar si hay registros para exportar
            if (empty($this->registros)) {
                session()->flash('error', 'No hay datos para exportar.');
                return;
            }
            $nombreArchivo = 'registro_compras_' . $this->año . '_' . str_pad($this->mes, 2, '0', STR_PAD_LEFT) . '.xlsx';
            // Llamar al servicio de exportación (si ya tienes un exportador específico para este reporte)
            return Excel::download(new ReporteRegistroComprasExport($this->registros), $nombreArchivo);
            
        } catch (\Exception $e) {
            Log::error("Error al exportar los registros de compras/ventas: " . $e->getMessage());
            session()->flash('error', 'Hubo un error al exportar los datos.');
        }
    }
    

    
    public function render()
    {
        return view('livewire.reporte-registro-compras-view')->layout('layouts.app');
    }
}
