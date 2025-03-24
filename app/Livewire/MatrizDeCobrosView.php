<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\MatrizDeCobrosServices;
use Illuminate\Support\Facades\Log;  // Asegúrate de importar Log
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Entidad; // Añadir importación del modelo Entidad

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

    // Nueva propiedad para el selector de empresas
    public $mostrarSelectorEmpresas = false;
    public $empresaSeleccionada = null;
    public $empresas = [];
    public $searchTerm = '';

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
        
        // Cargamos las empresas para el selector
        $this->cargarEmpresas();
        
        $this->procesar();
    }

    /**
     * Cargar empresas para el selector
     */
    public function cargarEmpresas()
    {
        // Obtenemos todas las empresas para el selector
        $this->empresas = Entidad::select('id', 'descripcion')
            ->orderBy('descripcion')
            ->get()
            ->toArray();
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
        // Si se selecciona "pagado", mostramos el selector de empresas
        if ($value === 'pagado') {
            $this->mostrarSelectorEmpresas = true;
            // Reseteamos la empresa seleccionada
            $this->empresaSeleccionada = null;
        } else {
            $this->mostrarSelectorEmpresas = false;
            $this->empresaSeleccionada = null;
        }
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
                    // Si se ha seleccionado una empresa, filtramos por ella
                    if ($this->empresaSeleccionada) {
                        $this->movimientos = $this->matrizDeCobrosService->obtenerPagosPagados($this->empresaSeleccionada);
                        $mensaje = 'Movimientos pagados procesados correctamente para la empresa seleccionada.';
                    } else {
                        // Si no se ha seleccionado empresa pero estamos en modo "pagado", no procesamos nada
                        $this->movimientos = [];
                        return;
                    }
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

    /**
     * Método para seleccionar una empresa
     */
    public function seleccionarEmpresa($id)
    {
        $this->empresaSeleccionada = $id;
        // Una vez seleccionada la empresa, procesamos
        $this->procesar();
    }
    
    /**
     * Método para filtrar empresas basado en el término de búsqueda
     */
    public function filtrarEmpresas()
    {
        return collect($this->empresas)
            ->filter(function($empresa) {
                return $this->searchTerm === '' || 
                       stripos($empresa['descripcion'], $this->searchTerm) !== false || 
                       stripos($empresa['id'], $this->searchTerm) !== false;
            })
            ->take(10)
            ->toArray();
    }

    public function render()
    {
        $paginatedMovimientos = $this->getPaginatedMovimientos();
        $totalPages = $this->getTotalPages();
        
        return view('livewire.matriz-de-cobros-view', [
            'paginatedMovimientos' => $paginatedMovimientos,
            'totalPages' => $totalPages,
            'filteredEmpresas' => $this->filtrarEmpresas(),
            'currentPage' => $this->currentPage
        ]);
    }
}
