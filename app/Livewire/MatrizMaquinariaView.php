<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\ParteDiario;
use Illuminate\Support\Facades\DB;

class MatrizMaquinariaView extends Component
{
    public $anioSeleccionado;
    public $partesPagados = [];
    public $partesPendientes = [];
    public $aniosDisponibles = [];
    
    // Propiedades para filtros de partes pagados
    public $filtroPagadosSerie = '';
    public $filtroPagadosEntidad = '';
    public $filtroPagadosFechaDesde = '';
    public $filtroPagadosFechaHasta = '';
    
    // Propiedades para filtros de partes pendientes
    public $filtroPendientesSerie = '';
    public $filtroPendientesEntidad = '';
    public $filtroPendientesFechaDesde = '';
    public $filtroPendientesFechaHasta = '';
    public $filtroPendientesEstado = '';
    
    // Actualizar datos cuando cambien los filtros
    public function updatedFiltroPagadosSerie() { $this->cargarPartesPagados(); }
    public function updatedFiltroPagadosEntidad() { $this->cargarPartesPagados(); }
    public function updatedFiltroPagadosFechaDesde() { $this->cargarPartesPagados(); }
    public function updatedFiltroPagadosFechaHasta() { $this->cargarPartesPagados(); }
    
    public function updatedFiltroPendientesSerie() { $this->cargarPartesPendientes(); }
    public function updatedFiltroPendientesEntidad() { $this->cargarPartesPendientes(); }
    public function updatedFiltroPendientesFechaDesde() { $this->cargarPartesPendientes(); }
    public function updatedFiltroPendientesFechaHasta() { $this->cargarPartesPendientes(); }
    public function updatedFiltroPendientesEstado() { $this->cargarPartesPendientes(); }
    
    // Indicar qué propiedades actualizan el componente cuando cambian
    protected $updatesQueryString = [
        'filtroPagadosSerie', 'filtroPagadosEntidad', 'filtroPagadosFechaDesde', 'filtroPagadosFechaHasta',
        'filtroPendientesSerie', 'filtroPendientesEntidad', 'filtroPendientesFechaDesde', 'filtroPendientesFechaHasta', 'filtroPendientesEstado'
    ];
    
    // Indicar qué propiedades activan una actualización cuando cambian
    protected function getListeners()
    {
        return [
            'refresh' => '$refresh',
        ];
    }
    
    public function mount()
    {
        // Inicializar con el año actual
        $this->anioSeleccionado = date('Y');
        
        // Cargar años disponibles desde la base de datos
        $this->cargarAniosDisponibles();
        
        // Cargar datos iniciales
        $this->cargarDatos();
    }

