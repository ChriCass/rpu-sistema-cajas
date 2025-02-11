<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegistroComprasVentasService 
{
    public function RComprasVentas($libro,$mes,$anno) {
        $query = "select distinct fechaEmi,fechaVen,tabla10_tipodecomprobantedepagoodocumento.descripcion as tdoc,serie,documentos.numero,tabla02_tipodedocumentodeidentidad.abreviado as id_t02tcom,id_entidades,entidades.descripcion as rz,tasas_igv.tasa,
                round(if(id_t10tdoc = '07',basImp *-1,basImp),2) as basImp,round(if(id_t10tdoc = '07',IGV *-1,IGV),2) as IGV,
                noGravadas,otroTributo,round(if(id_t10tdoc = '07',precio *-1,precio),2) as precio,detraccion,montoNeto,id_t04tipmon,d2.descripcion,serieMod,
                numeroMod,observaciones from movimientosdecaja 
                left join documentos on movimientosdecaja.id_documentos = documentos.id 
                left join entidades on documentos.id_entidades = entidades.id
                left join tasas_igv on documentos.id_tasasIgv = tasas_igv.id
                left join tabla10_tipodecomprobantedepagoodocumento on documentos.id_t10tdoc = tabla10_tipodecomprobantedepagoodocumento.id
                left join tabla10_tipodecomprobantedepagoodocumento d2 on documentos.id_t10tdocMod = d2.id
                left join tabla02_tipodedocumentodeidentidad on tabla02_tipodedocumentodeidentidad.id = documentos.id_t02tcom
                where id_libro = '{$libro}' and month(fechaEmi) = '{$mes}' and year(fechaEmi) = '{$anno}' order by fechaEmi";
        return DB::select($query);
    }

    public function Totales($registros){
        
        $basImp = 0;
        $IGV = 0;
        $NoGravado = 0;
        $Otri = 0;
        $precio = 0;
        foreach ($registros as $registro){
            $basImp += $registro->basImp;
            $IGV += $registro->IGV;
            $NoGravado += $registro->noGravadas;
            $Otri += $registro->otroTributo;
            $precio += $registro->precio;
        }
        $Totales['basImp'] = $basImp;
        $Totales['IGV'] = $IGV;
        $Totales['NoGravado'] = $NoGravado;
        $Totales['Otri'] = $Otri;
        $Totales['precio'] = $precio;

        return $Totales;
    }    
}
