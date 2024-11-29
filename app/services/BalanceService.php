<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BalanceService {

    public function Balance($mes, $anio){

        $dia = $this -> obtenerUltimoDia($mes, $anio);
        $fecha = $anio."-".$mes."-".$dia;
        $query = "select tipodecuenta.descripcion as tipoDeCuenta,cuentas.descripcion as cuenta,debe,haber,round(if(debe - haber > 0,debe - haber,0),2) as sumDebe,round(if(haber - debe > 0,haber - debe ,0),2) as sumHaber 
                from (select id_cuentas,round(sum(if(id_dh = 1,monto,0)),2) as debe,round(sum(if(id_dh = 2,monto,0)),2) as haber from movimientosdecaja where fec <= '{$fecha}' 
                group by id_cuentas) CO1 left join cuentas on CO1.id_cuentas = cuentas.id 
                left join tipodecuenta on cuentas.id_tcuenta = tipodecuenta.id order by cuentas.id_tcuenta";
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
