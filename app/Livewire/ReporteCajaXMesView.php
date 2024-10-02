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
            CO2.mov
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

            // Si todo salió bien, mostrar un mensaje de éxito
            session()->flash('message', 'Reporte procesado exitosamente');
        } catch (\Exception $e) {
            // En caso de error, mostrar un mensaje de error
            Log::error('Error procesando reporte: ' . $e->getMessage());
            session()->flash('error', 'Hubo un error al procesar el reporte');
        }
    }

    public function render()
    {
        return view('livewire.reporte-caja-x-mes-view')->layout('layouts.app');
    }
}
