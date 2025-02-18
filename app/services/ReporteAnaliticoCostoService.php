<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReporteAnaliticoCostoService {

    public function AnalisisCostos($libro,$año,$mes,$detalleId,$CCid){

        if (!empty($mes)){
            $mesCon = "and month(fec) = '$mes'";
        }else{
            $mesCon = "";
        }

        if (!empty($CCid)){
            $CCCon = "and CO2.id_centroDeCostos = '$CCid'";
        }else{
            $CCCon = "";
        }

        $query = "SELECT 
                fec,
                familias.descripcion AS familia_descripcion,
                subfamilias.desripcion AS subfamilia_descripcion,
                CO2.descripcion AS detalle_descripcion,
                entidades.descripcion,serie,numero,monto,glosa,t_centrodecostos.descripcion as CC
            FROM (
                SELECT 
                    CO1.id_detalle,
                    CO1.id_apertura,
                    CO1.id_libro,
                    CO1.fec,
                    CO1.mov,
                    CO1.id_documentos,
                    detalle.id_familias,
                    detalle.id_subfamilia,
                    detalle.descripcion,
                    CO1.id_entidades,
                    CO1.serie,
                    CO1.numero,
                    CO1.monto,
                    CO1.glosa,
                    CO1.id_centroDeCostos
                FROM (
                    SELECT 
                    aperturas.id_tipo,
                    movimientosdecaja.id_libro,
                    movimientosdecaja.fec,
                    movimientosdecaja.id_apertura,
                    movimientosdecaja.mov,
                    movimientosdecaja.id_documentos,
                    INN1.id_detalle,
                    INN1.id_producto,
                    documentos.id_entidades,
                    documentos.serie,documentos.numero,
                    id_cuentas,
                    if(id_tip_form = '1',IF(id_dh = '1', monto, monto * -1),if(id_tasasIgv='0',IF(id_dh = '1', monto*(total/(noGravadas)), 
                    (monto*(total/(noGravadas))) * -1),if(id_tasas='0',IF(id_dh = '1', monto*(total/(noGravadas)), (monto*(total/(noGravadas))) * -1),
                    IF(id_dh = '1', (monto)*(total/(basImp)), ((monto)*(total/(basImp))) * -1)))) AS monto,
                    glosa,
                    id_centroDeCostos
                FROM movimientosdecaja
                LEFT JOIN documentos ON movimientosdecaja.id_documentos = documentos.id
                LEFT JOIN (
                    SELECT id_referencia, id_detalle,total,id_tasas, id_centroDeCostos, id_producto
                    FROM d_detalledocumentos 
                    LEFT JOIN l_productos ON d_detalledocumentos.id_producto = l_productos.id
                ) INN1 ON documentos.id = INN1.id_referencia
                LEFT JOIN aperturas ON movimientosdecaja.id_apertura = aperturas.id
                WHERE id_libro IN ('1', '2')
                ) CO1
                LEFT JOIN detalle ON CO1.id_detalle = detalle.id
            ) CO2
            LEFT JOIN familias ON CO2.id_familias = familias.id
            LEFT JOIN subfamilias ON CONCAT(CO2.id_familias, CO2.id_subfamilia) = CONCAT(subfamilias.id_familias, subfamilias.id)
            LEFT JOIN entidades on CO2.id_entidades = entidades.id
            LEFT JOIN libros on CO2.id_libro = libros.id
            LEFT JOIN t_centrodecostos on CO2.id_centroDeCostos = t_centrodecostos.id
            where libros.id = '{$libro}' {$mesCon} and year(fec) = '{$año}' 
            and CO2.id_detalle = '{$detalleId}'
            {$CCCon}
            order by fec asc";

        return DB::select($query);
    }

}