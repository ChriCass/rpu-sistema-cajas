<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Carbon;
use App\Models\ParteDiario;
use Illuminate\Support\Facades\DB;

class GraficoVentas extends Component
{
    public $anioSeleccionado;
    public $mesSeleccionado;
    public $todosLosDatos = [];
    
    protected $listeners = ['refreshComponent' => '$refresh'];
    
    public function mount()
    {
        // Inicializar valores predeterminados
        $this->anioSeleccionado = date('Y');
        $this->mesSeleccionado = (int)date('m');
        
        // Cargar datos
        $this->cargarTodosLosDatos();
    }
    
    /**
     * Carga todos los datos disponibles para todos los años y meses
     */
    public function cargarTodosLosDatos()
    {
        // Registrar inicio de carga
        logger('======================================================');
        logger('CARGANDO TODOS LOS DATOS - ' . date('Y-m-d H:i:s'));
        
        try {
            // Reiniciar estructura de datos
            $this->todosLosDatos = [];
            
            // Consulta para obtener todos los partes diarios
            $partesDiarios = ParteDiario::select('fecha_inicio', 'importe_cobrar')
                ->whereNotNull('fecha_inicio')
                ->whereNotNull('importe_cobrar')
                ->orderBy('fecha_inicio', 'asc')
                ->get();
            
            logger("Registros encontrados (total): " . $partesDiarios->count());
            
            // Mostrar algunos ejemplos de los datos encontrados
            if ($partesDiarios->count() > 0) {
                logger("Ejemplos de datos encontrados:");
                $partesDiarios->take(5)->each(function($parte) {
                    logger("  Fecha: {$parte->fecha_inicio}, Importe: {$parte->importe_cobrar}");
                });
            } else {
                logger("ADVERTENCIA: No se encontraron registros en la tabla partes_diarios");
                return;
            }
            
            // Procesar cada parte diario
            foreach ($partesDiarios as $parte) {
                try {
                    // Extraer componentes de la fecha
                    $fecha = Carbon::parse($parte->fecha_inicio);
                    $anio = (string)$fecha->year;
                    $mes = (string)$fecha->month;
                    $dia = (string)$fecha->day;
                    $total = (float)$parte->importe_cobrar;
                    
                    if ($total <= 0) {
                        continue; // Saltamos importes con valor 0 o negativo
                    }
                    
                    // Agrupar datos por año, mes y día
                    if (!isset($this->todosLosDatos[$anio])) {
                        $this->todosLosDatos[$anio] = [];
                    }
                    
                    if (!isset($this->todosLosDatos[$anio][$mes])) {
                        $this->todosLosDatos[$anio][$mes] = [];
                    }
                    
                    // Si ya existe un valor para este día, sumamos al valor existente
                    if (isset($this->todosLosDatos[$anio][$mes][$dia])) {
                        $this->todosLosDatos[$anio][$mes][$dia] += $total;
                    } else {
                        $this->todosLosDatos[$anio][$mes][$dia] = $total;
                    }
                } catch (\Exception $e) {
                    logger("ERROR procesando parte diario: " . $e->getMessage());
                    continue;
                }
            }
            
            // Verificar estructura final de los datos
            logger("Estructura final de datos:");
            foreach ($this->todosLosDatos as $anio => $meses) {
                logger("  Año: $anio");
                foreach ($meses as $mes => $dias) {
                    $totalDias = count($dias);
                    $totalValor = array_sum($dias);
                    logger("    Mes: $mes - $totalDias días - Total: $totalValor");
                }
            }
            
            logger("Datos totales cargados: " . json_encode($this->todosLosDatos));
        } catch (\Exception $e) {
            logger("ERROR en consulta de datos: " . $e->getMessage());
            $this->todosLosDatos = [];
        }
    }
    
