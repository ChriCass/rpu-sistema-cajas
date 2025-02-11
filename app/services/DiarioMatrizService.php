<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DiarioMatrizService {

    public function Diario($mes, $anio){

        $dia = $this -> obtenerUltimoDia($mes, $anio);
        $fecha = $anio."-".$mes."-".$dia;
        $query = "select libros.descripcion as Libro,year(fec) as annio,month(fec) as mes,aperturas.numero as apertura,mov,
                fec,documentos.id_entidades,entidades.descripcion,documentos.id_t10tdoc,documentos.serie,documentos.numero,
                cuentas.descripcion as Cuenta,if(id_dh = '1', monto,0) as debe,if(id_dh = '2', monto,0) as haber,
                numero_de_operacion,glosa from movimientosdecaja 
                left join libros on libros.id = movimientosdecaja.id_libro
                left join aperturas on aperturas.id = movimientosdecaja.id_apertura
                left join documentos on id_documentos = documentos.id
                left join entidades on documentos.id_entidades = entidades.id
                left join cuentas on id_cuentas = cuentas.id
                where fec <= '{$fecha}'";
        return DB::select($query);
    }

    function obtenerUltimoDia($mes, $anio) {
        // Validar que el mes y el año sean válidos
        if ($mes < 1 || $mes > 12 || $anio < 1) {
            return "Fecha inválida";
        }
    
        // Obtener el último día del mes utilizando cal_days_in_month
        $ultimoDia = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
    
        return $ultimoDia;
    }
    
}
