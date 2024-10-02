<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Mes;
use App\Models\TipoDeCaja;
use App\Models\Apertura;
use App\Models\MovimientoDeCaja;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;



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

        $this->saldo_inicial = MovimientoDeCaja::select(DB::raw("ROUND(SUM(IF(id_dh = '1', monto, monto * -1)), 2) as monto"))
            ->where('id_cuentas', $this->id_caja)
            ->where('fec', '<', $fecha_apertura)
            ->value('monto'); // Esto obtiene el valor directo de la consulta

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
            IF(id_dh = '2', monto, monto * -1) AS monto,
            glosa
        FROM 
            movimientosdecaja
        LEFT JOIN 
            documentos ON movimientosdecaja.id_documentos = documentos.id
        LEFT JOIN 
			(select id_referencia,id_detalle from d_detalledocumentos left join l_productos on d_detalledocumentos.id_producto = l_productos.id) 
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
    ", [$this->id_caja, $apertura->id]);

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
    }

    public function render()
    {
        return view('livewire.reporte-caja-view')->layout('layouts.app');
    }
}