    /**
     * Responde al cambio de año en el selector
     */
    public function updatedAnioSeleccionado() 
    {
        try {
            $anio = $this->anioSeleccionado;
            logger("Año actualizado a: {$anio}");
            
            // Obtener datos actualizados
            $datosActualizados = $this->getDatosGrafico();
            logger("Datos obtenidos para año {$anio}: " . json_encode($datosActualizados));
            
            // Notificar al frontend que debe actualizar los gráficos
            $this->dispatch('datosRecargados', $datosActualizados);
            
            // También forzar un refresh del componente para asegurar sincronización
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            logger("Error al actualizar año: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }
    
    /**
     * Responde al cambio de mes en el selector
     */
    public function updatedMesSeleccionado() 
    {
        try {
            $mes = $this->mesSeleccionado;
            $anio = $this->anioSeleccionado;
            logger("Mes actualizado a: {$mes} del año {$anio}");
            
            // Obtener datos actualizados
            $datosActualizados = $this->getDatosGrafico();
            logger("Datos obtenidos para mes {$mes}: " . json_encode($datosActualizados));
            
            // Notificar al frontend que debe actualizar los gráficos
            $this->dispatch('datosRecargados', $datosActualizados);
            
            // También forzar un refresh del componente para asegurar sincronización
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            logger("Error al actualizar mes: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }
    
    /**
     * Obtiene los datos para los gráficos en el formato adecuado
     */
    public function getDatosGrafico()
    {
        try {
            // Asegurarse de que anioSeleccionado y mesSeleccionado sean valores enteros válidos
            $anio = (int)$this->anioSeleccionado;
            $mes = (int)$this->mesSeleccionado;
            
            // Validar valores
            if ($anio <= 0 || $mes <= 0 || $mes > 12) {
                logger("Valores inválidos para año ($anio) o mes ($mes)");
                $anio = (int)date('Y');
                $mes = (int)date('m');
                // Actualizar propiedades para sincronizar
                $this->anioSeleccionado = $anio;
                $this->mesSeleccionado = $mes;
            }
            
            $diasEnMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
            
            // Asegurarse de que anio y mes son strings para acceder al array multidimensional
            $anioStr = (string) $anio;
            $mesStr = (string) $mes;
            
            // Verificar que tenemos datos para este año y mes
            $tieneValores = false;
            
            // Verificar si existen los datos para este año y mes
            $existenDatos = isset($this->todosLosDatos[$anioStr]) && 
                          isset($this->todosLosDatos[$anioStr][$mesStr]);
            
            logger("Verificando datos para año=$anioStr, mes=$mesStr: " . ($existenDatos ? 'Existen' : 'No existen'));
            
            // Preparar arrays para todos los gráficos
            $datosImporteDiario = [];
            $datosTendenciaSemanal = [];
            $datosComparacionMensual = [];
            $datosRendimientoAnual = [];
            
            // 1. Datos para el gráfico principal: importe diario
            for ($dia = 1; $dia <= $diasEnMes; $dia++) {
                $diaStr = (string) $dia;
                $valor = 0;
                
                // Verificar si hay datos para este día
                if ($existenDatos && isset($this->todosLosDatos[$anioStr][$mesStr][$diaStr])) {
                    $valor = (float) $this->todosLosDatos[$anioStr][$mesStr][$diaStr];
                    if ($valor > 0) {
                        $tieneValores = true;
                    }
                }
                
                $datosImporteDiario[] = [
                    'x' => $dia, // Eje X (día)
                    'y' => $valor // Eje Y (importe)
                ];
            }
            
            // 2. Datos para tendencia semanal (agrupar por semanas)
            $semanasEnMes = ceil($diasEnMes / 7);
            $totalPorSemana = array_fill(0, $semanasEnMes, 0);
            
            // Calcular totales por semana
            if ($existenDatos) {
                foreach ($this->todosLosDatos[$anioStr][$mesStr] as $dia => $valor) {
                    $diaInt = (int)$dia;
                    $semana = floor(($diaInt - 1) / 7);
                    if ($semana < $semanasEnMes) {
                        $totalPorSemana[$semana] += (float)$valor;
                    }
                }
            }
            
            // Crear datos para el gráfico
            for ($i = 0; $i < $semanasEnMes; $i++) {
                $datosTendenciaSemanal[] = [
                    'x' => 'Semana ' . ($i + 1),
                    'y' => $totalPorSemana[$i]
                ];
            }
            
            // 3. Datos para comparación mensual (últimos 6 meses)
            $mesesAComparar = 6;
            $nombresMeses = [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 
                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            ];
            
            // Iterar sobre los últimos 6 meses
            for ($i = 0; $i < $mesesAComparar; $i++) {
                $mesComparar = $mes - $i;
                $anioComparar = $anio;
                
                // Ajustar para meses anteriores a enero
                if ($mesComparar <= 0) {
                    $mesComparar += 12;
                    $anioComparar -= 1;
                }
                
                $mesCompararStr = (string)$mesComparar;
                $anioCompararStr = (string)$anioComparar;
                
                $totalMes = 0;
                
                // Verificar si hay datos para este mes
                if (isset($this->todosLosDatos[$anioCompararStr]) && 
                    isset($this->todosLosDatos[$anioCompararStr][$mesCompararStr])) {
                    
                    foreach ($this->todosLosDatos[$anioCompararStr][$mesCompararStr] as $valor) {
                        $totalMes += (float)$valor;
                    }
                }
                
                $nombreMes = $nombresMeses[$mesComparar] ?? "Mes $mesComparar";
                $datosComparacionMensual[] = [
                    'x' => $nombreMes . ' ' . $anioComparar,
                    'y' => $totalMes
                ];
            }
            
            // Invertir para mostrar los meses de más antiguo a más reciente
            $datosComparacionMensual = array_reverse($datosComparacionMensual);
            
            // 4. Datos para rendimiento anual (ventas por mes del año actual)
            for ($mesAnual = 1; $mesAnual <= 12; $mesAnual++) {
                $mesAnualStr = (string)$mesAnual;
                $totalMesAnual = 0;
                
                // Verificar si hay datos para este mes del año
                if (isset($this->todosLosDatos[$anioStr]) && 
                    isset($this->todosLosDatos[$anioStr][$mesAnualStr])) {
                    
                    foreach ($this->todosLosDatos[$anioStr][$mesAnualStr] as $valor) {
                        $totalMesAnual += (float)$valor;
                    }
                }
                
                $nombreMes = $nombresMeses[$mesAnual] ?? "Mes $mesAnual";
                $datosRendimientoAnual[] = [
                    'x' => $nombreMes,
                    'y' => $totalMesAnual
                ];
            }
            
            $tituloMes = $nombresMeses[$mes] ?? "Mes $mes";
            $tituloBase = $tieneValores 
                ? "Importes de Partes Diarios - {$tituloMes} del $anio" 
                : "No hay datos para {$tituloMes} del $anio";
            
            $resultado = [
                'importeDiario' => [
                    'datos' => $datosImporteDiario,
                    'titulo' => $tituloBase
                ],
                'tendenciaSemanal' => [
                    'datos' => $datosTendenciaSemanal,
                    'titulo' => "Tendencia Semanal - {$tituloMes} del $anio"
                ],
                'comparacionMensual' => [
                    'datos' => $datosComparacionMensual,
                    'titulo' => "Comparación Últimos 6 Meses - hasta {$tituloMes} del $anio"
                ],
                'rendimientoAnual' => [
                    'datos' => $datosRendimientoAnual,
                    'titulo' => "Rendimiento Mensual - Año $anio"
                ]
            ];
            
            logger("Datos preparados para los gráficos generados correctamente");
            
            return $resultado;
        } catch (\Exception $e) {
            logger("Error al obtener datos para el gráfico: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            
            // Devolver estructura vacía pero válida
            return [
                'importeDiario' => [
                    'datos' => [],
                    'titulo' => 'No hay datos disponibles'
                ],
                'tendenciaSemanal' => [
                    'datos' => [],
                    'titulo' => 'Tendencia Semanal - No hay datos'
                ],
                'comparacionMensual' => [
                    'datos' => [],
                    'titulo' => 'Comparación Mensual - No hay datos'
                ],
                'rendimientoAnual' => [
                    'datos' => [],
                    'titulo' => 'Rendimiento Anual - No hay datos'
                ]
            ];
        }
    }
    
    public function render()
    {
        // Obtener datos ya formateados para el gráfico
        $datosGrafico = $this->getDatosGrafico();
        
        return view('livewire.grafico-ventas', [
            'anioSeleccionado' => $this->anioSeleccionado,
            'mesSeleccionado' => $this->mesSeleccionado,
            'datosGrafico' => $datosGrafico
        ]);
    }
}
