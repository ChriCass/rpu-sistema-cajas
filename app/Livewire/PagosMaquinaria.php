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
use Illuminate\Support\Facades\DB;

class PagosMaquinaria extends Component
{
    use WithNotifications;
    
    protected $registroDocService;
    protected $RegistroVauchers;
    
    public function boot(RegistroDocAvanzService $registroDocService, RegistroVauchers $RegistroVauchers)
    {
        $this->registroDocService = $registroDocService;
        $this->RegistroVauchers = $RegistroVauchers;
    }
    
    public function hydrate()
    {
        $this->registroDocService = app(RegistroDocAvanzService::class);
        $this->RegistroVauchers = app(RegistroVauchers::class);
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
    
    // Agregar propiedades para manejar agrupación por entidades
    public $entidadesPendientes = [];
    public $entidadSeleccionada = null;
    public $documentosPorEntidad = [];

    // Propiedades para pago masivo
    public $documentosSeleccionados = [];
    public $montosPorDocumento = [];
    public $totalAPagar = 0;
    
    // Agregar listener para cuando cambie documentosSeleccionados
    protected function getListeners()
    {
        return [
            'updatedDocumentosSeleccionados' => 'actualizarMontosSeleccionados'
        ];
    }
    
    public function updatedDocumentosSeleccionados()
    {
        $this->actualizarMontosSeleccionados();
    }
    
    public function actualizarMontosSeleccionados()
    {
        // Reconstruir montosPorDocumento basado en los documentos seleccionados
        $this->totalAPagar = 0;
        
        // Convertir los documentosSeleccionados a un array en caso de que sea una colección
        $documentosSeleccionados = is_array($this->documentosSeleccionados) ? $this->documentosSeleccionados : $this->documentosSeleccionados->toArray();
        
        // Primero, eliminar cualquier documento que ya no esté seleccionado
        foreach (array_keys($this->montosPorDocumento) as $docId) {
            if (!in_array(strval($docId), $documentosSeleccionados) && !in_array($docId, $documentosSeleccionados)) {
                unset($this->montosPorDocumento[$docId]);
            }
        }
        
        // Luego, agregar nuevos documentos seleccionados
        foreach ($documentosSeleccionados as $docId) {
            // Asegurar que $docId sea un número
            $docId = intval($docId);
            if (!isset($this->montosPorDocumento[$docId])) {
                foreach ($this->documentosPorEntidad as $doc) {
                    if ($doc['id'] == $docId) {
                        $this->montosPorDocumento[$docId] = floatval($doc['monto_pendiente']);
                        break;
                    }
                }
            }
        }
        
        // Recalcular el total
        foreach ($this->montosPorDocumento as $monto) {
            $this->totalAPagar += floatval($monto);
        }
        
        // Asegurar que no tengamos errores de precisión decimal
        $this->totalAPagar = round($this->totalAPagar, 2);
        
        Log::info('Documentos seleccionados actualizados', [
            'documentos_seleccionados' => count($documentosSeleccionados),
            'tipos' => [
                'documentosSeleccionados' => gettype($this->documentosSeleccionados),
                'documentosSeleccionados_array' => gettype($documentosSeleccionados)
            ],
            'montos_por_documento' => $this->montosPorDocumento,
            'total_a_pagar' => $this->totalAPagar
        ]);
    }
    
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
        // Usar consulta SQL personalizada para obtener documentos pendientes
        $documentos = DB::select("
            SELECT 
                documentos.id,
                documentos.serie,
                documentos.numero,
                documentos.fechaEmi,
                documentos.fechaVen,
                documentos.id_entidades,
                documentos.precio,
                CON1.monto,
                DATEDIFF(documentos.fechaVen, CURDATE()) AS dias_restantes,
                CASE 
                    WHEN DATEDIFF(documentos.fechaVen, CURDATE()) < 0 THEN 'Vencido'
                    WHEN DATEDIFF(documentos.fechaVen, CURDATE()) <= 7 THEN 'Urgente'
                    ELSE 'Pendiente'
                END AS estado_fecha,
                CASE 
                    WHEN documentos.precio = CON1.monto THEN 'Pendiente'
                    ELSE 'Pago parcial'
                END AS estado_pago
            FROM (
                SELECT 
                    id_documentos,
                    ROUND(SUM(IF(id_dh = '1', monto, -monto)), 2) AS monto
                FROM movimientosdecaja
                WHERE id_cuentas IN ('44')
                GROUP BY id_documentos
                HAVING monto <> 0
            ) AS CON1
            LEFT JOIN documentos ON documentos.id = CON1.id_documentos
            WHERE documentos.id_t10tdoc = '82' -- Filtrar solo documentos de tipo Parte Diario (82)
            ORDER BY documentos.id_entidades, documentos.fechaEmi DESC
        ");

        Log::info('Documentos pendientes cargados', [
            'cantidad' => count($documentos)
        ]);

        // Transformar datos y agrupar por entidad
        $documentosProcesados = [];
        $entidadesAgrupadas = [];
        
        foreach ($documentos as $doc) {
            $entidad = Entidad::find($doc->id_entidades);
            $nombreEntidad = $entidad ? $entidad->descripcion : 'Cliente no encontrado';
            
            // Procesar documento
            $docProcesado = [
                'id' => $doc->id,
                'numero_parte' => $doc->numero,
                'fecha_inicio' => date('d/m/Y', strtotime($doc->fechaEmi)),
                'fecha_vencimiento' => date('d/m/Y', strtotime($doc->fechaVen)),
                'cliente_nombre' => $nombreEntidad,
                'cliente_codigo' => $doc->id_entidades,
                'importe_total' => (float)$doc->precio,
                'monto_pagado' => (float)$doc->precio - (float)$doc->monto,
                'monto_pendiente' => (float)$doc->monto,
                'monto_pendiente_fmt' => number_format($doc->monto, 2),
                'dias_restantes' => $doc->dias_restantes,
                'estado_fecha' => $doc->estado_fecha,
                'estado_pago' => $doc->estado_pago,
                'importe_total_fmt' => number_format($doc->precio, 2),
                'monto_pagado_fmt' => number_format($doc->precio - $doc->monto, 2)
            ];
            
            // Agregar a documentos procesados
            $documentosProcesados[] = $docProcesado;
            
            // Agregar a la entidad correspondiente
            if (!isset($entidadesAgrupadas[$doc->id_entidades])) {
                $entidadesAgrupadas[$doc->id_entidades] = [
                    'id' => $doc->id_entidades,
                    'nombre' => $nombreEntidad,
                    'total_documentos' => 0,
                    'monto_total' => 0,
                    'monto_pendiente' => 0,
                    'documentos_vencidos' => 0,
                    'documentos_urgentes' => 0,
                    'documentos_pendientes' => 0
                ];
            }
            
            // Actualizar contadores de la entidad
            $entidadesAgrupadas[$doc->id_entidades]['total_documentos']++;
            $entidadesAgrupadas[$doc->id_entidades]['monto_total'] += (float)$doc->precio;
            $entidadesAgrupadas[$doc->id_entidades]['monto_pendiente'] += (float)$doc->monto;
            
            // Contar por estado
            if ($doc->estado_fecha == 'Vencido') {
                $entidadesAgrupadas[$doc->id_entidades]['documentos_vencidos']++;
            } elseif ($doc->estado_fecha == 'Urgente') {
                $entidadesAgrupadas[$doc->id_entidades]['documentos_urgentes']++;
            } else {
                $entidadesAgrupadas[$doc->id_entidades]['documentos_pendientes']++;
            }
        }
        
        // Formatear montos en entidades agrupadas
        foreach ($entidadesAgrupadas as $id => $entidad) {
            $entidadesAgrupadas[$id]['monto_total_fmt'] = number_format($entidad['monto_total'], 2);
            $entidadesAgrupadas[$id]['monto_pendiente_fmt'] = number_format($entidad['monto_pendiente'], 2);
        }

        // Asignar a propiedades de clase
        $this->partesPendientes = $documentosProcesados;
        $this->entidadesPendientes = array_values($entidadesAgrupadas);
    }
    
    private function cargarDatosPago()
    {
        // Implementación pendiente para la carga de datos de pagos existentes
        Log::info('Método cargarDatosPago() pendiente de implementación');
    }
    
    public function buscarPartes()
    {
        if (strlen($this->busquedaParte) >= 2) {
            // Parámetro de búsqueda
            $busqueda = '%' . $this->busquedaParte . '%';
            
            // Consulta SQL para buscar documentos por número o cliente
            $this->resultadosBusqueda = DB::select("
                SELECT 
                    documentos.id,
                    documentos.serie,
                    documentos.numero,
                    documentos.fechaEmi,
                    documentos.fechaVen,
                    documentos.id_entidades,
                    documentos.precio,
                    CON1.monto,
                    DATEDIFF(documentos.fechaVen, CURDATE()) AS dias_restantes,
                    CASE 
                        WHEN DATEDIFF(documentos.fechaVen, CURDATE()) < 0 THEN 'Vencido'
                        WHEN DATEDIFF(documentos.fechaVen, CURDATE()) <= 7 THEN 'Urgente'
                        ELSE 'Pendiente'
                    END AS estado_fecha,
                    CASE 
                        WHEN documentos.precio = CON1.monto THEN 'Pendiente'
                        ELSE 'Pago parcial'
                    END AS estado_pago,
                    e.descripcion AS cliente_nombre
                FROM (
                    SELECT 
                        id_documentos,
                        ROUND(SUM(IF(id_dh = '1', monto, -monto)), 2) AS monto
                    FROM movimientosdecaja
                    WHERE id_cuentas IN ('44')
                    GROUP BY id_documentos
                    HAVING monto <> 0
                ) AS CON1
                LEFT JOIN documentos ON documentos.id = CON1.id_documentos
                LEFT JOIN entidades e ON documentos.id_entidades = e.id
                WHERE documentos.id_t10tdoc = '82' 
                AND (documentos.numero LIKE ? OR e.descripcion LIKE ?)
                ORDER BY documentos.fechaEmi DESC
                LIMIT 10
            ", [$busqueda, $busqueda]);
            
            // Transformar resultados
            $this->resultadosBusqueda = collect($this->resultadosBusqueda)->map(function ($doc) {
                return [
                    'id' => $doc->id,
                    'numero_parte' => $doc->numero,
                    'fecha_inicio' => date('d/m/Y', strtotime($doc->fechaEmi)),
                    'fecha_vencimiento' => date('d/m/Y', strtotime($doc->fechaVen)),
                    'cliente_nombre' => $doc->cliente_nombre ?? 'Cliente no encontrado',
                    'cliente_codigo' => $doc->id_entidades,
                    'importe_total' => number_format($doc->precio, 2),
                    'monto_pagado' => number_format($doc->precio - $doc->monto, 2),
                    'monto_pendiente' => number_format($doc->monto, 2),
                    'dias_restantes' => $doc->dias_restantes,
                    'estado_fecha' => $doc->estado_fecha,
                    'estado_pago' => $doc->estado_pago
                ];
            })->toArray();
            
            $this->mostrarResultados = true;
            
            Log::info('Búsqueda de partes completada', [
                'término' => $this->busquedaParte,
                'resultados' => count($this->resultadosBusqueda)
            ]);
        } else {
            $this->resultadosBusqueda = [];
            $this->mostrarResultados = false;
        }
    }
    
    public function seleccionarParte($id)
    {
        // Obtener información del documento desde la base de datos
        $documento = DB::select("
            SELECT 
                documentos.id,
                documentos.serie,
                documentos.numero,
                documentos.fechaEmi,
                documentos.fechaVen,
                documentos.id_entidades,
                documentos.precio,
                CON1.monto,
                CON1.monto AS monto_pendiente,
                documentos.precio - CON1.monto AS monto_pagado
            FROM documentos
            LEFT JOIN (
                SELECT 
                    id_documentos,
                    ROUND(SUM(IF(id_dh = '1', monto, -monto)), 2) AS monto
                FROM movimientosdecaja
                WHERE id_cuentas IN ('44')
                GROUP BY id_documentos
            ) AS CON1 ON documentos.id = CON1.id_documentos
            WHERE documentos.id = ?
        ", [$id]);
        
        if (!empty($documento)) {
            $documento = $documento[0]; // Obtener el primer resultado
            $entidad = Entidad::find($documento->id_entidades);
            
            $this->parteId = $documento->id;
            $this->numeroParte = $documento->numero;
            $this->fechaInicioParte = date('Y-m-d', strtotime($documento->fechaEmi));
            $this->fechaFinParte = date('Y-m-d', strtotime($documento->fechaVen));
            $this->clienteNombre = $entidad ? $entidad->descripcion : 'Cliente no encontrado';
            $this->clienteCodigo = $documento->id_entidades;
            $this->importeTotal = number_format($documento->precio, 2);
            $this->montoPagado = number_format($documento->monto_pagado, 2);
            $this->montoPendiente = number_format($documento->monto_pendiente, 2);
            
            $this->busquedaParte = $documento->numero . ' - ' . $this->clienteNombre;
            $this->mostrarResultados = false;
            
            Log::info('Documento seleccionado para pago', [
                'documento_id' => $documento->id,
                'numero' => $documento->numero,
                'cliente' => $this->clienteNombre,
                'importe_total' => $this->importeTotal,
                'monto_pendiente' => $this->montoPendiente
            ]);
        } else {
            Log::warning('No se encontró documento con ID: ' . $id);
            $this->notify('error', 'No se pudo cargar la información del documento');
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
    
    public function seleccionarEntidad($entidadId)
    {
        $this->entidadSeleccionada = $entidadId;
        
        // Filtrar documentos de esta entidad
        $this->documentosPorEntidad = array_filter($this->partesPendientes, function($doc) use ($entidadId) {
            return $doc['cliente_codigo'] == $entidadId;
        });
        
        // Convertir de array asociativo a array indexado
        $this->documentosPorEntidad = array_values($this->documentosPorEntidad);
        
        // Resetear las selecciones y montos
        $this->documentosSeleccionados = [];
        $this->montosPorDocumento = [];
        $this->totalAPagar = 0;
        
        Log::info('Entidad seleccionada para ver documentos pendientes', [
            'entidad_id' => $entidadId,
            'documentos_encontrados' => count($this->documentosPorEntidad)
        ]);
    }
    
    // Método para limpiar la selección de entidad
    public function limpiarEntidadSeleccionada()
    {
        $this->entidadSeleccionada = null;
        $this->documentosPorEntidad = [];
        $this->parteId = '';
        $this->documentosSeleccionados = [];
        $this->montosPorDocumento = [];
        $this->totalAPagar = 0;
        $this->limpiarParte();
    }
    
    // Método para seleccionar todos los documentos
    public function seleccionarTodosDocumentos()
    {
        $this->documentosSeleccionados = [];
        
        foreach ($this->documentosPorEntidad as $doc) {
            $this->documentosSeleccionados[] = strval($doc['id']);
        }
        
        $this->actualizarMontosSeleccionados();
        
        Log::info('Todos los documentos seleccionados para pago', [
            'total_seleccionados' => count($this->documentosSeleccionados),
            'total_a_pagar' => $this->totalAPagar
        ]);
    }

    // Método para deseleccionar todos los documentos
    public function deseleccionarTodosDocumentos()
    {
        $this->documentosSeleccionados = [];
        $this->montosPorDocumento = [];
        $this->totalAPagar = 0;
        
        Log::info('Todos los documentos deseleccionados');
    }

    // Método para registrar el pago múltiple
    public function registrarPagoMultiple()
    {
        if (empty($this->documentosSeleccionados)) {
            $this->notify('error', 'Debe seleccionar al menos un documento para pagar');
            return;
        }
        
        // Verificar que esté abierta una caja para la fecha seleccionada y del tipo 10
        $apertura = Apertura::where('id_tipo', 10) // Tipo de caja 10
            ->whereDate('fecha', $this->fecha)
            ->first();
            
        if (!$apertura) {
            $this->notify('error', 'No hay una caja abierta para la fecha ' . $this->fecha . '. Por favor, abra una caja antes de continuar.');
            return;
        }
        
        // Convertir documentosSeleccionados a un array si es necesario
        $documentosSeleccionados = is_array($this->documentosSeleccionados) ? $this->documentosSeleccionados : $this->documentosSeleccionados->toArray();
        
        // Mapear los documentos seleccionados con sus montos
        $documentosConDetalles = [];
        $datosArray = []; // Array para los datos del voucher
        
        // Procesar cada documento seleccionado por el usuario
        foreach ($documentosSeleccionados as $docId) {
            $docId = intval($docId);
            
            // Buscar el documento en la lista
            $documento = null;
            foreach ($this->documentosPorEntidad as $doc) {
                if ($doc['id'] == $docId) {
                    $documento = $doc;
                    break;
                }
            }
            
            // Verificar que el documento exista y tenga un monto válido
            if ($documento && isset($this->montosPorDocumento[$docId]) && $this->montosPorDocumento[$docId] > 0) {
                $documentosConDetalles[] = [
                    'id' => $docId,
                    'numero' => $documento['numero_parte'],
                    'monto_pendiente' => $documento['monto_pendiente'],
                    'monto_a_pagar' => $this->montosPorDocumento[$docId]
                ];
                
                // Agregar al array de datos para el voucher
                $datosArray[] = [
                    'OBSERVACION' => 'Pago de documento: ' . $documento['numero_parte'],
                    'CUENTA' => 44, // Cuenta CXC según el código de referencia
                    'DOCUMENTO' => $docId,
                    'MONTO' => $this->montosPorDocumento[$docId],
                    'NUMERO DE OPERACIÓN' => null
                ];
            }
        }
        
        // Verificar que haya documentos válidos para procesar
        if (empty($documentosConDetalles)) {
            $this->notify('error', 'No hay montos válidos para procesar el pago');
            return;
        }
        
        // Detalles del pago para el log
        $detallesPago = [
            'fecha' => $this->fecha,
            'total_a_pagar' => $this->totalAPagar,
            'entidad_id' => $this->entidadSeleccionada,
            'documentos' => $documentosConDetalles,
            'cantidad_documentos' => count($documentosConDetalles)
        ];
        
        // Crear el array para el voucher en el formato requerido
        $dataVoucher = [
            'APERTURA' => $apertura->id,
            'MONEDA' => 'PEN',
            'DATOS' => $datosArray, // Incluye todos los documentos seleccionados
            'TOTAL' => $this->totalAPagar,
            'TIPOMOVIENTO' => 'CXC',
            'FECHA' => $this->fecha
        ];
                
        // Registrar información detallada en el log
        Log::info('Registrando pago múltiple', [
            'usuario' => Auth::id(),
            'detalles_pago' => $detallesPago,
            'documentos_seleccionados' => $documentosConDetalles,
            'cantidad_documentos' => count($documentosConDetalles),
            'montos_por_documento' => $this->montosPorDocumento,
            'total_a_pagar' => $this->totalAPagar,
            'data_voucher' => $dataVoucher
        ]);
        
        // Aquí llamaríamos al servicio para registrar el pago
        try {
            // Mostrar notificación de desarrollo
            //$this->notify('info', 'Procesando pago para ' . count($documentosConDetalles) . ' documentos por un total de S/ ' . number_format($this->totalAPagar, 2));
            
            // Verificar que el servicio esté disponible
            if (!$this->RegistroVauchers) {
                Log::error('El servicio RegistroVauchers no está disponible');
                $this->notify('error', 'Error al registrar el pago: Servicio no disponible');
                return;
            }
            
            Log::info('Llamando al método guardarVaucher del servicio RegistroVauchers', [
                'dataVoucher' => $dataVoucher
            ]);
            
            // Registrar el voucher
            $resultado = $this->RegistroVauchers->guardarVaucher($dataVoucher);
            
          
            
            // Si el pago fue exitoso, almacenar mensaje en sesión flash y redireccionar
            session()->flash('mensaje', 'Pago registrado correctamente para ' . count($documentosConDetalles) . ' documentos');
            session()->flash('tipo', 'success');
            
            // Redireccionar para refrescar la página
            return redirect()->route('pagos-maquinaria');
            
        } catch (\Exception $e) {
            Log::error('Error al registrar pago múltiple', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->notify('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }
    
    // Método para actualizar el monto a pagar de un documento específico
    public function actualizarMontoPago($documentoId, $monto)
    {
        // Convertir a float y asegurar formato decimal correcto
        $monto = floatval(str_replace(',', '.', $monto));
        
        // Validar que el monto sea válido
        if (!is_numeric($monto) || $monto <= 0) {
            $this->notify('error', 'El monto debe ser un número mayor a cero');
            return;
        }
        
        // Encontrar el documento en la lista para verificar el monto máximo
        $montoMaximo = 0;
        foreach ($this->documentosPorEntidad as $doc) {
            if ($doc['id'] == $documentoId) {
                $montoMaximo = floatval($doc['monto_pendiente']);
                break;
            }
        }
        
        // Si el monto es mayor al pendiente, ajustarlo
        if ($monto > $montoMaximo) {
            $monto = $montoMaximo;
            $this->notify('warning', 'El monto ha sido ajustado al máximo pendiente');
        }
        
        // Actualizar el monto del documento
        $this->montosPorDocumento[$documentoId] = $monto;
        
        // Recalcular el total a pagar sumando todos los montos actuales
        $this->totalAPagar = 0;
        foreach ($this->documentosSeleccionados as $docId) {
            if (isset($this->montosPorDocumento[$docId])) {
                $this->totalAPagar += floatval($this->montosPorDocumento[$docId]);
            }
        }
        
        // Asegurar que no tengamos errores de precisión decimal
        $this->totalAPagar = round($this->totalAPagar, 2);
        
        Log::info('Monto de pago actualizado para documento', [
            'documento_id' => $documentoId,
            'monto' => $monto,
            'total_a_pagar' => $this->totalAPagar
        ]);
    }
    
    public function render()
    {
        return view('livewire.pagos-maquinaria');
    }
}
