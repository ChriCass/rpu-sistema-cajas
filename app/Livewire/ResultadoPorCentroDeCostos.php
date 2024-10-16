<?php

namespace App\Livewire;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\CentroDeCostos;

use Livewire\Component;

class ResultadoPorCentroDeCostos extends Component
{
    public $años;
    public $año;
    public $CC;
    public $centroDeCosto;
    public $movimientos;
    public $movimientos1;
    public $exportarExcel;
    public $totalesIngresos;
    public $totalesEgresos;

    public function mount()
    {
        // Obtener el año actual y dos años más
        $currentYear = now()->year;
        $this->años = [
            $currentYear,
            $currentYear + 1,
            $currentYear + 2
        ];
        $this -> CC = CentroDeCostos::all();
    }

    public function obtenerMovimientos($maymen)
    {

        if($maymen == 1){
            $mayormenor = '>';
        }else{
            $mayormenor = '<';
        }

        
        if($this -> centroDeCosto <> ''){
            $centrodecosto = "and CO2.id_centrodecostos = '".$this -> centroDeCosto."' ";
            Log::info('Centro de costo: '.$centrodecosto);
        }else{
            $centrodecosto = '';
        }

        $movimientos =   DB::select("
        SELECT 
            familias.descripcion AS familia_descripcion,
            subfamilias.desripcion AS subfamilia_descripcion,
            CO2.descripcion AS detalle_descripcion,
            SUM(IF(MONTH(CO2.fec) = '1', monto, 0)) AS enero,
            SUM(IF(MONTH(CO2.fec) = '2', monto, 0)) AS febrero,
            SUM(IF(MONTH(CO2.fec) = '3', monto, 0)) AS marzo,
            SUM(IF(MONTH(CO2.fec) = '4', monto, 0)) AS abril,
            SUM(IF(MONTH(CO2.fec) = '5', monto, 0)) AS mayo,
            SUM(IF(MONTH(CO2.fec) = '6', monto, 0)) AS junio,
            SUM(IF(MONTH(CO2.fec) = '7', monto, 0)) AS julio,
            SUM(IF(MONTH(CO2.fec) = '8', monto, 0)) AS agosto,
            SUM(IF(MONTH(CO2.fec) = '9', monto, 0)) AS septiembre,
            SUM(IF(MONTH(CO2.fec) = '10', monto, 0)) AS octubre,
            SUM(IF(MONTH(CO2.fec) = '11', monto, 0)) AS noviembre,
            SUM(IF(MONTH(CO2.fec) = '12', monto, 0)) AS diciembre
        FROM (
            SELECT 
                CO1.id_apertura,
                CO1.fec,
                CO1.mov,
                id_documentos,
                detalle.id_familias,
                detalle.id_subfamilia,
                detalle.descripcion,
                CO1.id_entidades,
                CO1.numero,
                CO1.monto,
                CO1.glosa,
                CO1.id_centroDeCostos
            FROM (                
                SELECT 
                    aperturas.id_tipo,
                    movimientosdecaja.fec,
                    movimientosdecaja.id_apertura,
                    movimientosdecaja.mov,
                    movimientosdecaja.id_documentos,
                    INN1.id_detalle,
                    documentos.id_entidades,
                    CONCAT(documentos.serie, '-', documentos.numero) AS numero,
                    id_cuentas,
                    IF(id_dh = '1', monto, monto * -1) AS monto,
                    glosa,
                    id_centroDeCostos
                FROM 
                    movimientosdecaja
                LEFT JOIN 
                    documentos ON movimientosdecaja.id_documentos = documentos.id
                LEFT JOIN 
                    (SELECT id_referencia, id_detalle, id_centroDeCostos 
                    FROM d_detalledocumentos 
                    LEFT JOIN l_productos ON d_detalledocumentos.id_producto = l_productos.id) INN1 
                ON documentos.id = INN1.id_referencia
                LEFT JOIN 
                    aperturas ON movimientosdecaja.id_apertura = aperturas.id
                WHERE 
                    id_libro in ('1','2') 
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
        where monto ".$mayormenor." '0' ".$centrodecosto." and year(fec) = ? 
        GROUP BY 
            familias.descripcion, 
            subfamilias.desripcion, 
            CO2.descripcion
        order by familia_descripcion

    ", [$this->año]);

    Log::info('movimientos', ['movimientos'=> $movimientos]);
        return $movimientos;
    }

    public function sumatoriaMeses($movimientos){
        $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        $Sumatoria = [];
        $movimientosArray = $movimientos->toArray();
        foreach ($meses as $mes) {
            $sum = 0;
            foreach ($movimientosArray as $movimiento) {
                $movimientoArray1 = (array) $movimiento;
                $sum += $movimientoArray1[$mes];
            }
            $Sumatoria[$mes] = $sum;
        }
        return $Sumatoria;
    }

    public function procesarReporte()
    {
        try {
            // Calcular los saldos y obtener los movimientos
            $this->exportarExcel = true;
            // Obtener los movimientos del mes seleccionado
            $movimientos = collect($this->obtenerMovimientos(1));
            $this -> totalesIngresos = $this -> sumatoriaMeses($movimientos);
            $this->movimientos = $movimientos;
            $movimientos1 = collect($this->obtenerMovimientos(2));
            $this->movimientos1 = $movimientos1;
            $this -> totalesEgresos = $this -> sumatoriaMeses($movimientos1);
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
        return view('livewire.resultado-por-centro-de-costos')->layout('layouts.app');
    }
}
