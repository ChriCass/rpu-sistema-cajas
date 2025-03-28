<?php

namespace App\Livewire;

use Illuminate\Support\Carbon;
use Livewire\Component;
use App\Models\Entidad;
use App\Models\Operador;
use App\Models\Unidad;
use App\Models\TipoVenta;
use App\Models\ParteDiario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\WithNotifications;
use Illuminate\Support\Facades\Auth;
use App\Services\RegistroDocAvanzService;
use App\Services\RegistroVauchers;
use App\Models\Apertura;

class ParteDiarioMaquinaria extends Component
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
    public $fechaInicio;
    public $fechaFin;
    public $diasTotales = 1;
    public $numero = '--';
    public $operador = '';
    public $operadores = [];
    public $unidad = '';
    public $unidades = [];
    public $cliente = '';
    public $codigoEntidad = '';
    public $lugarTrabajo = '';
    public $tipoDocId;
    
    // Propiedades para el buscador de clientes
    public $clientes = [];
    public $busquedaCliente = '';
    public $mostrarResultados = false;
    public $resultadosBusqueda = [];
    
    // Control de horas
    public $horaInicioManana = '';
    public $horaFinManana = '';
    public $horasManana = '';
    public $horaInicioTarde = '';
    public $horaFinTarde = '';
    public $horasTarde = '';
    public $totalHoras = '';
    
    // Horómetro mañana
    public $horometroInicioManana = '';
    public $horometroFinManana = '';
    public $diferenciaHorometroManana = '';
    
    // Horómetro tarde
    public $horometroInicioTarde = '';
    public $horometroFinTarde = '';
    public $diferenciaHorometroTarde = '';
    public $diferencia = ''; // Total diferencia
    public $interrupciones = '';
    
    // Propiedades para valorización
    public $horaPorTrabajo = '';
    public $precioPorHora = '';
    public $importeACobrar = '';
    
    // Descripción y observaciones
    public $descripcionTrabajo = '';
    public $observaciones = '';
    public $pagado = 0; // 0 = pendiente, 1 = pagado
    public $montoPagado = '';
    public $montoPendiente = '0.00';
    
    public $tiposVenta = [];
    public $numeroAnterior;
    
    public function mount($origen = 'nuevo', $id = null)
    {
        Log::info('Iniciando mount del componente ParteDiarioMaquinaria', [
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
        
        // Para el ID, primero buscar en route params (para rutas como /maquinarias/parte-diario/{id}/edit)
        // y luego en query params
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
        $this->fechaInicio = Carbon::now()->format('Y-m-d');
        $this->fechaFin = Carbon::now()->addDay()->format('Y-m-d');
        
        // Cargar tipos de venta desde la base de datos
        $this->tiposVenta = TipoVenta::where('estado', true)
            ->select('id', 'descripcion')
            ->get()
            ->toArray();
        
        // Cargar operadores desde la base de datos
        $this->operadores = Operador::where('estado', true)
            ->select('id', 'nombre')
            ->get()
            ->toArray();
        
        // Cargar unidades desde la base de datos
        $this->unidades = Unidad::where('estado', true)
            ->select('id', 'numero', 'descripcion')
            ->get()
            ->toArray();
        
        $this->calcularDiasTotales();

        // Si es edición, cargar datos del parte
        Log::info('Verificando si debe cargar datos del parte', [
            'origen' => $this->origen,
            'id' => $this->id,
            'condicion' => ($this->origen === 'edicion' && $this->id) ? 'verdadero' : 'falso'
        ]);
        
        if ($this->origen === 'edicion' && $this->id) {
            Log::info('Llamando a cargarDatosParte() para el ID: ' . $this->id);
            $this->cargarDatosParte();
            Log::info('cargarDatosParte() completado');
        }
    }

    private function cargarDatosParte()
    {
        $parte = ParteDiario::find($this->id);
        
        Log::info('Intentando cargar datos del parte diario', [
            'id' => $this->id,
            'parte_encontrado' => $parte ? 'sí' : 'no'
        ]);
        
        if ($parte) {
            Log::info('Datos del parte encontrado', [
                'fecha_inicio' => $parte->fecha_inicio,
                'fecha_fin' => $parte->fecha_fin,
                'operador_id' => $parte->operador_id,
                'unidad_id' => $parte->unidad_id,
                'entidad_id' => $parte->entidad_id,
                'lugar_trabajo' => $parte->lugar_trabajo,
                'horas_trabajadas' => $parte->horas_trabajadas,
                'precio_hora' => $parte->precio_hora,
                'importe_cobrar' => $parte->importe_cobrar,
                'tipo_venta_id' => $parte->tipo_venta_id,
                'estado_pago' => $parte->estado_pago,
                'monto_pagado' => $parte->monto_pagado,
                'numero_parte' => $parte->numero_parte,
                'hora_inicio_manana' => $parte->hora_inicio_manana,
                'hora_fin_manana' => $parte->hora_fin_manana,
                'hora_inicio_tarde' => $parte->hora_inicio_tarde,
                'hora_fin_tarde' => $parte->hora_fin_tarde,
                'horas_manana' => $parte->horas_manana,
                'horas_tarde' => $parte->horas_tarde,
                'total_horas' => $parte->total_horas
            ]);
            
            $this->fechaInicio = $parte->fecha_inicio->format('Y-m-d');
            $this->fechaFin = $parte->fecha_fin->format('Y-m-d');
            $this->operador = $parte->operador_id;
            $this->unidad = $parte->unidad_id;
            $this->codigoEntidad = $parte->entidad_id;
            
            // Cargar el nombre del cliente
            if ($parte->entidad_id) {
                $entidad = Entidad::find($parte->entidad_id);
                Log::info('Datos de la entidad', [
                    'entidad_encontrada' => $entidad ? 'sí' : 'no',
                    'id' => $parte->entidad_id,
                    'descripcion' => $entidad ? $entidad->descripcion : null
                ]);
                
                if ($entidad) {
                    $this->cliente = $entidad->descripcion;
                    $this->busquedaCliente = $entidad->descripcion;
                    $this->tipoDocId = $entidad->idt02doc;
                }
            }
            
            $this->lugarTrabajo = $parte->lugar_trabajo;
            
            // Convertir explícitamente a string todos los valores de horas para la vista
            $this->horaInicioManana = $parte->hora_inicio_manana ? $this->formatearHora($parte->hora_inicio_manana) : '';
            $this->horaFinManana = $parte->hora_fin_manana ? $this->formatearHora($parte->hora_fin_manana) : '';
            $this->horaInicioTarde = $parte->hora_inicio_tarde ? $this->formatearHora($parte->hora_inicio_tarde) : '';
            $this->horaFinTarde = $parte->hora_fin_tarde ? $this->formatearHora($parte->hora_fin_tarde) : '';
            
            // Formatear valores numéricos a string con 2 decimales
            $this->horasManana = $parte->horas_manana ? number_format((float)$parte->horas_manana, 2, '.', '') : '';
            $this->horasTarde = $parte->horas_tarde ? number_format((float)$parte->horas_tarde, 2, '.', '') : '';
            $this->totalHoras = $parte->total_horas ? number_format((float)$parte->total_horas, 2, '.', '') : '';
            
            // Registrar los valores convertidos
            Log::info('Valores de horas convertidos para la vista', [
                'horaInicioManana' => $this->horaInicioManana,
                'horaFinManana' => $this->horaFinManana,
                'horasManana' => $this->horasManana,
                'horaInicioTarde' => $this->horaInicioTarde,
                'horaFinTarde' => $this->horaFinTarde,
                'horasTarde' => $this->horasTarde,
                'totalHoras' => $this->totalHoras,
                'tipos_de_datos' => [
                    'horaInicioManana' => gettype($this->horaInicioManana),
                    'horaFinManana' => gettype($this->horaFinManana),
                    'horasManana' => gettype($this->horasManana),
                    'horaInicioTarde' => gettype($this->horaInicioTarde),
                    'horaFinTarde' => gettype($this->horaFinTarde),
                    'horasTarde' => gettype($this->horasTarde),
                    'totalHoras' => gettype($this->totalHoras)
                ]
            ]);
            
            // Si las horas de mañana están vacías pero tenemos horarios, calculamos
            if (empty($this->horasManana) && $this->horaInicioManana && $this->horaFinManana) {
                Log::info('Calculando horas de mañana en cargarDatosParte');
                $this->calcularHorasManana();
            }
            
            // Si las horas de tarde están vacías pero tenemos horarios, calculamos
            if (empty($this->horasTarde) && $this->horaInicioTarde && $this->horaFinTarde) {
                Log::info('Calculando horas de tarde en cargarDatosParte');
                $this->calcularHorasTarde();
            }
            
            // Si el total de horas está vacío pero tenemos los parciales, calculamos
            if (empty($this->totalHoras) && ($this->horasManana || $this->horasTarde)) {
                Log::info('Calculando total de horas en cargarDatosParte');
                $this->calcularTotalHoras();
            }
            
            // Convertir valores de horómetros a string para la vista
            $this->horometroInicioManana = $parte->horometro_inicio_manana !== null ? (string)$parte->horometro_inicio_manana : '';
            $this->horometroFinManana = $parte->horometro_fin_manana !== null ? (string)$parte->horometro_fin_manana : '';
            $this->horometroInicioTarde = $parte->horometro_inicio_tarde !== null ? (string)$parte->horometro_inicio_tarde : '';
            $this->horometroFinTarde = $parte->horometro_fin_tarde !== null ? (string)$parte->horometro_fin_tarde : '';
            $this->diferenciaHorometroManana = $parte->diferencia_manana ? number_format((float)$parte->diferencia_manana, 2, '.', '') : '0.00';
            $this->diferenciaHorometroTarde = $parte->diferencia_tarde ? number_format((float)$parte->diferencia_tarde, 2, '.', '') : '0.00';
            $this->diferencia = $parte->diferencia_total ? number_format((float)$parte->diferencia_total, 2, '.', '') : '0.00';
            
            // Registrar valores de horómetros
            Log::info('Valores de horómetros convertidos para la vista', [
                'horometroInicioManana' => $this->horometroInicioManana,
                'horometroFinManana' => $this->horometroFinManana,
                'diferenciaHorometroManana' => $this->diferenciaHorometroManana,
                'horometroInicioTarde' => $this->horometroInicioTarde,
                'horometroFinTarde' => $this->horometroFinTarde,
                'diferenciaHorometroTarde' => $this->diferenciaHorometroTarde,
                'diferencia' => $this->diferencia
            ]);
            
            // Calcular diferencias de horómetros si están vacías
            if (empty($this->diferenciaHorometroManana) && $this->horometroInicioManana !== '' && $this->horometroFinManana !== '') {
                Log::info('Calculando diferencia horómetro mañana en cargarDatosParte');
                $this->calcularDiferenciaHorometroManana();
            }
            
            if (empty($this->diferenciaHorometroTarde) && $this->horometroInicioTarde !== '' && $this->horometroFinTarde !== '') {
                Log::info('Calculando diferencia horómetro tarde en cargarDatosParte');
                $this->calcularDiferenciaHorometroTarde();
            }
            
            if (empty($this->diferencia)) {
                Log::info('Calculando diferencia total de horómetros en cargarDatosParte');
                $this->calcularDiferenciaTotal();
            }
            
            $this->interrupciones = $parte->interrupciones ? (string)$parte->interrupciones : '';
            $this->horaPorTrabajo = $parte->horas_trabajadas ? (string)$parte->horas_trabajadas : '';
            $this->precioPorHora = $parte->precio_hora ? (string)$parte->precio_hora : '';
            $this->descripcionTrabajo = $parte->tipo_venta_id;
            $this->observaciones = $parte->observaciones ? (string)$parte->observaciones : '';
            $this->pagado = (string)$parte->estado_pago;
            $this->montoPagado = $parte->monto_pagado ? (string)$parte->monto_pagado : '';
            $this->numero = $parte->numero_parte ? (string)$parte->numero_parte : '';
            $this->numeroAnterior = $parte->numero_parte ? (string)$parte->numero_parte : '';
            
            // Calcular el importe a cobrar en caso de que no esté definido
            if (empty($this->importeACobrar) && $this->horaPorTrabajo && $this->precioPorHora) {
                Log::info('Calculando importe a cobrar en cargarDatosParte');
                $this->calcularImporte();
            } else {
                $this->importeACobrar = $parte->importe_cobrar ? number_format((float)$parte->importe_cobrar, 2, '.', '') : '0.00';
            }
            
            // Si es pago parcial, calcular el monto pendiente
            if ($this->pagado == '2') {
                Log::info('Calculando monto pendiente para pago parcial en cargarDatosParte');
                $this->calcularMontoPendiente();
            }
            
            Log::info('Valores asignados al componente', [
                'fechaInicio' => $this->fechaInicio,
                'fechaFin' => $this->fechaFin,
                'operador' => $this->operador,
                'unidad' => $this->unidad,
                'codigoEntidad' => $this->codigoEntidad,
                'cliente' => $this->cliente,
                'lugarTrabajo' => $this->lugarTrabajo,
                'horaInicioManana' => $this->horaInicioManana,
                'horaFinManana' => $this->horaFinManana,
                'horasManana' => $this->horasManana,
                'horaInicioTarde' => $this->horaInicioTarde,
                'horaFinTarde' => $this->horaFinTarde,
                'horasTarde' => $this->horasTarde,
                'totalHoras' => $this->totalHoras,
                'horaPorTrabajo' => $this->horaPorTrabajo,
                'precioPorHora' => $this->precioPorHora,
                'importeACobrar' => $this->importeACobrar,
                'descripcionTrabajo' => $this->descripcionTrabajo,
                'pagado' => $this->pagado,
                'montoPagado' => $this->montoPagado,
                'montoPendiente' => $this->montoPendiente,
                'numero' => $this->numero
            ]);
        } else {
            Log::error('No se encontró el parte diario con ID: ' . $this->id);
        }
    }

    public function updatedFechaInicio()
    {
        $this->calcularDiasTotales();
    }

    public function updatedFechaFin()
    {
        $this->calcularDiasTotales();
    }

    public function calcularDiasTotales()
    {
        Log::info('Iniciando cálculo de días totales');
        Log::info('Fecha inicio: ' . $this->fechaInicio);
        Log::info('Fecha fin: ' . $this->fechaFin);

        if ($this->fechaInicio && $this->fechaFin) {
            try {
                $inicio = Carbon::createFromFormat('Y-m-d', $this->fechaInicio);
                $fin = Carbon::createFromFormat('Y-m-d', $this->fechaFin);
                
                Log::info('Objetos Carbon creados:');
                Log::info('Inicio: ' . $inicio->format('Y-m-d'));
                Log::info('Fin: ' . $fin->format('Y-m-d'));
                
                // Solo ajustamos la fecha fin si está completamente escrita (tiene 10 caracteres)
                if (strlen($this->fechaFin) === 10 && $fin->lt($inicio)) {
                    Log::warning('La fecha fin es menor que la fecha inicio, ajustando...');
                    $this->fechaFin = $this->fechaInicio;
                    $fin = Carbon::createFromFormat('Y-m-d', $this->fechaFin);
                    Log::info('Fecha fin ajustada a: ' . $fin->format('Y-m-d'));
                }
                
                $dias = $fin->diffInDays($inicio) + 1; // Incluimos ambos días
                Log::info('Días calculados: ' . $dias);
                $this->diasTotales = $dias;
            } catch (\Exception $e) {
                Log::error('Error al calcular días totales: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
                $this->diasTotales = 1;
            }
        } else {
            Log::warning('Fechas no completas, estableciendo días totales a 1');
            $this->diasTotales = 1;
        }
        
        Log::info('Días totales final: ' . $this->diasTotales);
    }

    public function updatedHoraInicioManana()
    {
        $this->calcularHorasManana();
    }

    public function updatedHoraFinManana()
    {
        $this->calcularHorasManana();
    }

    public function updatedHoraInicioTarde()
    {
        $this->calcularHorasTarde();
    }

    public function updatedHoraFinTarde()
    {
        $this->calcularHorasTarde();
    }

    public function calcularHorasManana()
    {
        if ($this->horaInicioManana && $this->horaFinManana) {
            try {
                $inicio = Carbon::createFromFormat('H:i', $this->horaInicioManana);
                $fin = Carbon::createFromFormat('H:i', $this->horaFinManana);
                
                // Si la hora de fin es menor que la hora de inicio, asumimos que es del día siguiente
                if ($fin < $inicio) {
                    $fin->addDay();
                }
                
                $diferencia = $fin->diffInMinutes($inicio);
                $this->horasManana = number_format($diferencia / 60, 2, '.', '');
                $this->calcularTotalHoras();
            } catch (\Exception $e) {
                $this->horasManana = '';
            }
        } else {
            $this->horasManana = '';
            $this->calcularTotalHoras();
        }
    }

    public function calcularHorasTarde()
    {
        if ($this->horaInicioTarde && $this->horaFinTarde) {
            try {
                $inicio = Carbon::createFromFormat('H:i', $this->horaInicioTarde);
                $fin = Carbon::createFromFormat('H:i', $this->horaFinTarde);
                
                // Si la hora de fin es menor que la hora de inicio, asumimos que es del día siguiente
                if ($fin < $inicio) {
                    $fin->addDay();
                }
                
                $diferencia = $fin->diffInMinutes($inicio);
                $this->horasTarde = number_format($diferencia / 60, 2, '.', '');
                $this->calcularTotalHoras();
            } catch (\Exception $e) {
                $this->horasTarde = '';
            }
        } else {
            $this->horasTarde = '';
            $this->calcularTotalHoras();
        }
    }

    public function calcularTotalHoras()
    {
        $manana = floatval($this->horasManana ?: 0);
        $tarde = floatval($this->horasTarde ?: 0);
        $this->totalHoras = number_format($manana + $tarde, 2, '.', '');
    }

    public function updatedHorometroInicioManana()
    {
        $this->calcularDiferenciaHorometroManana();
    }

    public function updatedHorometroFinManana()
    {
        $this->calcularDiferenciaHorometroManana();
    }

    public function updatedHorometroInicioTarde()
    {
        $this->calcularDiferenciaHorometroTarde();
    }

    public function updatedHorometroFinTarde()
    {
        $this->calcularDiferenciaHorometroTarde();
    }

    public function calcularDiferenciaHorometroManana()
    {
        if ($this->horometroInicioManana !== '' && $this->horometroFinManana !== '') {
            try {
                $inicio = floatval($this->horometroInicioManana);
                $fin = floatval($this->horometroFinManana);
                
                if ($fin >= $inicio) {
                    $this->diferenciaHorometroManana = number_format($fin - $inicio, 2, '.', '');
                } else {
                    $this->diferenciaHorometroManana = '0.00';
                }
                
                $this->calcularDiferenciaTotal();
            } catch (\Exception $e) {
                $this->diferenciaHorometroManana = '0.00';
            }
        } else {
            $this->diferenciaHorometroManana = '0.00';
            $this->calcularDiferenciaTotal();
        }
    }
    
    public function calcularDiferenciaHorometroTarde()
    {
        if ($this->horometroInicioTarde !== '' && $this->horometroFinTarde !== '') {
            try {
                $inicio = floatval($this->horometroInicioTarde);
                $fin = floatval($this->horometroFinTarde);
                
                if ($fin >= $inicio) {
                    $this->diferenciaHorometroTarde = number_format($fin - $inicio, 2, '.', '');
                } else {
                    $this->diferenciaHorometroTarde = '0.00';
                }
                
                $this->calcularDiferenciaTotal();
            } catch (\Exception $e) {
                $this->diferenciaHorometroTarde = '0.00';
            }
        } else {
            $this->diferenciaHorometroTarde = '0.00';
            $this->calcularDiferenciaTotal();
        }
    }
    
    public function calcularDiferenciaTotal()
    {
        $manana = floatval($this->diferenciaHorometroManana ?: 0);
        $tarde = floatval($this->diferenciaHorometroTarde ?: 0);
        $this->diferencia = number_format($manana + $tarde, 2, '.', '');
    }
    
    public function updatedHoraPorTrabajo()
    {
        Log::info('updatedHoraPorTrabajo llamado', [
            'valor_anterior' => isset($this->importeACobrar) ? $this->importeACobrar : 'no definido',
            'horaPorTrabajo' => $this->horaPorTrabajo,
            'precioPorHora' => $this->precioPorHora
        ]);
        $this->calcularImporte();
        Log::info('importeACobrar actualizado', [
            'nuevo_valor' => $this->importeACobrar
        ]);
    }

    public function updatedPrecioPorHora()
    {
        Log::info('updatedPrecioPorHora llamado', [
            'valor_anterior' => isset($this->importeACobrar) ? $this->importeACobrar : 'no definido',
            'horaPorTrabajo' => $this->horaPorTrabajo,
            'precioPorHora' => $this->precioPorHora
        ]);
        $this->calcularImporte();
        Log::info('importeACobrar actualizado', [
            'nuevo_valor' => $this->importeACobrar
        ]);
    }

    public function calcularImporte()
    {
        Log::info('Iniciando calcularImporte', [
            'horaPorTrabajo' => $this->horaPorTrabajo,
            'precioPorHora' => $this->precioPorHora
        ]);
        
        if ($this->horaPorTrabajo !== '' && $this->precioPorHora !== '') {
            try {
                $horas = floatval($this->horaPorTrabajo);
                $precio = floatval($this->precioPorHora);
                $this->importeACobrar = number_format($horas * $precio, 2, '.', '');
                
                Log::info('Importe calculado correctamente', [
                    'horas' => $horas,
                    'precio' => $precio,
                    'importe' => $this->importeACobrar
                ]);
            } catch (\Exception $e) {
                Log::error('Error al calcular importe', [
                    'error' => $e->getMessage(),
                    'horaPorTrabajo' => $this->horaPorTrabajo,
                    'precioPorHora' => $this->precioPorHora
                ]);
                $this->importeACobrar = '0.00';
            }
        } else {
            Log::info('No se puede calcular el importe, valores incompletos', [
                'horaPorTrabajo' => $this->horaPorTrabajo,
                'precioPorHora' => $this->precioPorHora
            ]);
            $this->importeACobrar = '0.00';
        }
    }

    public function guardar()
    {
        try {
            // Validación de horas de trabajo
            if ((empty($this->horaInicioManana) || $this->horaInicioManana === '00:00') && 
                (empty($this->horaFinManana) || $this->horaFinManana === '00:00') && 
                (empty($this->horaInicioTarde) || $this->horaInicioTarde === '00:00') && 
                (empty($this->horaFinTarde) || $this->horaFinTarde === '00:00')) {
                $this->notify('error', 'Debe registrar al menos un período de trabajo (mañana o tarde)');
                return;
            }

            // Validación de horas de la mañana
            Log::info('Validando horas mañana', [
                'horaInicioManana' => $this->horaInicioManana,
                'horaFinManana' => $this->horaFinManana
            ]);
            
            if (($this->horaInicioManana && $this->horaInicioManana !== '00:00') && 
                (!$this->horaFinManana || $this->horaFinManana === '00:00')) {
                Log::warning('Error: Hora inicio mañana sin hora fin');
                $this->notify('error', 'Si se ingresa hora de inicio de mañana, debe ingresar también la hora de fin.');
                return;
            }

            // Validación de horas de la tarde
            Log::info('Validando horas tarde', [
                'horaInicioTarde' => $this->horaInicioTarde,
                'horaFinTarde' => $this->horaFinTarde
            ]);
            
            if (($this->horaInicioTarde && $this->horaInicioTarde !== '00:00') && 
                (!$this->horaFinTarde || $this->horaFinTarde === '00:00')) {
                Log::warning('Error: Hora inicio tarde sin hora fin');
                $this->notify('error', 'Si se ingresa hora de inicio de tarde, debe ingresar también la hora de fin.');
                return;
            }

            // Validación de orden de horas
            if ($this->horaInicioManana && $this->horaFinManana && $this->horaInicioTarde &&
                $this->horaInicioManana !== '00:00' && $this->horaFinManana !== '00:00' && $this->horaInicioTarde !== '00:00') {
                
                $horaFinManana = Carbon::createFromFormat('H:i', $this->horaFinManana);
                $horaInicioTarde = Carbon::createFromFormat('H:i', $this->horaInicioTarde);
                
                Log::info('Validando orden de horas', [
                    'horaFinManana' => $horaFinManana->format('H:i'),
                    'horaInicioTarde' => $horaInicioTarde->format('H:i')
                ]);
                
                if ($horaInicioTarde < $horaFinManana) {
                    Log::warning('Error: Hora inicio tarde anterior a hora fin mañana');
                    $this->notify('error', 'La hora de inicio de la tarde debe ser posterior a la hora de fin de la mañana.');
                    return;
                }
            }

            // Validación básica
            try {
                $this->validate([
                    'fechaInicio' => 'required|date',
                    'fechaFin' => 'required|date|after_or_equal:fechaInicio',
                    'operador' => 'required|exists:operadores,id',
                    'unidad' => 'required|exists:unidades,id',
                    'codigoEntidad' => 'required|exists:entidades,id',
                    'descripcionTrabajo' => 'required|exists:tipos_venta,id',
                    'lugarTrabajo' => 'nullable|string|max:255',
                    'horaPorTrabajo' => 'required|numeric|min:0',
                    'precioPorHora' => 'required|numeric|min:0',
                    'importeACobrar' => 'required|numeric|min:0',
                    'pagado' => 'required|in:0,1,2',
                    'observaciones' => 'nullable|string|max:1000',
                    'interrupciones' => 'nullable|string|max:1000',
                    'numero' => 'required|not_in:--,0',
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                // Extraer el primer mensaje de error para mostrarlo con notify
                $errorMsg = collect($e->validator->errors()->all())->first();
                $this->notify('error', $errorMsg);
                
                // Registrar todos los errores de validación en el log
                Log::warning('Errores de validación en parte diario', [
                    'errores' => $e->validator->errors()->all(),
                    'usuario' => Auth::user()->id
                ]);
                
                // Re-lanzar la excepción para que Livewire muestre los errores de validación en el formulario
                throw $e;
            }

            // Verificar si el número de parte ya existe en la base de datos
            $existeNumero = ParteDiario::where('numero_parte', $this->numero)
                ->when($this->origen === 'edicion' && $this->id, function($query) {
                    // Si es edición, excluir el parte actual de la búsqueda
                    return $query->where('id', '!=', $this->id);
                })
                ->exists();
            
            if ($existeNumero) {
                Log::warning('Intento de usar un número de parte ya existente', [
                    'numero' => $this->numero,
                    'id_parte' => $this->id,
                    'usuario' => Auth::user()->id
                ]);
                $this->notify('error', 'El número de parte ya existe en la base de datos. Por favor, ingrese otro número.');
                return;
            }

            // Validación de pago parcial
            if ($this->pagado == '2') {
                $this->calcularMontoPendiente();
            }

            // Buscar la apertura correspondiente solo si está pagado o pago parcial
            if ($this->pagado == '1' || $this->pagado == '2') {
                $fecha = Carbon::parse($this->fecha);
                $apertura = Apertura::where('id_tipo', 10) // Caja partes
                    ->where('fecha', $fecha->format('Y-m-d'))
                    ->first();

                if (!$apertura) {
                    Log::warning('No se encontró apertura para la fecha: ' . $this->fecha);
                    $this->notify('error', 'Las cajas aún no han sido aperturadas para esta fecha.');
                    return;
                }
            }

            // Validación de horómetro mañana
            Log::info('Validando horómetro mañana', [
                'horometroInicioManana' => $this->horometroInicioManana,
                'horometroFinManana' => $this->horometroFinManana
            ]);
            
            if ($this->horometroInicioManana !== '' && $this->horometroFinManana === '') {
                Log::warning('Error: Horómetro inicio mañana sin horómetro fin');
                $this->notify('error', 'Si se ingresa horómetro de inicio de mañana, debe ingresar también el horómetro de fin.');
                return;
            }

            if ($this->horometroInicioManana !== '' && $this->horometroFinManana !== '') {
                $inicio = floatval($this->horometroInicioManana);
                $fin = floatval($this->horometroFinManana);
                
                Log::info('Validando valores horómetro mañana', [
                    'inicio' => $inicio,
                    'fin' => $fin
                ]);
                
                if ($fin < $inicio) {
                    Log::warning('Error: Horómetro fin mañana menor que inicio');
                    $this->notify('error', 'El horómetro de fin de mañana no puede ser menor que el horómetro de inicio.');
                    return;
                }
            }

            // Validación de horómetro tarde
            Log::info('Validando horómetro tarde', [
                'horometroInicioTarde' => $this->horometroInicioTarde,
                'horometroFinTarde' => $this->horometroFinTarde
            ]);
            
            if ($this->horometroInicioTarde !== '' && $this->horometroFinTarde === '') {
                Log::warning('Error: Horómetro inicio tarde sin horómetro fin');
                $this->notify('error', 'Si se ingresa horómetro de inicio de tarde, debe ingresar también el horómetro de fin.');
                return;
            }

            if ($this->horometroInicioTarde !== '' && $this->horometroFinTarde !== '') {
                $inicio = floatval($this->horometroInicioTarde);
                $fin = floatval($this->horometroFinTarde);
                
                Log::info('Validando valores horómetro tarde', [
                    'inicio' => $inicio,
                    'fin' => $fin
                ]);
                
                if ($fin < $inicio) {
                    Log::warning('Error: Horómetro fin tarde menor que inicio');
                    $this->notify('error', 'El horómetro de fin de tarde no puede ser menor que el horómetro de inicio.');
                    return;
                }
            }

            // Validación de continuidad entre horómetros
            if ($this->horometroFinManana !== '' && $this->horometroInicioTarde !== '') {
                $finManana = floatval($this->horometroFinManana);
                $inicioTarde = floatval($this->horometroInicioTarde);
                
                Log::info('Validando continuidad horómetros', [
                    'finManana' => $finManana,
                    'inicioTarde' => $inicioTarde
                ]);
                
                if ($inicioTarde < $finManana) {
                    Log::warning('Error: Horómetro inicio tarde menor que fin mañana');
                    $this->notify('error', 'El horómetro de inicio de tarde no puede ser menor que el horómetro de fin de mañana.');
                    return;
                }
            }

            Log::info('Todas las validaciones pasadas correctamente');

            try {
                // Iniciar transacción
                DB::beginTransaction();
                Log::info('Iniciando transacción de guardado');

                $datos = [
                    'fecha_inicio' => $this->fechaInicio,
                    'fecha_fin' => $this->fechaFin,
                    'operador_id' => $this->operador,
                    'unidad_id' => $this->unidad,
                    'entidad_id' => $this->codigoEntidad,
                    'tipo_venta_id' => $this->descripcionTrabajo,
                    'lugar_trabajo' => $this->lugarTrabajo ?? null,
                    'hora_inicio_manana' => $this->horaInicioManana ?? null,
                    'hora_fin_manana' => $this->horaFinManana ?? null,
                    'horas_manana' => $this->horasManana ?? null,
                    'hora_inicio_tarde' => $this->horaInicioTarde ?? null,
                    'hora_fin_tarde' => $this->horaFinTarde ?? null,
                    'horas_tarde' => $this->horasTarde ?? null,
                    'total_horas' => $this->totalHoras ?? null,
                    'horometro_inicio_manana' => $this->horometroInicioManana ?? null,
                    'horometro_fin_manana' => $this->horometroFinManana ?? null,
                    'diferencia_manana' => $this->diferenciaHorometroManana ?? null,
                    'horometro_inicio_tarde' => $this->horometroInicioTarde ?? null,
                    'horometro_fin_tarde' => $this->horometroFinTarde ?? null,
                    'diferencia_tarde' => $this->diferenciaHorometroTarde ?? null,
                    'diferencia_total' => $this->diferencia ?? null,
                    'interrupciones' => $this->interrupciones ?? null,
                    'horas_trabajadas' => $this->horaPorTrabajo,
                    'precio_hora' => $this->precioPorHora,
                    'importe_cobrar' => $this->importeACobrar ?? null,
                    'estado_pago' => $this->pagado ?? null,
                    'monto_pagado' => $this->montoPagado ?? null,
                    'observaciones' => $this->observaciones ?? null,
                    'numero_parte' => $this->numero,
                ];

                Log::info('Datos a guardar:', $datos);

                if ($this->origen === 'nuevo') {
                    
                    
                    $parte = ParteDiario::create($datos);
                    $mensaje = 'Parte diario creado correctamente!';
                    Log::info('Parte diario creado con ID: ' . $parte->id);

                    // Preparar datos para el servicio de registro de documento
                    $data = [
                        'tipoDocumento' => 82,
                        'tipoDocDescripcion' => 'Parte Diario',
                        'serieNumero1' => '0000',
                        'serieNumero2' => $this->numero,
                        'tipoDocId' => $this->tipoDocId,
                        'docIdent' => $this->codigoEntidad,
                        'entidad' => $this->cliente,
                        'monedaId' => 'PEN',
                        'tasaIgvId' => 'No Gravado',
                        'fechaEmi' => $parte->fecha_inicio,
                        'fechaVen' => $parte->fecha_fin,
                        'basImp' => 0,
                        'igv' => 0,
                        'noGravado' => $parte->importe_cobrar,
                        'precio' => $parte->importe_cobrar,
                        'observaciones' => 'Parte diario de maquinaria - ' . $this->numero,
                        'user' => Auth::user()->id,
                        'productos' => [
                            [
                                'codigoProducto' => 'B6DD38',
                                'productoSeleccionado' => 'GENERAL',
                                'tasaImpositiva' => 0,
                                'observacion' => null,
                                'cantidad' => 1,
                                'precioUnitario' => $parte->importe_cobrar,
                                'total' => $parte->importe_cobrar,
                                'CC' => null,
                                'producto' => 'GENERAL'
                            ]
                        ],
                        'origen' => $this->pagado == '0' ? 'cxc' : ($this->pagado == '2' ? 'cxc' : 'ingreso'),
                        'cuenta' => 44, // Cuenta por defecto para ingresos
                        'cod_operacion' => null
                    ];

                    Log::info('Datos a enviar al servicio:', [
                        'data' => $data,
                        'estado_pago' => $this->pagado,
                        'parte_id' => $parte->id,
                        'numero_parte' => $parte->numeroParte,
                        'importe' => $parte->importeACobrar
                    ]);

                    // Solo incluir datos de apertura si está pagado
                    if ($this->pagado == '1' || $this->pagado == '2') {
                        $fecha = Carbon::parse($this->fecha);
                        $apertura = Apertura::where('id_tipo', 10) // Caja partes
                            ->where('fecha', $fecha->format('Y-m-d'))
                            ->first();

                        if ($apertura) {
                            $data['apertura'] = [
                                'numero' => $apertura->numero,
                                'id_tipo' => $apertura->id_tipo,
                                'mes' => ['descripcion' => $apertura->mes->descripcion],
                                'año' => $apertura->año
                            ];

                            Log::info('Datos de apertura a enviar:', [
                                'apertura' => $data['apertura'],
                                'numero_apertura' => $apertura->numero,
                                'tipo_apertura' => $apertura->id_tipo,
                                'mes' => $apertura->mes->descripcion,
                                'año' => $apertura->año
                            ]);
                        }
                    }

                    // Registrar documento usando el servicio
                    try {
                        $resultado = $this->registroDocService->guardarDocumento($data);
                        
                        // Log adicional para verificar la respuesta
                        Log::info('Respuesta del servicio guardarDocumento:', [
                            'resultado' => $resultado
                        ]);
                        
                        // Verifica si el resultado indica un error
                        if (isset($resultado['error']) || (isset($resultado['status']) && $resultado['status'] === 'error')) {
                            throw new \Exception(isset($resultado['mensaje']) ? $resultado['mensaje'] : 'Error desconocido al actualizar el documento');
                        }
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Error específico al registrar documento: ' . $e->getMessage(), [
                            'parte_id' => $parte->id
                        ]);
                        $this->notify('error', 'No se puede registrar el movimiento: ' . $e->getMessage());
                        return;
                    }

                    // Registro de voucher para pago parcial
                    if ($this->pagado == '2') {  // PAGO PARCIAL
                        try {
                            Log::info('Iniciando registro de voucher para pago parcial', [
                                'parte_id' => $parte->id,
                                'monto_total' => $parte->importe_cobrar,
                                'monto_pagado' => $this->montoPagado
                            ]);

                            $dataVoucher = [
                                'APERTURA' => $apertura->id,
                                'MONEDA' => 'PEN',
                                'DATOS' => [
                                    [
                                        'OBSERVACION' => 'Parte diario de maquinaria - ' . $this->numero,
                                        'CUENTA' => 44,
                                        'DOCUMENTO' => $parte->id,
                                        'MONTO' => $this->montoPagado,
                                        'NUMERO DE OPERACIÓN' => null
                                    ]
                                ],
                                'TOTAL' => $this->montoPagado,
                                'TIPOMOVIENTO' => 'CXC',
                                'FECHA' => $parte->fecha_inicio
                            ];

                            Log::info('Datos del voucher preparados:', [
                                'apertura_id' => $dataVoucher['APERTURA'],
                                'total' => $dataVoucher['TOTAL'],
                                'tipo_movimiento' => $dataVoucher['TIPOMOVIENTO'],
                                'fecha' => $dataVoucher['FECHA']
                            ]);

                            // Registrar el voucher
                            $resultado = $this->RegistroVauchers->guardarVaucher($dataVoucher);

                            Log::info('Voucher registrado exitosamente', [
                                'resultado' => $resultado,
                                'parte_id' => $parte->id
                            ]);

                        } catch (\Exception $e) {
                            Log::error('Error al registrar voucher de pago parcial', [
                                'error' => $e->getMessage(),
                                'parte_id' => $parte->id,
                                'monto_pagado' => $this->montoPagado,
                                'stack_trace' => $e->getTraceAsString()
                            ]);

                            throw $e; // Re-lanzar la excepción para que sea manejada por el try-catch principal
                        }
                    }
                } else {
                    $parte = ParteDiario::findOrFail($this->id);
                    
                    // Validación para impedir editar un parte diario con pago parcial
                    if ($parte->estado_pago == '2') {
                        $this->notify('error', 'No se puede editar un parte diario con pago parcial.');
                        Log::warning('Intento de editar un parte diario con pago parcial', [
                            'id_parte' => $this->id,
                            'estado_pago' => $parte->estado_pago,
                            'usuario' => Auth::user()->id
                        ]);
                        return;
                    }
                    
                    
                    $parte->update($datos);
                    $mensaje = 'Parte diario actualizado correctamente!';
                    
                    Log::info('Parte diario actualizado con ID: ' . $this->id);
                    
                    // Obtener el ID del documento asociado al parte diario
                    $documentoId = $this->obtenerIdDocumento('0000', $this->numeroAnterior, $parte->entidad_id);
                    
                    Log::info('ID del documento obtenido para actualización', [
                        'parte_id' => $parte->id,
                        'numero_parte' => $parte->numero_parte,
                        'entidad_id' => $parte->entidad_id,
                        'documento_id' => $documentoId
                    ]);
                    
                    // Actualizar el registro de documento para el parte editado
                    $data = [
                        'idDocumento' => $documentoId,
                        'tipoDocumento' => 82,
                        'tipoDocDescripcion' => 'Parte Diario',
                        'serieNumero1' => '0000',
                        'serieNumero2' => $parte->numero_parte,
                        'tipoDocId' => $this->tipoDocId,
                        'docIdent' => $this->codigoEntidad,
                        'entidad' => $this->cliente,
                        'monedaId' => 'PEN',
                        'tasaIgvId' => 'No Gravado',
                        'fechaEmi' => $parte->fecha_inicio,
                        'fechaVen' => $parte->fecha_fin,
                        'basImp' => 0,
                        'igv' => 0,
                        'noGravado' => $parte->importe_cobrar,
                        'precio' => $parte->importe_cobrar,
                        'observaciones' => 'Parte diario de maquinaria - ' . $parte->numero_parte,
                        'user' => Auth::user()->id,
                        'productos' => [
                            [
                                'codigoProducto' => 'B6DD38',
                                'productoSeleccionado' => 'GENERAL',
                                'tasaImpositiva' => 0,
                                'observacion' => null,
                                'cantidad' => 1,
                                'precioUnitario' => $parte->importe_cobrar,
                                'total' => $parte->importe_cobrar,
                                'CC' => null,
                                'producto' => 'GENERAL'
                            ]
                        ],
                        'origen' => $this->pagado == '0' ? 'editar_cxc' : ($this->pagado == '2' ? 'editar_cxc' : 'editar_ingreso'),
                        'cuenta' => 44, // Cuenta por defecto para ingresos
                        'cod_operacion' => null,
                        'actualizar' => true // Flag para indicar que es una actualización
                    ];

                    Log::info('Datos a enviar al servicio para actualización:', [
                        'data' => $data,
                        'estado_pago' => $this->pagado,
                        'parte_id' => $parte->id,
                        'numero_parte' => $parte->numero_parte,
                        'importe' => $parte->importe_cobrar,
                        'documento_id' => $documentoId
                    ]);

                    // Solo incluir datos de apertura si está pagado o pago parcial
                    if ($this->pagado == '1' || $this->pagado == '2') {
                        $fecha = Carbon::parse($this->fecha);
                        $apertura = Apertura::where('id_tipo', 10) // Caja partes
                            ->where('fecha', $fecha->format('Y-m-d'))
                            ->first();

                        if ($apertura) {
                            $data['apertura'] = [
                                'numero' => $apertura->numero,
                                'id_tipo' => $apertura->id_tipo,
                                'mes' => ['descripcion' => $apertura->mes->descripcion],
                                'año' => $apertura->año
                            ];

                            Log::info('Datos de apertura a enviar para actualización:', [
                                'apertura' => $data['apertura'],
                                'numero_apertura' => $apertura->numero,
                                'tipo_apertura' => $apertura->id_tipo,
                                'mes' => $apertura->mes->descripcion,
                                'año' => $apertura->año
                            ]);
                        }
                    }

                    
                    // Actualizar el documento usando el servicio
                    try {
                        $resultado = $this->registroDocService->guardarDocumento($data);
                        
                        // Log adicional para verificar la respuesta
                        Log::info('Respuesta del servicio guardarDocumento:', [
                            'resultado' => $resultado
                        ]);
                        
                        // Verifica si el resultado indica un error
                        if (isset($resultado['error']) || (isset($resultado['status']) && $resultado['status'] === 'error')) {
                            throw new \Exception(isset($resultado['mensaje']) ? $resultado['mensaje'] : 'Por que tiene movimientos en caja');
                        }
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Error específico al actualizar documento: ' . $e->getMessage(), [
                            'documento_id' => $documentoId,
                            'parte_id' => $parte->id
                        ]);
                        $this->notify('error', 'No se puede modificar el movimiento: ' . $e->getMessage());
                        return;
                    }

                    // Actualización del voucher para pago parcial
                    if ($this->pagado == '2') {  // PAGO PARCIAL
                        try {
                            Log::info('Iniciando actualización de voucher para pago parcial', [
                                'parte_id' => $parte->id,
                                'monto_total' => $parte->importe_cobrar,
                                'monto_pagado' => $this->montoPagado,
                                'documento_id' => $documentoId
                            ]);

                            $dataVoucher = [
                                'APERTURA' => $apertura->id,
                                'MONEDA' => 'PEN',
                                'DATOS' => [
                                    [
                                        'OBSERVACION' => 'Parte diario de maquinaria - ' . $parte->numero_parte,
                                        'CUENTA' => 44,
                                        'DOCUMENTO' => $documentoId,
                                        'MONTO' => $this->montoPagado,
                                        'NUMERO DE OPERACIÓN' => null
                                    ]
                                ],
                                'TOTAL' => $this->montoPagado,
                                'TIPOMOVIENTO' => 'CXC',
                                'FECHA' => $parte->fecha_inicio,
                                'ACTUALIZAR' => true
                            ];

                            Log::info('Datos del voucher preparados para actualización:', [
                                'apertura_id' => $dataVoucher['APERTURA'],
                                'total' => $dataVoucher['TOTAL'],
                                'tipo_movimiento' => $dataVoucher['TIPOMOVIENTO'],
                                'fecha' => $dataVoucher['FECHA'],
                                'documento_id' => $documentoId
                            ]);

                            // Actualizar el voucher
                            $resultado = $this->RegistroVauchers->guardarVaucher($dataVoucher);

                            Log::info('Voucher actualizado exitosamente', [
                                'resultado' => $resultado,
                                'parte_id' => $parte->id,
                                'documento_id' => $documentoId
                            ]);

                        } catch (\Exception $e) {
                            Log::error('Error al actualizar voucher de pago parcial', [
                                'error' => $e->getMessage(),
                                'parte_id' => $parte->id,
                                'monto_pagado' => $this->montoPagado,
                                'documento_id' => $documentoId,
                                'stack_trace' => $e->getTraceAsString()
                            ]);

                            throw $e; // Re-lanzar la excepción para que sea manejada por el try-catch principal
                        }
                    }
                        
                }

                // Si todo salió bien, confirmar la transacción
                DB::commit();
                Log::info('Transacción confirmada exitosamente');

                // Usamos session()->flash para mensajes de una sola visualización
                session()->flash('mensaje', $mensaje);
                session()->flash('tipo', 'success');

                return redirect()->route('movimientos-maquinaria');

            } catch (\Exception $e) {
                // Si algo salió mal, revertir la transacción
                DB::rollBack();
                Log::error('Error al guardar parte diario: ' . $e->getMessage(), [
                    'exception' => $e,
                    'datos' => $datos ?? null,
                    'origen' => $this->origen,
                    'id' => $this->id
                ]);

                $this->notify('error', 'Error al guardar el parte diario. Por favor, intente nuevamente.');
            }

        } catch (\Exception $e) {
            Log::error('Error en la validación: ' . $e->getMessage());
            $this->notify('error', 'Error al guardar el parte diario. Por favor, intente nuevamente.');
        }
    }

    public function buscarClientes()
    {
        if (strlen($this->busquedaCliente) >= 2) {
            $this->clientes = Entidad::where('descripcion', 'like', '%' . $this->busquedaCliente . '%')
                ->orWhere('id', 'like', '%' . $this->busquedaCliente . '%')
                ->select('id', 'descripcion')
                ->get()
                ->toArray();
            $this->mostrarResultados = true;
        } else {
            $this->clientes = [];
            $this->mostrarResultados = false;
        }
    }

    public function seleccionarCliente($id)
    {
        $clienteSeleccionado = Entidad::find($id);
        if ($clienteSeleccionado) {
            $this->cliente = $clienteSeleccionado->descripcion;
            $this->codigoEntidad = $clienteSeleccionado->id;
            $this->tipoDocId = $clienteSeleccionado->idt02doc;
            $this->busquedaCliente = $clienteSeleccionado->descripcion;
            $this->mostrarResultados = false;
        }
    }

    public function limpiarCliente()
    {
        $this->cliente = '';
        $this->codigoEntidad = '';
        $this->busquedaCliente = '';
        $this->mostrarResultados = false;
        $this->clientes = [];
    }

    public function crearCliente()
    {
        $this->dispatch('openModalEntidad', true);
    }

    public function updatedPagado()
    {
        if ($this->pagado != '2') {
            $this->montoPagado = '';
            $this->montoPendiente = '0.00';
        } else {
            $this->calcularMontoPendiente();
        }
    }

    public function updatedMontoPagado()
    {
        $this->calcularMontoPendiente();
    }

    public function updatedImporteACobrar()
    {
        if ($this->pagado == '2') {
            $this->calcularMontoPendiente();
        }
    }

    public function calcularMontoPendiente()
    {
        if ($this->importeACobrar && $this->montoPagado !== '') {
            try {
                $importe = floatval($this->importeACobrar);
                $pagado = floatval($this->montoPagado);
                
                if ($pagado <= $importe) {
                    $this->montoPendiente = number_format($importe - $pagado, 2, '.', '');
                } else {
                    $this->montoPagado = $importe;
                    $this->montoPendiente = '0.00';
                }
            } catch (\Exception $e) {
                $this->montoPendiente = '0.00';
            }
        } else {
            $this->montoPendiente = $this->importeACobrar ?: '0.00';
        }
    }

    /**
     * Formatea una hora del formato HH:MM:SS a HH:MM
     *
     * @param string $hora
     * @return string
     */
    private function formatearHora($hora)
    {
        try {
            // Si la hora ya está en formato HH:MM, devolverla tal cual
            if (strlen($hora) === 5) {
                return $hora;
            }
            
            // Si la hora tiene formato HH:MM:SS, eliminar los segundos
            if (strlen($hora) === 8) {
                return substr($hora, 0, 5);
            }
            
            // Si es un objeto Carbon, formatearlo correctamente
            if ($hora instanceof Carbon) {
                return $hora->format('H:i');
            }
            
            // Intentar convertir a Carbon y formatear
            return Carbon::parse($hora)->format('H:i');
        } catch (\Exception $e) {
            Log::error('Error al formatear hora: ' . $e->getMessage(), [
                'hora' => $hora,
                'tipo' => gettype($hora)
            ]);
            // En caso de error, devolver la hora original como string
            return (string)$hora;
        }
    }

    /**
     * Obtiene el ID del documento asociado a un parte diario
     *
     * @param string $serie Serie del documento (ej: '0000')
     * @param string $numero Número del documento
     * @param string $entidadId ID de la entidad (código del cliente)
     * @return int|null ID del documento o null si no se encuentra
     */
    private function obtenerIdDocumento($serie, $numero, $entidadId)
    {
        try {
            // Buscar el documento con tipo 82 (Parte Diario), serie, número y entidad específicos
            $documento = \App\Models\Documento::where('id_t10tdoc', '82') // Tipo de documento Parte Diario
                ->where('serie', $serie)
                ->where('numero', $numero)
                ->where('id_entidades', $entidadId)
                ->first();
            
            Log::info('Búsqueda de documento para parte diario', [
                'serie' => $serie,
                'numero' => $numero,
                'entidad_id' => $entidadId,
                'documento_encontrado' => $documento ? 'sí' : 'no',
                'documento_id' => $documento ? $documento->id : null
            ]);
            
            return $documento ? $documento->id : null;
        } catch (\Exception $e) {
            Log::error('Error al obtener ID del documento: ' . $e->getMessage(), [
                'serie' => $serie,
                'numero' => $numero,
                'entidad_id' => $entidadId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    public function render()
    {
        return view('livewire.parte-diario-maquinaria');
    }
} 