    public function cargarAniosDisponibles()
    {
        // Obtener los años únicos desde la tabla partes_diarios
        $this->aniosDisponibles = DB::select("
            SELECT DISTINCT YEAR(partes_diarios.fecha_inicio) as anio
            FROM partes_diarios
            WHERE partes_diarios.fecha_inicio IS NOT NULL
            ORDER BY anio DESC
        ");
        
        // Convertir a formato simple de array
        $this->aniosDisponibles = collect($this->aniosDisponibles)->pluck('anio')->toArray();
        
        // Si no hay datos, usar año actual
        if (empty($this->aniosDisponibles)) {
            $this->aniosDisponibles = [date('Y')];
        }
    }
    
    public function cambiarAnio($anio)
    {
        $this->anioSeleccionado = $anio;
        $this->cargarDatos();
    }
    
    public function cargarDatos()
    {
        // Cargar partes pagados
        $this->cargarPartesPagados();
        
        // Cargar partes pendientes
        $this->cargarPartesPendientes();
    }
    
    public function cargarPartesPagados()
    {
        // Consulta para obtener documentos que ya han sido pagados
        $query = "
            SELECT 
                documentos.id,
                documentos.fechaEmi,
                documentos.fechaVen,
                documentos.id_entidades,
                documentos.precio,
                documentos.serie,
                documentos.numero,
                documentos.id_t10tdoc,
                e.descripcion AS entidad_descripcion,
                CON1.monto,
                MAX(movimientosdecaja.fec) as fecha_pago
            FROM (
                SELECT 
                    id_documentos,
                    monto
                FROM (
                    SELECT 
                        id_documentos,
                        id_cuentas,
                        ROUND(SUM(IF(id_dh = '1', monto, -monto)), 2) AS monto,
                        ROUND(SUM(
                            IF(id_dh = '1', IFNULL(montodo, 0), -IFNULL(montodo, 0))
                        ), 2) AS montodo
                    FROM movimientosdecaja
                    WHERE id_cuentas IN ('44')
                    GROUP BY id_documentos, id_cuentas
                ) AS t
                GROUP BY id_documentos 
                HAVING monto = 0
            ) CON1 
            JOIN documentos ON documentos.id = CON1.id_documentos
            LEFT JOIN entidades e ON documentos.id_entidades = e.id
            JOIN movimientosdecaja ON movimientosdecaja.id_documentos = documentos.id
            WHERE YEAR(documentos.fechaEmi) = ?
        ";
        
        $params = [$this->anioSeleccionado];
        
        // Aplicar filtros si están definidos
        if (!empty($this->filtroPagadosSerie)) {
            $query .= " AND CONCAT(documentos.serie, '-', documentos.numero) LIKE ?";
            $params[] = '%' . $this->filtroPagadosSerie . '%';
        }
        
        if (!empty($this->filtroPagadosEntidad)) {
            $query .= " AND e.descripcion LIKE ?";
            $params[] = '%' . $this->filtroPagadosEntidad . '%';
        }
        
        if (!empty($this->filtroPagadosFechaDesde)) {
            $query .= " AND documentos.fechaEmi >= ?";
            $params[] = $this->filtroPagadosFechaDesde;
        }
        
        if (!empty($this->filtroPagadosFechaHasta)) {
            $query .= " AND documentos.fechaEmi <= ?";
            $params[] = $this->filtroPagadosFechaHasta;
        }
        
        $query .= " GROUP BY documentos.id, documentos.fechaEmi, documentos.fechaVen, documentos.id_entidades, documentos.precio, 
                     documentos.serie, documentos.numero, documentos.id_t10tdoc, e.descripcion, CON1.monto
            ORDER BY e.descripcion ASC, documentos.fechaEmi DESC";
        
        // Ejecutar consulta y obtener resultados directamente
        $this->partesPagados = DB::select($query, $params);
    }
    
    public function cargarPartesPendientes()
    {
        // Consulta personalizada para obtener documentos pendientes basados en movimientos de caja
        $query = "
            SELECT 
                documentos.id,
                documentos.fechaEmi,
                documentos.fechaVen,
                documentos.id_entidades,
                documentos.precio,
                documentos.serie,
                documentos.numero,
                documentos.id_t10tdoc,
                e.descripcion AS entidad_descripcion,
                CON1.monto
            FROM (
                SELECT 
                    id_documentos,
                    monto
                FROM (
                    SELECT 
                        id_documentos,
                        id_cuentas,
                        ROUND(SUM(IF(id_dh = '1', monto, -monto)), 2) AS monto,
                        ROUND(SUM(
                            IF(id_dh = '1', IFNULL(montodo, 0), -IFNULL(montodo, 0))
                        ), 2) AS montodo
                    FROM movimientosdecaja
                    WHERE id_cuentas IN ('44')
                    GROUP BY id_documentos, id_cuentas
                ) AS t
                GROUP BY id_documentos 
                HAVING monto <> 0
            ) CON1 
            LEFT JOIN documentos ON documentos.id = CON1.id_documentos
            LEFT JOIN entidades e ON documentos.id_entidades = e.id
            WHERE YEAR(documentos.fechaEmi) = ?
        ";
        
        $params = [$this->anioSeleccionado];
        
        // Aplicar filtros si están definidos
        if (!empty($this->filtroPendientesSerie)) {
            $query .= " AND CONCAT(documentos.serie, '-', documentos.numero) LIKE ?";
            $params[] = '%' . $this->filtroPendientesSerie . '%';
        }
        
        if (!empty($this->filtroPendientesEntidad)) {
            $query .= " AND e.descripcion LIKE ?";
            $params[] = '%' . $this->filtroPendientesEntidad . '%';
        }
        
        if (!empty($this->filtroPendientesFechaDesde)) {
            $query .= " AND documentos.fechaEmi >= ?";
            $params[] = $this->filtroPendientesFechaDesde;
        }
        
        if (!empty($this->filtroPendientesFechaHasta)) {
            $query .= " AND documentos.fechaEmi <= ?";
            $params[] = $this->filtroPendientesFechaHasta;
        }
        
        $query .= " ORDER BY documentos.fechaEmi DESC";
        
        // Ejecutar consulta y obtener resultados
        $this->partesPendientes = DB::select($query, $params);
        
        // Si hay un filtro de estado, aplicarlo manualmente
        if (!empty($this->filtroPendientesEstado)) {
            $partesFiltradas = [];
            
            foreach ($this->partesPendientes as $parte) {
                $fechaVen = $parte->fechaVen ? \Carbon\Carbon::parse($parte->fechaVen) : null;
                $hoy = \Carbon\Carbon::now();
                $diasDesdeEmision = \Carbon\Carbon::parse($parte->fechaEmi)->diffInDays($hoy);
                
                $incluir = false;
                
                switch ($this->filtroPendientesEstado) {
                    case 'vencido':
                        if ($fechaVen && $hoy->gt($fechaVen)) {
                            $incluir = true;
                        } elseif (!$fechaVen && $diasDesdeEmision > 30) {
                            $incluir = true;
                        }
                        break;
                        
                    case 'urgente':
                        if ($fechaVen && !$hoy->gt($fechaVen)) {
                            $diasHastaVencimiento = $hoy->diffInDays($fechaVen);
                            if ($diasHastaVencimiento <= 5) {
                                $incluir = true;
                            }
                        }
                        break;
                        
                    case 'proximo':
                        if ($fechaVen && !$hoy->gt($fechaVen)) {
                            $diasHastaVencimiento = $hoy->diffInDays($fechaVen);
                            if ($diasHastaVencimiento > 5 && $diasHastaVencimiento <= 15) {
                                $incluir = true;
                            }
                        } elseif (!$fechaVen && $diasDesdeEmision > 15 && $diasDesdeEmision <= 30) {
                            $incluir = true;
                        }
                        break;
                        
                    case 'a_tiempo':
                        if ($fechaVen && !$hoy->gt($fechaVen)) {
                            $diasHastaVencimiento = $hoy->diffInDays($fechaVen);
                            if ($diasHastaVencimiento > 15) {
                                $incluir = true;
                            }
                        } elseif (!$fechaVen && $diasDesdeEmision <= 15) {
                            $incluir = true;
                        }
                        break;
                }
                
                if ($incluir) {
                    $partesFiltradas[] = $parte;
                }
            }
            
            $this->partesPendientes = $partesFiltradas;
        }
    }
    
    // Método para limpiar filtros de partes pagados
    public function limpiarFiltrosPagados()
    {
        $this->filtroPagadosSerie = '';
        $this->filtroPagadosEntidad = '';
        $this->filtroPagadosFechaDesde = '';
        $this->filtroPagadosFechaHasta = '';
        
        // Recargar datos después de limpiar filtros
        $this->cargarPartesPagados();
    }
    
    // Método para limpiar filtros de partes pendientes
    public function limpiarFiltrosPendientes()
    {
        $this->filtroPendientesSerie = '';
        $this->filtroPendientesEntidad = '';
        $this->filtroPendientesFechaDesde = '';
        $this->filtroPendientesFechaHasta = '';
        $this->filtroPendientesEstado = '';
        
        // Recargar datos después de limpiar filtros
        $this->cargarPartesPendientes();
    }
    
    public function render()
    {
        $data = [
            'partesPagados' => $this->partesPagados,
            'partesPendientes' => $this->partesPendientes,
            'aniosDisponibles' => $this->aniosDisponibles,
            'anioSeleccionado' => $this->anioSeleccionado,
        ];
        
        return view('livewire.matriz-maquinaria-view', $data);
    }
}
