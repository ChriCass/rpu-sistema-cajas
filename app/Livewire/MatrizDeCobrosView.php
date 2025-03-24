<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\MatrizDeCobrosServices;
use Illuminate\Support\Facades\Log;  // Asegúrate de importar Log
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class MatrizDeCobrosView extends Component
{
    use WithPagination;

    // Propiedades para radio buttons
    public $filtroStatus = 'pendiente';
    
    // Valores para mantener cambios pendientes
    public $tempFiltroStatus = 'pendiente';
    
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

    protected $matrizDeCobrosService;

    // Constructor para inyectar el servicio
    public function __construct()
    {
        $this->matrizDeCobrosService = app(MatrizDeCobrosServices::class);
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
     * Método que se dispara cuando cualquier propiedad del filtro cambia
     */
    public function updatedFilters($value, $key)
    {
        $this->aplicarFiltros();
        $this->resetPage();
    }

    /**
     * Método actualizado para el cambio de estado del radio button
     */
    public function updatedTempFiltroStatus($value)
    {
        // No hace nada, solo actualiza el valor temporal
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

            if (!$this->matrizDeCobrosService) {
                throw new \Exception('El servicio MatrizDeCobrosService no está disponible.');
            }

            switch ($this->filtroStatus) {
                case 'pendiente':
                    $this->movimientos = $this->matrizDeCobrosService->obtenerPagosPendientes();
                    $mensaje = 'Movimientos pendientes procesados correctamente.';
                    break;

                case 'pagado':
                    $this->movimientos = $this->matrizDeCobrosService->obtenerPagosPagados();
                    $mensaje = 'Movimientos pagados procesados correctamente.';
                    break;

                default:
                    $this->movimientos = $this->matrizDeCobrosService->obtenerTodosLosPagos();
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
            session()->flash('error', 'Ocurrió un error al procesar los cobros: ' . $e->getMessage());
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

    public function hydrate(MatrizDeCobrosServices $matrizDeCobrosService)
    {
        $this->matrizDeCobrosService = $matrizDeCobrosService;
    }

    public function setPage($page)
    {
        $this->currentPage = $page;
        $this->dispatch('scrollToTop');
    }

    public function render()
    {
        return view('livewire.matriz-de-cobros-view', [
            'paginatedMovimientos' => $this->getPaginatedMovimientos(),
            'totalPages' => $this->getTotalPages(),
            'totalRegistros' => count($this->movimientosFiltrados),
        ]);
    }
}
