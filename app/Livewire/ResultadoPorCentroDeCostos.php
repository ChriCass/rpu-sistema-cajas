<?php

namespace App\Livewire;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\CentroDeCostos;
use App\Services\CentroDeCostosService;
use Livewire\Component;
use App\Models\Mes;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ResultadoCentroCostosExport;

class ResultadoPorCentroDeCostos extends Component
{
    public $años;
    public $año;
    public $CC;
    public $centroDeCosto;
    public $movimientos;
    public $movimientos1;
    public $exportarExcel;
    public $totalesIngresos;
    public $totalesEgresos;
    
    protected $centroDeCostosService;

    public function mount( )
    {
        
        $this->inicializarDatos();
    }

    public function hydrate(CentroDeCostosService $centroDeCostosService)
    {
        $this->centroDeCostosService = $centroDeCostosService;
    }

    private function inicializarDatos()
    {
        $currentYear = now()->year;
        $this->años = [$currentYear, $currentYear + 1, $currentYear + 2];
        $this->CC =  CentroDeCostos::all();
    }

    public function procesarReporte()
    {
        if (empty($this->centroDeCosto) || empty($this->año)) {
            Log::warning('Campos faltantes: Centro de Costo o Año no seleccionados.', [
                'centroDeCosto' => $this->centroDeCosto,
                'año' => $this->año,
            ]);
            session()->flash('error', 'Todos los campos son requeridos: Año y Centro de Costos.');
            return;
        }
    
        try {
           
    
            // Obtener y loguear los movimientos de ingresos
            $this->movimientos = collect($this->centroDeCostosService->obtenerMovimientos(1, $this->centroDeCosto, $this->año));
            Log::info('Movimientos de ingresos obtenidos', ['movimientos' => $this->movimientos]);
    
            $this->totalesIngresos = $this->sumatoriaMeses($this->movimientos);
    
            // Obtener y loguear los movimientos de egresos
            $this->movimientos1 = collect($this->centroDeCostosService->obtenerMovimientos(2, $this->centroDeCosto, $this->año)) ?? collect([]);
            Log::info('Movimientos de egresos obtenidos', ['movimientos' => $this->movimientos1]);
    
            $this->totalesEgresos = $this->sumatoriaMeses($this->movimientos1);
    
            session()->flash('message', 'Reporte procesado exitosamente');
            $this->exportarExcel = true;
        } catch (\Exception $e) {
            Log::error('Error procesando reporte', [
                'message' => $e->getMessage(),
                'centroDeCosto' => $this->centroDeCosto,
                'año' => $this->año,
            ]);
            session()->flash('error', 'Hubo un error al procesar el reporte');
        }
    }


    public function sumatoriaMeses($movimientos)
    {
        // Obtener los nombres de los meses en minúsculas utilizando Carbon
        $meses = collect(range(1, 12))->map(fn($num) => strtolower(Carbon::createFromDate(null, $num, 1)->translatedFormat('F')));
    
        return $meses->mapWithKeys(function ($mes) use ($movimientos) {
            // Acceder correctamente a las propiedades del stdClass
            $suma = $movimientos->reduce(function ($carry, $mov) use ($mes) {
                return $carry + ($mov->$mes ?? 0);
            }, 0);
    
            Log::info("Suma calculada para {$mes}", ['suma' => $suma]);
    
            return [$mes => $suma];
        })->toArray();
    }
    

    public function exportarPDF()
    {
        try {
            // Validar si hay datos para exportar
            if ($this->movimientos->isEmpty() && $this->movimientos1->isEmpty()) {
                session()->flash('error', 'No hay datos disponibles para exportar.');
                return;
            }
            

            // Preparar los datos para la vista del PDF
            $datos = [
                'movimientos' => $this->movimientos,
                'movimientos1' => $this->movimientos1,
                'totalesIngresos' => $this->totalesIngresos,
                'totalesEgresos' => $this->totalesEgresos,
                'año' => $this->año,
                'centroDeCosto' => CentroDeCostos::find($this->centroDeCosto)->descripcion ?? 'No definido',
                'fecha_exportacion' => Carbon::now()->translatedFormat('d \d\e F \d\e Y')
            ];
    
            // Generar el PDF utilizando una vista de Blade
            $pdf = Pdf::loadView('pdf.resultado_centro_costo', $datos)
                        ->setPaper('a2', 'landscape');
    
            // Retornar el PDF como descarga
            return response()->streamDownload(
                fn() => print($pdf->output()),
                "reporte_centro_costos_{$this->año}.pdf"
            );
    
        } catch (\Exception $e) {
            // Registrar el error en los logs
            Log::error('Error al exportar el PDF: ' . $e->getMessage());
    
            // Mostrar un mensaje de error al usuario
            session()->flash('error', 'Ocurrió un error al generar el PDF.');
            return redirect()->back();
        }
    }
    
    public function exportarCentroCostos()
{
    try {
        // Verificar si la exportación está permitida
        if (!$this->exportarExcel) {
            session()->flash('error', 'La exportación no está permitida.');
            return;
        }

        // Verificar si hay movimientos para exportar
        if (empty($this->movimientos) || empty($this->movimientos1)) {
            session()->flash('error', 'No hay datos para exportar.');
            return;
        }

        // Crear la exportación con los datos del Centro de Costos
        return Excel::download(new ResultadoCentroCostosExport(
            $this->movimientos, 
            $this->movimientos1, 
            $this->totalesIngresos, 
            $this->totalesEgresos
        ), 'centro_costos.xlsx');
    } catch (\Exception $e) {
        Log::error("Error al exportar el Centro de Costos: " . $e->getMessage());
        session()->flash('error', 'Ocurrió un error al exportar el archivo.');
    }
}



    public function render()
    {
        return view('livewire.resultado-por-centro-de-costos')->layout('layouts.app');
    }
}
