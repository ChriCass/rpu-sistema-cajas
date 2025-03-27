<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Traits\WithNotifications;
use Illuminate\Support\Facades\Auth;
use App\Models\Entidad;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class HistorialPagosMaquinaria extends Component
{
    use WithNotifications;
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';
    
    // Propiedades para filtros
    public $fechaInicio;
    public $fechaFin;
    public $entidadId;
    public $estado;
    public $numeroParte;
    
    // Propiedades para entidades y detalles
    public $entidades = [];
    public $historialPagos = [];
    public $paginatedHistorial = [];
    public $detallesPago = null;
    public $mostrarDetalle = false;
    
    // Propiedades para eliminar voucher
    public $mostrarModalConfirmacion = false;
    public $voucherAEliminar = [
        'movimiento' => null,
        'apertura_id' => null
    ];
    
    public function mount()
    {
        // Inicializar fechas: último mes por defecto
        $this->fechaInicio = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->fechaFin = Carbon::now()->format('Y-m-d');
        
        // Cargar entidades para el filtro
        $this->cargarEntidades();
        
        // Cargar historial inicial
        $this->buscarHistorial();
    }
    
    private function cargarEntidades()
    {
        // Obtener entidades que tienen documentos de tipo parte diario de maquinaria
        $this->entidades = DB::select("
            SELECT DISTINCT e.id, e.descripcion
            FROM entidades e
            JOIN documentos d ON e.id = d.id_entidades
            JOIN partes_diarios pd ON pd.entidad_id = e.id AND pd.numero_parte = d.numero
            JOIN aperturas a ON a.id IN (
                SELECT DISTINCT mc.id_apertura 
                FROM movimientosdecaja mc 
                WHERE mc.id_documentos = d.id
            )
            JOIN tipodecaja tc ON a.id_tipo = tc.id
            WHERE d.id_t10tdoc = '82' -- Filtrar solo documentos de tipo Parte Diario (82)
            AND tc.id = 10 -- Filtrar solo por tipo de caja específico para maquinaria (10)
            ORDER BY e.descripcion
        ");
    }
    
    public function buscarHistorial()
    {
        try {
            // Consulta actualizada para mostrar vouchers completos en lugar de documentos individuales
            // Filtrada específicamente para documentos de partes diario de maquinaria
            $query = "
                SELECT 
                    MIN(mc.id) as id,
                    mc.mov,
                    mc.id_apertura,
                    mc.fec AS fecha_pago,
                    a.fecha AS fecha_apertura,
                    e.id AS entidad_id,
                    e.descripcion AS cliente_nombre,
                    SUM(IF(mc.id_dh = 2, mc.monto, 0)) AS monto_total,
                    GROUP_CONCAT(DISTINCT d.numero SEPARATOR ', ') AS documentos_relacionados,
                    COUNT(DISTINCT mc.id_documentos) AS cantidad_documentos,
                    tc.descripcion AS tipo_caja
                FROM movimientosdecaja mc
                LEFT JOIN aperturas a ON mc.id_apertura = a.id
                LEFT JOIN tipodecaja tc ON a.id_tipo = tc.id
                LEFT JOIN documentos d ON mc.id_documentos = d.id
                LEFT JOIN entidades e ON d.id_entidades = e.id
                WHERE mc.id_cuentas = '44'
                AND mc.id_dh = 2
                AND d.id_t10tdoc = '82' -- Filtrar solo documentos de tipo Parte Diario (82)
                AND tc.id = 10 -- Filtrar solo por tipo de caja específico para maquinaria (10)
                AND EXISTS (
                    -- Verificar que exista en la tabla partes_diarios
                    SELECT 1 
                    FROM partes_diarios pd 
                    WHERE pd.numero_parte = d.numero
                    AND pd.entidad_id = d.id_entidades
                )
            ";
            
            // Aplicar filtros
            $params = [];
            
            if ($this->fechaInicio) {
                $query .= " AND mc.fec >= ?";
                $params[] = $this->fechaInicio;
            }
            
            if ($this->fechaFin) {
                $query .= " AND mc.fec <= ?";
                $params[] = $this->fechaFin;
            }
            
            if ($this->entidadId) {
                $query .= " AND e.id = ?";
                $params[] = $this->entidadId;
            }
            
            if ($this->numeroParte) {
                $query .= " AND d.numero LIKE ?";
                $params[] = '%' . $this->numeroParte . '%';
            }
            
            // Agrupar por voucher (movimiento y apertura)
            $query .= " GROUP BY mc.mov, mc.id_apertura, mc.fec, a.fecha, e.id, e.descripcion, tc.descripcion";
            
            // Ordenar por fecha de pago (más reciente primero)
            $query .= " ORDER BY mc.fec DESC, mc.mov DESC";
            
            // Ejecutar la consulta
            $this->historialPagos = DB::select($query, $params);
            
            // Transformar los resultados para mostrar en la vista
            $this->transformarResultados();
            
            // Log para depuración
            Log::info('Historial de pagos cargado', [
                'cantidad' => count($this->historialPagos),
                'filtros' => [
                    'fechaInicio' => $this->fechaInicio,
                    'fechaFin' => $this->fechaFin,
                    'entidadId' => $this->entidadId,
                    'numeroParte' => $this->numeroParte
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al cargar historial de pagos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->notify('error', 'Error al cargar el historial de pagos: ' . $e->getMessage());
        }
    }
    
    private function transformarResultados()
    {
        // Transformar los datos para mejorar la presentación
        foreach ($this->historialPagos as $key => $pago) {
            // Formatear montos
            $this->historialPagos[$key]->monto_fmt = number_format($pago->monto_total, 2);
            
            // Formatear fechas
            $this->historialPagos[$key]->fecha_pago_fmt = Carbon::parse($pago->fecha_pago)->format('d/m/Y');
            
            // Determinar estado según documentos relacionados
            $this->historialPagos[$key]->estado = 'Completado';
            $this->historialPagos[$key]->estado_clase = 'bg-green-100 text-green-800';
        }
    }
    
    public function limpiarFiltros()
    {
        $this->fechaInicio = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->fechaFin = Carbon::now()->format('Y-m-d');
        $this->entidadId = null;
        $this->numeroParte = null;
        
        $this->buscarHistorial();
    }
    
    public function verDetallePago($movimiento, $apertura_id)
    {
        try {
            // Obtener todos los documentos relacionados con este voucher
            // Específicamente filtrados para documentos de partes diario de maquinaria
            $documentosRelacionados = DB::select("
                SELECT 
                    d.id AS documento_id,
                    d.numero AS numero_documento,
                    d.fechaEmi AS fecha_emision,
                    d.fechaVen AS fecha_vencimiento,
                    e.descripcion AS cliente_nombre,
                    e.id AS entidad_id,
                    mc.monto,
                    mc.id_dh,
                    mc.glosa,
                    mc.numero_de_operacion,
                    (
                        SELECT SUM(mc2.monto * IF(mc2.id_dh = 1, 1, -1))
                        FROM movimientosdecaja mc2
                        WHERE mc2.id_documentos = d.id
                        AND mc2.id_cuentas = '44'
                    ) AS saldo_pendiente
                FROM movimientosdecaja mc
                LEFT JOIN documentos d ON mc.id_documentos = d.id
                LEFT JOIN entidades e ON d.id_entidades = e.id
                LEFT JOIN aperturas a ON mc.id_apertura = a.id
                LEFT JOIN tipodecaja tc ON a.id_tipo = tc.id
                WHERE mc.mov = ?
                AND mc.id_apertura = ?
                AND mc.id_cuentas = '44'
                AND mc.id_dh = 2
                AND d.id IS NOT NULL
                AND d.id_t10tdoc = '82' -- Filtrar solo documentos de tipo Parte Diario (82)
                AND tc.id = 10 -- Filtrar solo por tipo de caja específico para maquinaria (10)
                AND EXISTS (
                    -- Verificar que exista en la tabla partes_diarios
                    SELECT 1 
                    FROM partes_diarios pd 
                    WHERE pd.numero_parte = d.numero
                    AND pd.entidad_id = d.id_entidades
                )
                ORDER BY d.numero
            ", [$movimiento, $apertura_id]);
            
            // Obtener detalles del voucher
            $detallesVoucher = DB::select("
                SELECT 
                    a.id AS apertura_id,
                    mc.mov,
                    mc.fec AS fecha_pago,
                    tc.descripcion AS tipo_caja,
                    SUM(IF(mc.id_dh = 2, mc.monto, 0)) AS monto_total,
                    a.fecha AS fecha_apertura,
                    e.descripcion AS cliente_nombre,
                    e.id AS entidad_id
                FROM movimientosdecaja mc
                LEFT JOIN aperturas a ON mc.id_apertura = a.id
                LEFT JOIN tipodecaja tc ON a.id_tipo = tc.id
                LEFT JOIN documentos d ON mc.id_documentos = d.id
                LEFT JOIN entidades e ON d.id_entidades = e.id
                WHERE mc.mov = ?
                AND mc.id_apertura = ?
                AND mc.id_cuentas = '44'
                AND mc.id_dh = 2
                AND tc.id = 10 -- Filtrar solo por tipo de caja específico para maquinaria (10)
                AND d.id_t10tdoc = '82' -- Filtrar solo documentos de tipo Parte Diario (82)
                AND EXISTS (
                    -- Verificar que exista en la tabla partes_diarios
                    SELECT 1 
                    FROM partes_diarios pd 
                    WHERE pd.numero_parte = d.numero
                    AND pd.entidad_id = d.id_entidades
                )
                GROUP BY a.id, mc.mov, mc.fec, tc.descripcion, a.fecha, e.descripcion, e.id
            ", [$movimiento, $apertura_id]);
            
            if (!empty($detallesVoucher)) {
                $this->detallesPago = $detallesVoucher[0];
                $this->detallesPago->documentos = $documentosRelacionados;
                $this->mostrarDetalle = true;
                
                // Formatear datos para la vista
                $this->detallesPago->monto_fmt = number_format($this->detallesPago->monto_total, 2);
                $this->detallesPago->fecha_pago_fmt = Carbon::parse($this->detallesPago->fecha_pago)->format('d/m/Y');
                
                // Formatear datos de los documentos
                foreach ($this->detallesPago->documentos as $key => $doc) {
                    $this->detallesPago->documentos[$key]->monto_fmt = number_format($doc->monto, 2);
                    
                    if ($doc->fecha_emision) {
                        $this->detallesPago->documentos[$key]->fecha_emision_fmt = Carbon::parse($doc->fecha_emision)->format('d/m/Y');
                    }
                    
                    if ($doc->fecha_vencimiento) {
                        $this->detallesPago->documentos[$key]->fecha_vencimiento_fmt = Carbon::parse($doc->fecha_vencimiento)->format('d/m/Y');
                    }
                    
                    // Estado del documento
                    $saldoPendiente = $doc->saldo_pendiente ?? 0;
                    if ($saldoPendiente <= 0) {
                        $this->detallesPago->documentos[$key]->estado = 'Cancelado';
                        $this->detallesPago->documentos[$key]->estado_clase = 'bg-green-100 text-green-800';
                    } else {
                        $this->detallesPago->documentos[$key]->estado = 'Pago Parcial';
                        $this->detallesPago->documentos[$key]->estado_clase = 'bg-orange-100 text-orange-800';
                    }
                }
            } else {
                $this->notify('error', 'No se encontraron detalles para este pago');
            }
            
        } catch (\Exception $e) {
            Log::error('Error al cargar detalles del pago', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'movimiento' => $movimiento,
                'apertura_id' => $apertura_id
            ]);
            $this->notify('error', 'Error al cargar detalles del pago: ' . $e->getMessage());
        }
    }
    
    public function cerrarDetalle()
    {
        $this->mostrarDetalle = false;
        $this->detallesPago = null;
    }
    
    /**
     * Método para editar un voucher
     * Redirige a la página de edición de voucher con los parámetros necesarios
     */
    public function editarVoucher($movimiento, $aperturaId)
    {
        // Cerrar el modal de detalles
        $this->cerrarDetalle();
        
        // Redirigir a la página de edición con los parámetros necesarios
        return redirect()->route('pagos-maquinaria', [
            'origen' => 'edicion',
            'mov' => $movimiento,
            'apertura' => $aperturaId
        ]);
    }
    
    /**
     * Método para mostrar la confirmación de eliminación
     */
    public function confirmarEliminarVoucher($movimiento, $aperturaId)
    {
        $this->voucherAEliminar = [
            'movimiento' => $movimiento,
            'apertura_id' => $aperturaId
        ];
        
        $this->mostrarModalConfirmacion = true;
    }
    
    /**
     * Método para cancelar la eliminación
     */
    public function cancelarEliminarVoucher()
    {
        $this->mostrarModalConfirmacion = false;
        $this->voucherAEliminar = [
            'movimiento' => null,
            'apertura_id' => null
        ];
    }
    
    /**
     * Método para eliminar el voucher
     */
    public function eliminarVoucher()
    {
        try {
            $movimiento = $this->voucherAEliminar['movimiento'];
            $aperturaId = $this->voucherAEliminar['apertura_id'];
            
            // Verificar que tenemos los datos necesarios
            if (!$movimiento || !$aperturaId) {
                $this->notify('error', 'No se pudo identificar el voucher a eliminar');
                return;
            }
            
            // Eliminar los registros de movimientos relacionados con este voucher
            $eliminados = DB::delete("
                DELETE FROM movimientosdecaja
                WHERE mov = ? 
                AND id_apertura = ?
            ", [$movimiento, $aperturaId]);
            
            // Cerrar modales
            $this->mostrarModalConfirmacion = false;
            $this->mostrarDetalle = false;
            
            // Mostrar mensaje de éxito
            if ($eliminados > 0) {
                $this->notify('success', 'Voucher eliminado correctamente');
                
                // Recargar datos
                $this->buscarHistorial();
            } else {
                $this->notify('warning', 'No se encontraron registros para eliminar');
            }
            
            Log::info('Voucher eliminado', [
                'movimiento' => $movimiento,
                'apertura_id' => $aperturaId,
                'registros_eliminados' => $eliminados
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al eliminar voucher', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'movimiento' => $this->voucherAEliminar['movimiento'] ?? null,
                'apertura_id' => $this->voucherAEliminar['apertura_id'] ?? null
            ]);
            
            $this->notify('error', 'Error al eliminar el voucher: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        return view('livewire.historial-pagos-maquinaria', [
            'historialPagos' => $this->historialPagos,
            'entidades' => $this->entidades
        ]);
    }
}
