<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Cuenta;

class BalanceCuentasAnalisisService {

    public function BalanceAnalisis($mes, $anio, $cuenta){

        $dia = $this -> obtenerUltimoDia($mes, $anio);
        $fecha = $anio."-".$mes."-".$dia;
        $idCuenta = $this -> cuentasId($cuenta);
        $query = "select movimientosdecaja.id_documentos,documentos.id_entidades,entidades.descripcion as entidades,tabla10_tipodecomprobantedepagoodocumento.descripcion as tdoc,documentos.serie,documentos.numero,
                cuentas.descripcion as cuenta,sum(if(id_dh = '1',monto,monto*-1)) as monto,documentos.observaciones from movimientosdecaja 
                left join documentos on movimientosdecaja.id_documentos = documentos.id
                left join entidades on documentos.id_entidades = entidades.id
                left join tabla10_tipodecomprobantedepagoodocumento on documentos.id_t10tdoc = tabla10_tipodecomprobantedepagoodocumento.id
                left join cuentas on movimientosdecaja.id_cuentas = cuentas.id
                where fec <= '{$fecha}' and id_cuentas = '{$idCuenta}' group by  movimientosdecaja.id_documentos,
                documentos.id_entidades,
                entidades.descripcion,
                tabla10_tipodecomprobantedepagoodocumento.descripcion,
                documentos.serie,
                documentos.numero,
                cuentas.descripcion,
                documentos.observaciones having sum(if(id_dh = '1',monto,monto*-1)) <> 0";
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

    function cuentasId($cuenta){
        $cuentaId = Cuenta::where('descripcion',$cuenta)->get();
        return $cuentaId[0]['id'];
    }

    function totales($registros){

        $total = 0;
        foreach($registros as $registro){
            $reg = json_encode($registro); // Convierte a JSON
            $decodedReg = json_decode($reg, true); // Decodifica a un arreglo asociativo

            $total += $decodedReg['monto']; 
        }
            
        return $total;

    }
    
}
