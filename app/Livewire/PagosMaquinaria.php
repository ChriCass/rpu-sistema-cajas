<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Traits\WithNotifications;
use Illuminate\Support\Facades\Auth;
use App\Models\ParteDiario;
use App\Models\Entidad;
use App\Services\RegistroVauchers;
use App\Services\RegistroDocAvanzService;
use App\Models\Apertura;
use Illuminate\Support\Carbon;

class PagosMaquinaria extends Component
{
    use WithNotifications;
    
    protected $registroDocService;
    protected $RegistroVauchers;
    
    public function hydrate(RegistroDocAvanzService $registroDocService, RegistroVauchers $RegistroVauchers)
    {
        $this->registroDocService = $registroDocService;
        $this->RegistroVauchers = $RegistroVauchers;
    }
    
    public $origen;
    public $id;
    
    // Datos del formulario
    public $fecha;
    public $numeroComprobante = '';
    public $parteId = '';
    public $numeroParte = '';
    
    // Propiedades para el buscador de partes
    public $busquedaParte = '';
    public $mostrarResultados = false;
    public $resultadosBusqueda = [];
    
    // Datos del parte seleccionado
    public $fechaInicioParte = '';
    public $fechaFinParte = '';
    public $clienteNombre = '';
    public $clienteCodigo = '';
    public $importeTotal = '';
    public $montoPagado = '';
    public $montoPendiente = '';
    public $observaciones = '';
    
    // Lista de partes con pagos pendientes
    public $partesPendientes = [];
    
    public function mount($origen = 'nuevo', $id = null)
    {
        Log::info('Iniciando mount del componente PagosMaquinaria', [
            'origen_parametro' => $origen,
            'id_parametro' => $id,
            'request_path' => request()->path(),
            'request_url' => request()->url()
        ]);
        
        // Registrar todos los parámetros de la ruta
        $routeParams = request()->route()->parameters();
        $queryParams = request()->query();
        
        Log::info('Parámetros de ruta', [
            'route_params' => $routeParams,
            'query_params' => $queryParams,
            'all_request_data' => request()->all(),
            'input_data' => request()->input(),
            'url_segments' => explode('/', request()->path())
        ]);
        
        // Obtener origen y id de los query params si están disponibles
        $this->origen = isset($queryParams['origen']) ? $queryParams['origen'] : $origen;
        
        // Para el ID, primero buscar en route params y luego en query params
        if (isset($routeParams['id'])) {
            $this->id = $routeParams['id'];
        } elseif (isset($queryParams['id'])) {
            $this->id = $queryParams['id'];
        } else {
            $this->id = $id;
        }
        
        Log::info('Valores finales asignados', [
            'origen' => $this->origen,
            'id' => $this->id
        ]);
        
        // Inicializar valores predeterminados
        $this->fecha = Carbon::now()->format('Y-m-d');
        
        // Cargar partes pendientes de pago
        $this->cargarPartesPendientes();
        
        // Si es edición, cargar datos del pago
        if ($this->origen === 'edicion' && $this->id) {
            Log::info('Llamando a cargarDatosPago() para el ID: ' . $this->id);
            $this->cargarDatosPago();
            Log::info('cargarDatosPago() completado');
        }
    }
    
    private function cargarPartesPendientes()
    {
        // Cargar partes con estado de pago 0 (pendiente) o 2 (parcial)
        $this->partesPendientes = ParteDiario::whereIn('estado_pago', ['0', '2'])
            ->orderBy('fecha_inicio', 'desc')
            ->get()
            ->map(function ($parte) {
                // Obtener datos de la entidad (cliente)
                $entidad = Entidad::find($parte->entidad_id);
                $clienteNombre = $entidad ? $entidad->descripcion : 'Cliente no encontrado';
                
                return [
                    'id' => $parte->id,
                    'numero_parte' => $parte->numero_parte,
                    'fecha_inicio' => $parte->fecha_inicio->format('d/m/Y'),
                    'cliente_nombre' => $clienteNombre,
                    'cliente_codigo' => $parte->entidad_id,
                    'importe_total' => number_format($parte->importe_cobrar, 2),
                    'monto_pagado' => number_format($parte->monto_pagado ?? 0, 2),
                    'monto_pendiente' => number_format($parte->importe_cobrar - ($parte->monto_pagado ?? 0), 2),
                    'estado_pago' => $parte->estado_pago
                ];
            })
            ->toArray();
            
        Log::info('Partes pendientes cargados', [
            'cantidad' => count($this->partesPendientes)
        ]);
    }
    
