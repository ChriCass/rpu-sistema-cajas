<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CentroDeCostosService
{
    public function obtenerMovimientos($maymen, $centroDeCosto, $año)
    {
        // Determinación del filtro de mayor o menor que 0
        $mayormenor = $maymen == 1 ? '>' : '<';

        // Filtro para el centro de costos si se proporciona
        $filtroCentroDeCosto = $centroDeCosto 
            ? "AND CO2.id_centrodecostos = '{$centroDeCosto}'" 
            : '';

        // Consulta SQL completa
        $query = "
            SELECT 
                familias.descripcion AS familia_descripcion,
                subfamilias.desripcion AS subfamilia_descripcion,
                CO2.descripcion AS detalle_descripcion,
                SUM(IF(MONTH(CO2.fec) = 1, monto, 0)) AS enero,
                SUM(IF(MONTH(CO2.fec) = 2, monto, 0)) AS febrero,
                SUM(IF(MONTH(CO2.fec) = 3, monto, 0)) AS marzo,
                SUM(IF(MONTH(CO2.fec) = 4, monto, 0)) AS abril,
                SUM(IF(MONTH(CO2.fec) = 5, monto, 0)) AS mayo,
                SUM(IF(MONTH(CO2.fec) = 6, monto, 0)) AS junio,
                SUM(IF(MONTH(CO2.fec) = 7, monto, 0)) AS julio,
                SUM(IF(MONTH(CO2.fec) = 8, monto, 0)) AS agosto,
                SUM(IF(MONTH(CO2.fec) = 9, monto, 0)) AS septiembre,
                SUM(IF(MONTH(CO2.fec) = 10, monto, 0)) AS octubre,
                SUM(IF(MONTH(CO2.fec) = 11, monto, 0)) AS noviembre,
                SUM(IF(MONTH(CO2.fec) = 12, monto, 0)) AS diciembre
            FROM (
                SELECT 
                    CO1.id_apertura,
                    CO1.fec,
                    CO1.mov,
                    CO1.id_documentos,
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
                    FROM movimientosdecaja
                    LEFT JOIN documentos ON movimientosdecaja.id_documentos = documentos.id
                    LEFT JOIN (
                        SELECT id_referencia, id_detalle, id_centroDeCostos
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
            WHERE monto {$mayormenor} 0 {$filtroCentroDeCosto} 
            AND YEAR(fec) = ?
            GROUP BY familia_descripcion, subfamilia_descripcion, detalle_descripcion
            ORDER BY familia_descripcion;
        ";

        // Loguear la consulta para fines de depuración
        Log::info('Ejecutando consulta de movimientos', [
            'query' => $query,
            'año' => $año,
            'centroDeCosto' => $centroDeCosto,
        ]);

        // Ejecutar la consulta y devolver los resultados
        return DB::select($query, [$año]);
    }
}
