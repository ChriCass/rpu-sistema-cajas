<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Operador;
use App\Models\ParteDiario;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Carbon\Carbon;

class HorasOperadorView extends Component
{
    use WithPagination;
    
    // Propiedades para filtros
    public $fechaDesde;
    public $fechaHasta;
    public $operadorId;
    public $operadores = [];
    
    // Propiedad para gráfico
    public $datosGrafico = [];
    
    // Propiedad para mostrar tabla
    public $mostrarTabla = false;
    
    public function mount()
    {
        // Inicializar fechas con el mes actual
        $ahora = Carbon::now();
        $this->fechaDesde = $ahora->startOfMonth()->format('Y-m-d');
        $this->fechaHasta = $ahora->endOfMonth()->format('Y-m-d');
        
        // Cargar operadores
        $this->cargarOperadores();
    }
    
    public function cargarOperadores()
    {
        // Obtener todos los operadores activos
        $this->operadores = Operador::where('estado', true)
            ->orderBy('nombre')
            ->get()
            ->toArray();
    }
    
    public function generarReporte()
    {
        $this->mostrarTabla = true;
        $this->resetPage();
        
        // Actualizar datos del gráfico
        $this->actualizarDatosGrafico();
    }
    
    public function actualizarDatosGrafico()
    {
        // Obtener datos para el gráfico de horas trabajadas por operador
        $query = ParteDiario::query()
            ->select('operadores.nombre as operador', DB::raw('SUM(partes_diarios.horas_trabajadas) as total_horas'))
            ->join('operadores', 'partes_diarios.operador_id', '=', 'operadores.id')
            ->whereBetween('fecha_inicio', [$this->fechaDesde, $this->fechaHasta])
            ->groupBy('operadores.id', 'operadores.nombre')
            ->orderBy('total_horas', 'desc');
            
        // Filtrar por operador específico si se seleccionó uno
        if (!empty($this->operadorId)) {
            $query->where('operador_id', $this->operadorId);
        }
        
        $this->datosGrafico = $query->get()->toArray();
    }
    
    public function render()
    {
        // Consulta para obtener los datos detallados de los partes diarios por operador
        $query = ParteDiario::query()
            ->with(['operador', 'unidad', 'entidad'])
            ->whereBetween('fecha_inicio', [$this->fechaDesde, $this->fechaHasta]);
            
        // Filtrar por operador específico si se seleccionó uno
        if (!empty($this->operadorId)) {
            $query->where('operador_id', $this->operadorId);
        }
        
        $partesDiarios = $query->orderBy('fecha_inicio', 'desc')
            ->paginate(10);
        
        return view('livewire.horas-operador-view', [
            'partesDiarios' => $partesDiarios
        ]);
    }
} 