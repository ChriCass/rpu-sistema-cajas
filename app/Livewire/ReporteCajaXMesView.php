<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Mes;
use App\Models\TipoDeCaja;
use App\Models\Apertura;
use App\Models\Cuenta;
use App\Models\MovimientoDeCaja;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Exports\CajaxMesExport;
 
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteCajaXMesView extends Component
{
    public $cajas;
    public $meses;
    public $años;

    public $mes;
    public $id_caja;
    public $numero;
    public $año;

    public $saldo_inicial;
    public $variacion;
    public $saldo_final;
    public $movimientos;
    public $movimientos_encontrados = false;
    public $exportarExcel =false;
    public function mount()
    {
        $this->meses = Mes::all();
        $this->cajas = TipoDeCaja::all();

        // Obtener el año actual y dos años más
        $currentYear = now()->year;
        $this->años = [
            $currentYear,
            $currentYear + 1,
            $currentYear + 2
        ];

        $this->movimientos = collect(); // Iniciar vacío
    }

    public function obtenerSaldoInicial()
    {


        $desc = TipoDeCaja::select('descripcion')
            ->where('id', $this->id_caja)
            ->get()
            ->toarray();

        $idcuenta = Cuenta::select('id')
            ->where('descripcion', $desc[0]['descripcion'])
            ->get()
            ->toarray();
        // Obtener el saldo inicial sumando los montos antes del mes y año seleccionados
        $saldo_inicial = MovimientoDeCaja::where('id_cuentas', $idcuenta[0]['id'])
            ->whereDate('fec', '<', $this->año . '-' . $this->mes . '-01')
            ->sum(DB::raw("IF(id_dh = '1', monto, monto * -1)"));

        return $saldo_inicial;
    }

    // Método para obtener los movimientos
    public function obtenerMovimientos()
    {


        $desc = TipoDeCaja::select('descripcion')
            ->where('id', $this->id_caja)
            ->get()
            ->toarray();

        $idcuenta = Cuenta::select('id')
            ->where('descripcion', $desc[0]['descripcion'])
            ->get()
            ->toarray();

        $movimientos =   DB::select("
        SELECT 
            aperturas.numero as numero_apertura,
            aperturas.fecha,
            mov AS id_documentos,
            familias.descripcion AS familia_descripcion,
            subfamilias.desripcion AS subfamilia_descripcion,
            CO2.descripcion AS detalle_descripcion,
            entidades.descripcion AS descripcion,
            CO2.numero as numero_serie, 
            monto, 
            glosa
        FROM (
            SELECT 
                CO1.id_apertura,
                CO1.mov,
                id_documentos,
                detalle.id_familias,
                detalle.id_subfamilia,
                detalle.descripcion,
                CO1.id_entidades,
                CO1.numero,
                CO1.monto,
                CO1.glosa 
            FROM (
                SELECT 
                    movimientosdecaja.id_apertura,
                    movimientosdecaja.mov,
                    movimientosdecaja.id_documentos,
                    INN1.id_detalle,
                    documentos.id_entidades,
                    CONCAT(documentos.serie, '-', documentos.numero) AS numero,
                    IF(id_dh = '2', monto, monto * -1) AS monto,
                    glosa
                FROM 
                    movimientosdecaja
                LEFT JOIN 
                    documentos ON movimientosdecaja.id_documentos = documentos.id
                LEFT JOIN 
                    (select id_referencia, id_detalle from d_detalledocumentos 
                     left join l_productos on d_detalledocumentos.id_producto = l_productos.id) INN1 
                    ON documentos.id = INN1.id_referencia
                WHERE 
                    id_cuentas <> ? 
                    AND id_apertura IS NOT NULL
            ) CO1
            LEFT JOIN 
                detalle ON CO1.id_detalle = detalle.id
        ) CO2
        LEFT JOIN 
            familias ON CO2.id_familias = familias.id
        LEFT JOIN 
            subfamilias ON CONCAT(CO2.id_familias, CO2.id_subfamilia) = CONCAT(subfamilias.id_familias, subfamilias.id)
        LEFT JOIN 
            entidades ON CO2.id_entidades = entidades.id
        LEFT JOIN
            aperturas ON CO2.id_apertura = aperturas.id
        WHERE 
            aperturas.id_mes = ? AND aperturas.año = ?
        ORDER BY 
            aperturas.numero
    ", [$idcuenta[0]['id'], $this->mes, $this->año]);

    Log::info('movimientos', ['movimientos'=> $movimientos]);
        return $movimientos;
    }

    // Método para calcular saldos
    public function calcularSaldos()
    {
        // Obtener el saldo inicial
        $this->saldo_inicial = $this->obtenerSaldoInicial();

        // Obtener los movimientos del mes seleccionado
        $movimientos = collect($this->obtenerMovimientos());


        // Calcular la variación (suma de los movimientos del mes)
        $this->variacion = $movimientos->sum('monto');

        // Calcular el saldo final
        $this->saldo_final = $this->saldo_inicial + $this->variacion;

        // Guardar los movimientos para mostrarlos en la tabla
        $this->movimientos = $movimientos;
        $this->movimientos_encontrados = $movimientos->isNotEmpty();
    }

    // Método para procesar el reporte
    public function procesarReporte()
    {
        try {
            // Calcular los saldos y obtener los movimientos
            $this->calcularSaldos();
            $this->exportarExcel = true;
            // Si todo salió bien, mostrar un mensaje de éxito
            session()->flash('message', 'Reporte procesado exitosamente');
        } catch (\Exception $e) {
            // En caso de error, mostrar un mensaje de error
            Log::error('Error procesando reporte: ' . $e->getMessage());
            session()->flash('error', 'Hubo un error al procesar el reporte');
        }
    }

    public function exportCaja()
    {
        try {
            // Verificar si la exportación está permitida
            if (!$this->exportarExcel) {
                session()->flash('error', 'La exportación no está permitida.');
                return;
            }
    
            // Verificar si hay movimientos para exportar
            if (empty($this->movimientos)) {
                session()->flash('error', 'No hay datos para exportar.');
                return;
            }
    
            // Crear la exportación con los datos del procedimiento almacenado
            return Excel::download(new CajaxMesExport($this->movimientos), 'cajaxmes.xlsx');
        } catch (\Exception $e) {
            Log::info("Error al exportar la caja: " . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al exportar el archivo.');
        }
    }

    public function exportarPDF()
    {
        try {
            // Obtener el mes como objeto y luego su descripción
            $descripcion_fecha = Mes::where('id', $this->mes)->first(); 
            $mes = $descripcion_fecha->descripcion;
        
            // Obtener la descripción de la caja
            $desc = TipoDeCaja::select('descripcion')
                ->where('id', $this->id_caja)
                ->first();
        
            // Obtener el ID de la cuenta
            $idcuenta = Cuenta::select('id')
                ->where('descripcion', $desc->descripcion)
                ->first();
        
            $datos = [
                'movimientos' => $this->movimientos,
                'saldo_inicial' => $this->saldo_inicial,
                'variacion' => $this->variacion,
                'saldo_final' => $this->saldo_final,
                'año' => $this->año,
                'mes' => $mes,
                'id_caja' => $desc->descripcion,
            ];
        
            // Generar el PDF a partir de una vista de Blade
            $pdf = Pdf::loadView('pdf.reporte_caja_mes', $datos)->setPaper('a2', 'landscape');
        
            // Retornar el PDF como descarga
            return response()->streamDownload(
                fn() => print($pdf->output()),
                'reporte_caja_mes.pdf'
            );
        } catch (\Exception $e) {
            // Log para registrar el error
            Log::error("Error al exportar el PDF: " . $e->getMessage());
            
            // Retornar un mensaje de error a la sesión
            session()->flash('error', 'Ocurrió un error al generar el PDF.');
            
            // Redirigir o retornar una respuesta, si es necesario
            return redirect()->back();
        }
    }

    public function render()
    {
        return view('livewire.reporte-caja-x-mes-view')->layout('layouts.app');
    }
}
