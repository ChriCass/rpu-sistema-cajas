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
use App\Exports\CajaxAnioExport;
 
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
class ReporteCajaXAnioView extends Component
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
            $currentYear - 1,
            $currentYear,
            $currentYear + 1,
            $currentYear + 2
        ];

        $this->movimientos = collect(); // Iniciar vacío
    }

    // Método para obtener el saldo inicial
    public function obtenerSaldoInicial()
    {

        $moneda = TipoDeCaja::select('t04_tipodemoneda')
            ->where('id', $this->id_caja)
            ->get()
            ->toarray(); 

        if($moneda[0]['t04_tipodemoneda'] == 'USD'){
            $monto = "montodo";
        }else{
            $monto = "monto";
        }

        $desc = TipoDeCaja::select('descripcion')
        ->where('id', $this->id_caja)
        ->get()
        ->toarray();

    $idcuenta = Cuenta::select('id')
        ->where('descripcion', $desc[0]['descripcion'])
        ->get()
        ->toarray();
        // Obtener el saldo inicial sumando los montos antes del año seleccionado
        $saldo_inicial = MovimientoDeCaja::where('id_cuentas',  $idcuenta)
            ->whereDate('fec', '<', $this->año . '-01-01')
            ->sum(DB::raw("IF(id_dh = '1', ".$monto.", ".$monto." * -1)"));

        return $saldo_inicial;
    }

    // Método para obtener los movimientos por año
    public function obtenerMovimientos()
    {

        $moneda = TipoDeCaja::select('t04_tipodemoneda')
            ->where('id', $this->id_caja)
            ->get()
            ->toarray(); 

        if($moneda[0]['t04_tipodemoneda'] == 'USD'){
            $monto = "montodo";
        }else{
            $monto = "monto";
        }

        $desc = TipoDeCaja::select('descripcion')
        ->where('id', $this->id_caja)
        ->get()
        ->toarray();

    $idcuenta = Cuenta::select('id')
        ->where('descripcion', $desc[0]['descripcion'])
        ->get()
        ->toarray();

        $movimientos = DB::select("
             SELECT 
			aperturas.id_mes,
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
                    aperturas.id_tipo,
                    movimientosdecaja.id_apertura,
                    movimientosdecaja.mov,
                    movimientosdecaja.id_documentos,
                    INN1.id_detalle,
                    documentos.id_entidades,
                    CONCAT(documentos.serie, '-', documentos.numero) AS numero,
                    if(id_tip_form = '1',IF(id_dh = '2', ".$monto.", ".$monto." * -1),if(id_tasasIgv='0',IF(id_dh = '2', ".$monto."*(total/(noGravadas)), 
                    (".$monto."*(total/(noGravadas))) * -1),if(id_tasas='0',IF(id_dh = '2', noGravadas*(total/(noGravadas)), (noGravadas*(total/(noGravadas))) * -1),
                    IF(id_dh = '2', (".$monto.")*(total/(basImp)), ((".$monto.")*(total/(basImp))) * -1)))) AS monto,
                    glosa
                FROM 
                    movimientosdecaja
                LEFT JOIN 
                    documentos ON movimientosdecaja.id_documentos = documentos.id
                LEFT JOIN 
                    (select id_referencia,id_detalle,total,id_tasas from d_detalledocumentos left join l_productos on d_detalledocumentos.id_producto = l_productos.id) 
                    INN1 ON documentos.id = INN1.id_referencia
                LEFT JOIN 
                    aperturas on movimientosdecaja.id_apertura = aperturas.id
                WHERE 
                    id_cuentas <> ?
                    AND id_apertura IS NOT NULL 
                    AND id_tipo = ?
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
			aperturas.año = ?
        ORDER BY 
            aperturas.id_mes,aperturas.numero
         ", [$idcuenta[0]['id'],$this->id_caja, $this->año]);

        return collect($movimientos); // Convertir a colección
    }

    // Método para calcular saldos
    public function calcularSaldos()
    {
        // Obtener el saldo inicial
        $this->saldo_inicial = $this->obtenerSaldoInicial();

        // Obtener los movimientos del año seleccionado
        $this->movimientos = $this->obtenerMovimientos();

        // Calcular la variación (suma de los movimientos del año)
        $this->variacion = $this->movimientos->sum('monto');

        // Calcular el saldo final
        $this->saldo_final = $this->saldo_inicial + $this->variacion;

        // Verificar si se encontraron movimientos
        $this->movimientos_encontrados = $this->movimientos->isNotEmpty();
    }

    // Método para procesar el reporte
    public function procesarReporte()
    {
        try {
            // Calcular los saldos y obtener los movimientos
            $this->calcularSaldos();

            $this->exportarExcel = true;
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
            return Excel::download(new CajaxAnioExport($this->movimientos), 'cajaxaño.xlsx');
        } catch (\Exception $e) {
            Log::info("Error al exportar la caja: " . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al exportar el archivo.');
        }
    }
    
    

    public function exportarPDF()
    {
        try {
            
        
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
                'id_caja' => $desc->descripcion,
            ];
        
            // Generar el PDF a partir de una vista de Blade
            $pdf = Pdf::loadView('pdf.reporte_caja_anio', $datos)->setPaper('a2', 'landscape');
        
            // Retornar el PDF como descarga
            return response()->streamDownload(
                fn() => print($pdf->output()),
                'reporte_caja_anio.pdf'
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
        return view('livewire.reporte-caja-x-anio-view')->layout('layouts.app');
    }
}
