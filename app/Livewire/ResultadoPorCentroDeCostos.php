<?php

namespace App\Livewire;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\CentroDeCostos;
use App\Services\CentroDeCostosService;
use Livewire\Component;
use App\Models\Mes;
use Carbon\Carbon;
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
        try {
            $this->exportarExcel = true;
    
            // Movimientos de ingresos
            $this->movimientos = collect($this->centroDeCostosService->obtenerMovimientos(1, $this->centroDeCosto, $this->año));
            $this->totalesIngresos = $this->sumatoriaMeses($this->movimientos);
    
            // Movimientos de egresos
            $this->movimientos1 = collect($this->centroDeCostosService->obtenerMovimientos(2, $this->centroDeCosto, $this->año)) ?? collect([]);
            $this->totalesEgresos = $this->sumatoriaMeses($this->movimientos1);
    
            session()->flash('message', 'Reporte procesado exitosamente');
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
    

  


    public function render()
    {
        return view('livewire.resultado-por-centro-de-costos')->layout('layouts.app');
    }
}
