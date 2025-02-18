<?php

namespace App\Livewire;

use App\Exports\ReporteAnaliticoCostoExport;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Mes;
use App\Models\Familia;
use App\Models\SubFamilia;
use App\Models\Detalle;
use App\Models\CentroDeCostos;
use App\Services\ReporteAnaliticoCostoService;

class ReporteAnaliticoCostoView extends Component
{
    public $años;
    public $meses;
    public $año;
    public $mes;
    public $libros = [
        ['Libro' => 1, 'Tipo' => 'INGRESO'],
        ['Libro' => 2, 'Tipo' => 'EGRESO']
    ];
    public $libro;
    public $familias;
    public $subfamilias;
    public $detalles;
    public $familiaId;
    public $subfamiliaId;
    public $detalleId;
    public $CC;
    public $CCid;
    public $registros;
    protected $reporteAnaliticoCostoService;

    public function mount(){
        $this->inicializarDatos();
    }

    public function hydrate(ReporteAnaliticoCostoService $reporteAnaliticoCostoService)
    {
        $this->reporteAnaliticoCostoService = $reporteAnaliticoCostoService;
    }

    public function procesarReporte()
    {
        if (empty($this->año) || empty($this->libro) || empty($this->detalleId )) {
            session()->flash('error', 'Los parametros como el tipo, el libro y el detalle son obligatorios.');
            return;
        }

        try {
            $this->registros = collect($this->reporteAnaliticoCostoService->AnalisisCostos($this->libro,$this->año,$this->mes,$this->detalleId,$this->CCid));
            session()->flash('message', 'Reporte procesado exitosamente');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Hubo un error al procesar el reporte');
        }
     
    }
    private function inicializarDatos()
    {
        $currentYear = now()->year;
        $this->años = [$currentYear - 1,$currentYear, $currentYear + 1, $currentYear + 2];
        $this->meses = Mes::all();
        $this->familias = Familia::where('id_tipofamilias', '2')->get();
        $this->CC = CentroDeCostos::all();
        
    }

    public function updatedfamiliaId($value)
    {
        // Actualizar las subfamilias según la familia seleccionada
        $this->subfamilias = SubFamilia::select( // Abelardo = Hice cambios para que funcione el select
            'id_familias',
            'id as ic',  // Renombramos el campo 'id' a 'ic'
            'desripcion'  
        )
        ->where('id_familias', $value)
        ->get();
        $this->reset('subfamiliaId', 'detalleId'); // Reiniciar las selecciones
    }

    public function updatedsubfamiliaId($value)
    {
        // Filtrar los detalles según la subfamilia seleccionada
        $this->detalles = Detalle::where('id_subfamilia', $value)
                                    ->where('id_familias', $this -> familiaId)
                                    ->get();
        
        $this->reset('detalleId'); // Reiniciar detalle
    }

    public function exportReporteAnalCosto()
    {
        try {
            // Verificar si hay registros para exportar
            if (empty($this->registros)) {
                session()->flash('error', 'No hay datos para exportar.');
                return;
            }
            $nombreArchivo = 'reporte_analitico_costo_' . $this->libros[$this->libro - 1]['Tipo'] . '_' . $this->año . '_' . str_pad($this->mes, 2, '0', STR_PAD_LEFT) . '.xlsx';

            // Llamar al servicio de exportación (si ya tienes un exportador específico para este reporte)
            return Excel::download(new ReporteAnaliticoCostoExport($this->registros),  $nombreArchivo);
            
        } catch (\Exception $e) {
         
            session()->flash('error', 'Hubo un error al exportar los datos.');
        }
    }

 
   
    public function render()
    {
        return view('livewire.reporte-analitico-costo-view')->layout('layouts.app');
    }
}
