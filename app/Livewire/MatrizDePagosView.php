<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\MatrizDePagosServices;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class MatrizDePagosView extends Component
{
    use WithPagination;

    // Propiedades para radio buttons
    public $filtroStatus = 'pendiente';
    public $filtroBanco = null;
    
    // Valores para mantener cambios pendientes
    public $tempFiltroStatus = 'pendiente';
    public $tempFiltroBanco = null;
    
    // Propiedades para filtros adicionales
    public $filters = [
        'id' => '',
        'tdoc' => '',
        'Doc' => '',
        'id_entidades' => '',
        'Deski' => '',
        'name' => '',
        'id_t04tipmon' => '',
        'estadoMon' => '',
    ];
    
    // Paginación
    public $perPage = 10;
    public $currentPage = 1;
    
    // Propiedades para matriz
    public $movimientosFiltrados = [];
    public $totalRegistros = 0;
    public $movimientos = [];

    protected $matrizDePagosService;

    // Constructor para inyectar el servicio
    public function __construct()
    {
        $this->matrizDePagosService = app(MatrizDePagosServices::class);
    }

    // Configuración de paginación
    protected $queryString = [
        'perPage' => ['except' => 25],
        'currentPage' => ['except' => 1],
    ];

    // Establecer que actualizar cualquier propiedad de filtros dispare el método filtrar
    protected function getListeners()
    {
        return [
            'procesamientoTerminado' => 'aplicarFiltros',
        ];
    }

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }

    public function updatedCurrentPage()
    {
        $this->dispatch('scrollToTop');
    }

    // Método para cambiar registros por página
    public function changePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage();
        $this->dispatch('scrollToTop');
    }

    public function resetPage()
    {
        $this->currentPage = 1;
    }

    /**
     * Inicializar propiedades
     */
    public function mount()
    {
        $this->filtroStatus = 'pendiente';
        $this->tempFiltroStatus = 'pendiente';
        $this->perPage = 10;
        $this->currentPage = 1;
        
        $this->procesar();
    }

    /**
     * Método actualizado para el cambio de estado del radio button
     */
    public function updatedTempFiltroStatus($value)
    {
        // No hace nada, solo actualiza el valor temporal
    }

    /**
     * Método que se dispara cuando cualquier propiedad del filtro cambia
     */
    public function updatedFilters($value, $key)
    {
        $this->aplicarFiltros();
        $this->resetPage();
    }

    /**
     * Método para resetear los filtros 
     */
    public function resetFilters()
    {
        $this->filters = [
            'id' => '',
            'tdoc' => '',
            'Doc' => '',
            'id_entidades' => '',
            'Deski' => '',
            'name' => '',
            'id_t04tipmon' => '',
            'estadoMon' => '',
        ];
        
        $this->aplicarFiltros();
        $this->resetPage();
    }

    /**
     * Aplicar los filtros en tiempo real
     */
    public function aplicarFiltros()
    {
        if (empty($this->movimientos)) {
            return;
        }

        // Comenzar con todos los movimientos
        $resultados = $this->movimientos;

        // Aplicar cada filtro si tiene valor
        foreach ($this->filters as $campo => $valor) {
            if (!empty($valor)) {
                $resultados = array_filter($resultados, function($item) use ($campo, $valor) {
                    // Si el campo no existe en el item, saltar
                    if (!isset($item->$campo)) {
                        return true;
                    }
                    
                    // Convertir a string para la comparación
                    $itemValue = (string)$item->$campo;
                    $searchValue = (string)$valor;
                    
                    // Buscar coincidencia parcial, sin importar mayúsculas/minúsculas
                    return stripos($itemValue, $searchValue) !== false;
                });
            }
        }

        // Convertir el resultado a array indexado
        $this->movimientosFiltrados = array_values($resultados);
        $this->totalRegistros = count($this->movimientosFiltrados);
    }

    public function procesar()
    {
        try {
            // Aplicar cambios pendientes de los radio buttons
            $this->filtroStatus = $this->tempFiltroStatus;
            $this->filtroBanco = $this->tempFiltroBanco;

            if (!$this->matrizDePagosService) {
                throw new \Exception('El servicio MatrizDePagosService no está disponible.');
            }

            switch ($this->filtroStatus) {
                case 'pendiente':
                    $this->movimientos = $this->matrizDePagosService->obtenerPagosPendientes($this->filtroBanco ?? null);
                    $mensaje = 'Movimientos pendientes procesados correctamente.';
                    break;

                case 'pagado':
                    $this->movimientos = $this->matrizDePagosService->obtenerPagosPagados($this->filtroBanco ?? null);
                    $mensaje = 'Movimientos pagados procesados correctamente.';
                    break;

                default:
                    $this->movimientos = $this->matrizDePagosService->obtenerTodosLosPagos($this->filtroBanco ?? null);
                    $mensaje = 'Todos los movimientos procesados correctamente.';
                    break;
            }
            
            // Inicialmente, mostrar todos los movimientos
            $this->movimientosFiltrados = $this->movimientos;
            
            // Aplicar filtros existentes después de obtener nuevos movimientos
            $this->aplicarFiltros();
            
            // Calcular el total de registros
            $this->totalRegistros = count($this->movimientosFiltrados);
            
            // Reiniciar a primera página
            $this->resetPage();
            
            $this->dispatch('procesamientoTerminado');

        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error al procesar los pagos: ' . $e->getMessage());
        }
    }

    // Obtener los pagos paginados
    public function getPaginatedMovimientos()
    {
        if ($this->perPage == 0) {
            return $this->movimientosFiltrados;
        }
        
        $offset = ($this->currentPage - 1) * $this->perPage;
        $length = $this->perPage;
        
        return array_slice($this->movimientosFiltrados, $offset, $length);
    }

    // Total de páginas
    public function getTotalPages()
    {
        if ($this->perPage == 0) {
            return 1;
        }
        
        return ceil(count($this->movimientosFiltrados) / $this->perPage);
    }

    public function hydrate(MatrizDePagosServices $matrizDePagosService)
    {
        $this->matrizDePagosService = $matrizDePagosService;
    }

    public function setPage($page)
    {
        $this->currentPage = $page;
        $this->dispatch('scrollToTop');
    }

    public function render()
    {
        return view('livewire.matriz-de-pagos-view', [
            'paginatedMovimientos' => $this->getPaginatedMovimientos(),
            'totalPages' => $this->getTotalPages(),
            'totalRegistros' => count($this->movimientosFiltrados),
        ]);
    }
}
