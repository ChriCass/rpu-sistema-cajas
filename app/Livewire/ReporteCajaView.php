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
use Barryvdh\DomPDF\Facade\Pdf; // Asegúrate de importar la clase correcta
use App\Exports\CajaExport;
use Maatwebsite\Excel\Facades\Excel;


class ReporteCajaView extends Component
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

    public function procesarReporte()
    {
        Log::info('Iniciando el procesamiento del reporte de caja.');

        // Validar campos requeridos
        if (!$this->año || !$this->mes || !$this->id_caja || !$this->numero) {
            Log::warning('Faltan campos requeridos: año, mes, caja o número.');
            session()->flash('error', 'Todos los campos son requeridos.');
            return;
        }

        Log::info('Campos validados correctamente.', [
            'año' => $this->año,
            'mes' => $this->mes,
            'id_caja' => $this->id_caja,
            'numero' => $this->numero,
        ]);

        $desc = TipoDeCaja::select('descripcion')
            ->where('id', $this->id_caja)
            ->get()
            ->toarray();

        $moneda = TipoDeCaja::select('t04_tipodemoneda')
            ->where('id', $this->id_caja)
            ->get()
            ->toarray(); 

        $idcuenta = Cuenta::select('id')
            ->where('descripcion', $desc[0]['descripcion'])
            ->get()
            ->toarray();

        // Obtener la apertura en base a los datos seleccionados
        $apertura = Apertura::where('id_tipo', $this->id_caja)
            ->where('id_mes', $this->mes)
            ->where('año', $this->año)
            ->where('numero', (int) $this->numero)
            ->first();

        Log::info('apertura', ['apertura' => $apertura]);


        if (!$apertura) {
            Log::warning('No se encontró la apertura con los datos proporcionados.');
            session()->flash('error', 'La apertura no existe.');
            return;
        }



        Log::info('Apertura encontrada.', ['apertura_id' => $apertura->id, 'fecha_apertura' => $apertura->fecha]);

        // Obtener la fecha de la apertura
        $fecha_apertura = $apertura->fecha;

        // Calcular el saldo inicial: Suma los montos anteriores a la fecha de apertura
        Log::info('Calculando saldo inicial antes de la fecha de apertura.', ['fecha_apertura' => $fecha_apertura]);
        /*      $this->saldo_inicial = DB::select("
        SELECT if(id_dh = '1', monto, monto * - 1) AS saldo_inicial
        FROM  movimientosdecaja
        WHERE id_cuentas = ? AND fec < ?
    ", [$this->id_caja, $fecha_apertura])[0]->saldo_inicial ?? 0; */
        Log::info('id caja y fec valores', ['id caja' => $this->id_caja, 'fec' => $fecha_apertura]);

        if($moneda[0]['t04_tipodemoneda'] == 'USD'){
            $monto = "montodo";
        }else{
            $monto = "monto";
        }


        $this->saldo_inicial = MovimientoDeCaja::select(DB::raw("ROUND(SUM(IF(id_dh = '1',".$monto.", ".$monto." * -1)), 2) as monto"))
            ->where('id_cuentas', $idcuenta[0]['id'])
            ->where('fec', '<', $fecha_apertura)
            ->value($monto); // Esto obtiene el valor directo de la consulta

        $this->saldo_inicial = number_format($this->saldo_inicial ?? 0, 2, '.', '');

        Log::info('Saldo inicial calculado.', ['saldo_inicial' => $this->saldo_inicial]);
        // Obtener los movimientos relacionados con la apertura (RESPETANDO LA NUEVA CONSULTA)
        Log::info('Obteniendo movimientos de caja relacionados con la apertura.', ['apertura_id' => $apertura->id]);
        $raw_movimientos = DB::select("
         SELECT 
    mov AS id_documentos,
    familias.descripcion AS familia_descripcion,
    subfamilias.desripcion AS subfamilia_descripcion,
    CO2.descripcion AS detalle_descripcion,
    entidades.descripcion AS descripcion,
    numero, 
    monto, 
    glosa
FROM (
    SELECT 
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
	WHERE 
		id_cuentas <> ?
		AND id_apertura = ?
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
ORDER BY 
    CO2.mov;
    ", [$idcuenta[0]['id'], $apertura->id]);

        // Convertir a una colección
        $this->movimientos = collect($raw_movimientos);


        Log::info('Movimientos obtenidos.', ['total_movimientos' => $this->movimientos]);

        // Calcular la variación (sumatoria de los montos de los movimientos)
        $this->variacion = $this->movimientos->sum('monto');
        Log::info('Variación calculada.', ['variacion' => $this->variacion]);

        // Calcular el saldo final
        $this->saldo_final = $this->saldo_inicial + $this->variacion;
        Log::info('Saldo final calculado.', ['saldo_final' => $this->saldo_final]);

        session()->flash('message', 'Reporte procesado correctamente.');
        $this->exportarExcel = true;
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
            $pdf = Pdf::loadView('pdf.reporte_caja', $datos)->setPaper('a3', 'landscape');
        
            // Retornar el PDF como descarga
            return response()->streamDownload(
                fn() => print($pdf->output()),
                'reporte_caja.pdf'
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
            return Excel::download(new CajaExport($this->movimientos), 'caja.xlsx');
        } catch (\Exception $e) {
            Log::info("Error al exportar la caja: " . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al exportar el archivo.');
        }
    }
    
    

    public function render()
    {
        return view('livewire.reporte-caja-view')->layout('layouts.app');
    }
}