    private function cargarDatosPago()
    {
        // Implementación pendiente para la carga de datos de pagos existentes
        Log::info('Método cargarDatosPago() pendiente de implementación');
    }
    
    public function buscarPartes()
    {
        if (strlen($this->busquedaParte) >= 2) {
            $this->resultadosBusqueda = ParteDiario::whereIn('estado_pago', ['0', '2'])
                ->where(function ($query) {
                    $query->where('numero_parte', 'like', '%' . $this->busquedaParte . '%')
                        ->orWhereHas('entidad', function ($q) {
                            $q->where('descripcion', 'like', '%' . $this->busquedaParte . '%');
                        });
                })
                ->orderBy('fecha_inicio', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($parte) {
                    $entidad = Entidad::find($parte->entidad_id);
                    $clienteNombre = $entidad ? $entidad->descripcion : 'Cliente no encontrado';
                    
                    return [
                        'id' => $parte->id,
                        'numero_parte' => $parte->numero_parte,
                        'fecha_inicio' => $parte->fecha_inicio->format('d/m/Y'),
                        'cliente_nombre' => $clienteNombre,
                        'cliente_codigo' => $parte->entidad_id,
                        'importe_total' => number_format($parte->importe_cobrar, 2),
                        'monto_pagado' => number_format($parte->monto_pagado ?? 0, 2),
                        'monto_pendiente' => number_format($parte->importe_cobrar - ($parte->monto_pagado ?? 0), 2),
                        'estado_pago' => $parte->estado_pago
                    ];
                })
                ->toArray();
                
            $this->mostrarResultados = true;
        } else {
            $this->resultadosBusqueda = [];
            $this->mostrarResultados = false;
        }
    }
    
    public function seleccionarParte($id)
    {
        $parte = ParteDiario::find($id);
        if ($parte) {
            $entidad = Entidad::find($parte->entidad_id);
            
            $this->parteId = $parte->id;
            $this->numeroParte = $parte->numero_parte;
            $this->fechaInicioParte = $parte->fecha_inicio->format('Y-m-d');
            $this->fechaFinParte = $parte->fecha_fin->format('Y-m-d');
            $this->clienteNombre = $entidad ? $entidad->descripcion : 'Cliente no encontrado';
            $this->clienteCodigo = $parte->entidad_id;
            $this->importeTotal = number_format($parte->importe_cobrar, 2);
            $this->montoPagado = number_format($parte->monto_pagado ?? 0, 2);
            $this->montoPendiente = number_format($parte->importe_cobrar - ($parte->monto_pagado ?? 0), 2);
            
            $this->busquedaParte = $parte->numero_parte . ' - ' . $this->clienteNombre;
            $this->mostrarResultados = false;
        }
    }
    
    public function limpiarParte()
    {
        $this->parteId = '';
        $this->numeroParte = '';
        $this->fechaInicioParte = '';
        $this->fechaFinParte = '';
        $this->clienteNombre = '';
        $this->clienteCodigo = '';
        $this->importeTotal = '';
        $this->montoPagado = '';
        $this->montoPendiente = '';
        $this->busquedaParte = '';
        $this->mostrarResultados = false;
        $this->resultadosBusqueda = [];
    }
    
    public function registrarPago()
    {
        // Implementación pendiente para el registro de pagos
        $this->notify('info', 'Función en desarrollo. Pronto estará disponible.');
        Log::info('Método registrarPago() pendiente de implementación');
    }
    
    public function render()
    {
        return view('livewire.pagos-maquinaria');
    }
}
