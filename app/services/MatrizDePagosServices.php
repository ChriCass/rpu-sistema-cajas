<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class MatrizDePagosServices
{
    public function obtenerPagosPendientes()
    {
        return DB::select("
            SELECT 
                id,
                tdoc,
                Doc,
                fechaemi,
                id_entidades,
                Deski,
                name,
                id_t04tipmon,
                Facturado,
                monto,
                montoK,
                estadoMon,
                IF(estadoMon = 'PAGADO', NULL, DATEDIFF(CURDATE(), STR_TO_DATE(fechaVen, '%d/%m/%Y'))) AS dias,
                fechaVen,
                detraccion,
                EstadoDetr,
                VenDetraccion,
                observaciones,
                INN1.Num
            FROM (
                SELECT 
                    CON5.id,
                    tabla10_tipodecomprobantedepagoodocumento.descripcion AS tdoc,
                    Doc,
                    fechaemi,
                    id_entidades,
                    entidades.descripcion AS Deski,
                    users.name,
                    id_t04tipmon,
                    IF(id_t04tipmon = 'PEN', monto, montodo) AS monto,
                    IF(IF(id_t04tipmon = 'PEN', monto, montodo) = 0, 'PAGADO',
                        IF(CURDATE() > DATE_ADD(STR_TO_DATE(fechaVen, '%d/%m/%Y'), INTERVAL 1 DAY), 'VENCIDO',
                            IF(CURDATE() > DATE_ADD(STR_TO_DATE(fechaVen, '%d/%m/%Y'), INTERVAL -7 DAY), 'URGENTE', 'PENDIENTE'))) 
                    AS estadoMon,
                    fechaVen,
                    detraccion,
                    IF(
                        detraccion IS NULL,
                        '',
                        IF(detraccion = 0, 'PAGADO',
                            IF(CURDATE() > STR_TO_DATE(
                                    IF(detraccion IS NOT NULL, 
                                        DATE_FORMAT(DATE_ADD(STR_TO_DATE(fechaemi, '%d/%m/%Y'), INTERVAL 1 YEAR), '%d/%m/%Y'),
                                        NULL), '%d/%m/%Y'), 'VENCIDO',
                                IF(CURDATE() > DATE_ADD(STR_TO_DATE(
                                        IF(detraccion IS NOT NULL, 
                                            DATE_FORMAT(DATE_ADD(STR_TO_DATE(fechaemi, '%d/%m/%Y'), INTERVAL 1 YEAR), '%d/%m/%Y'),
                                            NULL), '%d/%m/%Y'), INTERVAL -7 DAY), 'URGENTE', 'PENDIENTE')))
                    ) AS EstadoDetr,
                    IF(detraccion IS NOT NULL, DATE_FORMAT(DATE_ADD(STR_TO_DATE(fechaemi, '%d/%m/%Y'), INTERVAL 1 YEAR), '%d/%m/%Y'), NULL) AS VenDetraccion,
                    observaciones,
                    entidades.cta1,
                    entidades.cta2,
                    entidades.cta3,
                    entidades.telefono,
                    Facturado
                FROM (
                    SELECT 
                        id,
                        id_t10tdoc,
                        CONCAT(serie, '-', numero) AS Doc,
                        DATE_FORMAT(CAST(fechaemi AS DATE), '%d/%m/%Y') AS fechaemi,
                        id_entidades,
                        id_t04tipmon,
                        CON4.monto,
                        CON4.detraccion,
                        CON4.montodo,
                        CON4.detracciondo,
                        DATE_FORMAT(CAST(fechaVen AS DATE), '%d/%m/%Y') AS fechaVen,
                        observaciones,
                        id_user,
                        IFNULL(NULLIF(montoNeto, 0), precio) AS Facturado
                    FROM (
                        SELECT 
                            id_documentos,
                            SUM(monto) AS monto,
                            SUM(detraccion) AS detraccion,
                            SUM(montodo) AS montodo,
                            SUM(detracciondo) AS detracciondo
                        FROM (
                            SELECT 
                                id_documentos,
                                id_cuentas,
                                IF(id_cuentas = '3', monto, 0) AS monto,
                                IF(id_cuentas = '4', monto, NULL) AS detraccion,
                                IF(id_cuentas = '3', montodo, 0) AS montodo,
                                IF(id_cuentas = '4', montodo, NULL) AS detracciondo
                            FROM (
                                SELECT 
                                    id_documentos,
                                    id_cuentas,
                                    SUM(monto) AS monto,
                                    SUM(montodo) AS montodo
                                FROM (
                                    SELECT 
                                        id_documentos,
                                        id_cuentas,
                                        IF(id_dh = '2', monto, monto * -1) AS monto,
                                        IF(id_dh = '2', IF(montodo IS NULL, 0, montodo), IF(montodo IS NULL, 0, montodo) * -1) AS montodo
                                    FROM 
                                        movimientosdecaja
                                    WHERE 
                                        id_cuentas IN ('3', '4')
                                ) CON1
                                GROUP BY 
                                    id_documentos,
                                    id_cuentas
                            ) CON2
                        ) CON3
                        GROUP BY 
                            id_documentos
                    ) CON4
                    LEFT JOIN documentos ON CON4.id_documentos = documentos.id
                ) CON5
                LEFT JOIN entidades ON CON5.id_entidades = entidades.id
                LEFT JOIN tabla10_tipodecomprobantedepagoodocumento ON CON5.id_t10tdoc = tabla10_tipodecomprobantedepagoodocumento.id
                LEFT JOIN users ON CON5.id_user = users.id
            ) CON6
            LEFT JOIN (
                SELECT 
                    id_documentos,
                    GROUP_CONCAT(Num ORDER BY Num SEPARATOR ' | ') AS Num
                FROM (
                    SELECT 
                        id_documentos,
                        CONCAT(mov, ':[', fec, ']:', monto) AS Num
                    FROM (
                        SELECT 
                            id_documentos,
                            mov,
                            DATE_FORMAT(fec, '%d/%m/%Y') AS fec,
                            IF(montodo IS NULL, monto, montodo) AS monto
                        FROM 
                            movimientosdecaja
                        WHERE 
                            id_cuentas IN ('3') 
                            AND id_libro = '3'
                    ) CON1
                ) CON1
                GROUP BY id_documentos
            ) INN1 ON CON6.id = INN1.id_documentos
            LEFT JOIN (
                SELECT 
                    id_documentos,
                    SUM(IF(montodo IS NULL, monto, montodo)) AS montok
                FROM 
                    movimientosdecaja
                WHERE 
                    id_cuentas IN ('3') 
                    AND id_libro = '3'
                GROUP BY id_documentos
            ) INN2 ON CON6.id = INN2.id_documentos
            WHERE CONCAT(estadoMon, EstadoDetr) NOT IN ('PAGADOPAGADO', 'PAGADO')
        ");
    }

    public function obtenerPagosPagados()
    {
        return DB::select("
            SELECT 
                id,
                tdoc,
                Doc,
                fechaemi,
                id_entidades,
                Deski,
                name,
                id_t04tipmon,
                Facturado,
                monto,
                montoK,
                estadoMon,
                IF(estadoMon = 'PAGADO', NULL, DATEDIFF(CURDATE(), STR_TO_DATE(fechaVen, '%d/%m/%Y'))) AS dias,
                fechaVen,
                detraccion,
                EstadoDetr,
                VenDetraccion,
                observaciones,
                INN1.Num
            FROM (
                SELECT 
                    CON5.id,
                    tabla10_tipodecomprobantedepagoodocumento.descripcion AS tdoc,
                    Doc,
                    fechaemi,
                    id_entidades,
                    entidades.descripcion AS Deski,
                    users.name,
                    id_t04tipmon,
                    IF(id_t04tipmon = 'PEN', monto, montodo) AS monto,
                    IF(IF(id_t04tipmon = 'PEN', monto, montodo) = 0, 'PAGADO',
                        IF(CURDATE() > DATE_ADD(STR_TO_DATE(fechaVen, '%d/%m/%Y'), INTERVAL 1 DAY), 'VENCIDO',
                            IF(CURDATE() > DATE_ADD(STR_TO_DATE(fechaVen, '%d/%m/%Y'), INTERVAL -7 DAY), 'URGENTE', 'PENDIENTE'))) 
                    AS estadoMon,
                    fechaVen,
                    detraccion,
                    IF(
                        detraccion IS NULL,
                        '',
                        IF(detraccion = 0, 'PAGADO',
                            IF(CURDATE() > STR_TO_DATE(
                                    IF(detraccion IS NOT NULL, 
                                        DATE_FORMAT(DATE_ADD(STR_TO_DATE(fechaemi, '%d/%m/%Y'), INTERVAL 1 YEAR), '%d/%m/%Y'),
                                        NULL), '%d/%m/%Y'), 'VENCIDO',
                                IF(CURDATE() > DATE_ADD(STR_TO_DATE(
                                        IF(detraccion IS NOT NULL, 
                                            DATE_FORMAT(DATE_ADD(STR_TO_DATE(fechaemi, '%d/%m/%Y'), INTERVAL 1 YEAR), '%d/%m/%Y'),
                                            NULL), '%d/%m/%Y'), INTERVAL -7 DAY), 'URGENTE', 'PENDIENTE')))
                    ) AS EstadoDetr,
                    IF(detraccion IS NOT NULL, DATE_FORMAT(DATE_ADD(STR_TO_DATE(fechaemi, '%d/%m/%Y'), INTERVAL 1 YEAR), '%d/%m/%Y'), NULL) AS VenDetraccion,
                    observaciones,
                    entidades.cta1,
                    entidades.cta2,
                    entidades.cta3,
                    entidades.telefono,
                    Facturado
                FROM (
                    SELECT 
                        id,
                        id_t10tdoc,
                        CONCAT(serie, '-', numero) AS Doc,
                        DATE_FORMAT(CAST(fechaemi AS DATE), '%d/%m/%Y') AS fechaemi,
                        id_entidades,
                        id_t04tipmon,
                        CON4.monto,
                        CON4.detraccion,
                        CON4.montodo,
                        CON4.detracciondo,
                        DATE_FORMAT(CAST(fechaVen AS DATE), '%d/%m/%Y') AS fechaVen,
                        observaciones,
                        id_user,
                        IFNULL(NULLIF(montoNeto, 0), precio) AS Facturado
                    FROM (
                        SELECT 
                            id_documentos,
                            SUM(monto) AS monto,
                            SUM(detraccion) AS detraccion,
                            SUM(montodo) AS montodo,
                            SUM(detracciondo) AS detracciondo
                        FROM (
                            SELECT 
                                id_documentos,
                                id_cuentas,
                                IF(id_cuentas = '3', monto, 0) AS monto,
                                IF(id_cuentas = '4', monto, NULL) AS detraccion,
                                IF(id_cuentas = '3', montodo, 0) AS montodo,
                                IF(id_cuentas = '4', montodo, NULL) AS detracciondo
                            FROM (
                                SELECT 
                                    id_documentos,
                                    id_cuentas,
                                    SUM(monto) AS monto,
                                    SUM(montodo) AS montodo
                                FROM (
                                    SELECT 
                                        id_documentos,
                                        id_cuentas,
                                        IF(id_dh = '2', monto, monto * -1) AS monto,
                                        IF(id_dh = '2', IF(montodo IS NULL, 0, montodo), IF(montodo IS NULL, 0, montodo) * -1) AS montodo
                                    FROM 
                                        movimientosdecaja
                                    WHERE 
                                        id_cuentas IN ('3', '4')
                                ) CON1
                                GROUP BY 
                                    id_documentos,
                                    id_cuentas
                            ) CON2
                        ) CON3
                        GROUP BY 
                            id_documentos
                    ) CON4
                    LEFT JOIN documentos ON CON4.id_documentos = documentos.id
                ) CON5
                LEFT JOIN entidades ON CON5.id_entidades = entidades.id
                LEFT JOIN tabla10_tipodecomprobantedepagoodocumento ON CON5.id_t10tdoc = tabla10_tipodecomprobantedepagoodocumento.id
                LEFT JOIN users ON CON5.id_user = users.id
            ) CON6
            LEFT JOIN (
                SELECT 
                    id_documentos,
                    GROUP_CONCAT(Num ORDER BY Num SEPARATOR ' | ') AS Num
                FROM (
                    SELECT 
                        id_documentos,
                        CONCAT(mov, ':[', fec, ']:', monto) AS Num
                    FROM (
                        SELECT 
                            id_documentos,
                            mov,
                            DATE_FORMAT(fec, '%d/%m/%Y') AS fec,
                            IF(montodo IS NULL, monto, montodo) AS monto
                        FROM 
                            movimientosdecaja
                        WHERE 
                            id_cuentas IN ('3') 
                            AND id_libro = '3'
                    ) CON1
                ) CON1
                GROUP BY id_documentos
            ) INN1 ON CON6.id = INN1.id_documentos
            LEFT JOIN (
                SELECT 
                    id_documentos,
                    SUM(IF(montodo IS NULL, monto, montodo)) AS montok
                FROM 
                    movimientosdecaja
                WHERE 
                    id_cuentas IN ('3') 
                    AND id_libro = '3'
                GROUP BY id_documentos
            ) INN2 ON CON6.id = INN2.id_documentos
            WHERE CONCAT(estadoMon, EstadoDetr)  IN ('PAGADOPAGADO', 'PAGADO')
        ");
    }

    public function obtenerTodosLosPagos()
    {
        return DB::select("
            SELECT 
                id,
                tdoc,
                Doc,
                fechaemi,
                id_entidades,
                Deski,
                name,
                id_t04tipmon,
                Facturado,
                monto,
                montoK,
                estadoMon,
                IF(estadoMon = 'PAGADO', NULL, DATEDIFF(CURDATE(), STR_TO_DATE(fechaVen, '%d/%m/%Y'))) AS dias,
                fechaVen,
                detraccion,
                EstadoDetr,
                VenDetraccion,
                observaciones,
                INN1.Num
            FROM (
                SELECT 
                    CON5.id,
                    tabla10_tipodecomprobantedepagoodocumento.descripcion AS tdoc,
                    Doc,
                    fechaemi,
                    id_entidades,
                    entidades.descripcion AS Deski,
                    users.name,
                    id_t04tipmon,
                    IF(id_t04tipmon = 'PEN', monto, montodo) AS monto,
                    IF(IF(id_t04tipmon = 'PEN', monto, montodo) = 0, 'PAGADO',
                        IF(CURDATE() > DATE_ADD(STR_TO_DATE(fechaVen, '%d/%m/%Y'), INTERVAL 1 DAY), 'VENCIDO',
                            IF(CURDATE() > DATE_ADD(STR_TO_DATE(fechaVen, '%d/%m/%Y'), INTERVAL -7 DAY), 'URGENTE', 'PENDIENTE'))) 
                    AS estadoMon,
                    fechaVen,
                    detraccion,
                    IF(
                        detraccion IS NULL,
                        '',
                        IF(detraccion = 0, 'PAGADO',
                            IF(CURDATE() > STR_TO_DATE(
                                    IF(detraccion IS NOT NULL, 
                                        DATE_FORMAT(DATE_ADD(STR_TO_DATE(fechaemi, '%d/%m/%Y'), INTERVAL 1 YEAR), '%d/%m/%Y'),
                                        NULL), '%d/%m/%Y'), 'VENCIDO',
                                IF(CURDATE() > DATE_ADD(STR_TO_DATE(
                                        IF(detraccion IS NOT NULL, 
                                            DATE_FORMAT(DATE_ADD(STR_TO_DATE(fechaemi, '%d/%m/%Y'), INTERVAL 1 YEAR), '%d/%m/%Y'),
                                            NULL), '%d/%m/%Y'), INTERVAL -7 DAY), 'URGENTE', 'PENDIENTE')))
                    ) AS EstadoDetr,
                    IF(detraccion IS NOT NULL, DATE_FORMAT(DATE_ADD(STR_TO_DATE(fechaemi, '%d/%m/%Y'), INTERVAL 1 YEAR), '%d/%m/%Y'), NULL) AS VenDetraccion,
                    observaciones,
                    entidades.cta1,
                    entidades.cta2,
                    entidades.cta3,
                    entidades.telefono,
                    Facturado
                FROM (
                    SELECT 
                        id,
                        id_t10tdoc,
                        CONCAT(serie, '-', numero) AS Doc,
                        DATE_FORMAT(CAST(fechaemi AS DATE), '%d/%m/%Y') AS fechaemi,
                        id_entidades,
                        id_t04tipmon,
                        CON4.monto,
                        CON4.detraccion,
                        CON4.montodo,
                        CON4.detracciondo,
                        DATE_FORMAT(CAST(fechaVen AS DATE), '%d/%m/%Y') AS fechaVen,
                        observaciones,
                        id_user,
                        IFNULL(NULLIF(montoNeto, 0), precio) AS Facturado
                    FROM (
                        SELECT 
                            id_documentos,
                            SUM(monto) AS monto,
                            SUM(detraccion) AS detraccion,
                            SUM(montodo) AS montodo,
                            SUM(detracciondo) AS detracciondo
                        FROM (
                            SELECT 
                                id_documentos,
                                id_cuentas,
                                IF(id_cuentas = '3', monto, 0) AS monto,
                                IF(id_cuentas = '4', monto, NULL) AS detraccion,
                                IF(id_cuentas = '3', montodo, 0) AS montodo,
                                IF(id_cuentas = '4', montodo, NULL) AS detracciondo
                            FROM (
                                SELECT 
                                    id_documentos,
                                    id_cuentas,
                                    SUM(monto) AS monto,
                                    SUM(montodo) AS montodo
                                FROM (
                                    SELECT 
                                        id_documentos,
                                        id_cuentas,
                                        IF(id_dh = '2', monto, monto * -1) AS monto,
                                        IF(id_dh = '2', IF(montodo IS NULL, 0, montodo), IF(montodo IS NULL, 0, montodo) * -1) AS montodo
                                    FROM 
                                        movimientosdecaja
                                    WHERE 
                                        id_cuentas IN ('3', '4')
                                ) CON1
                                GROUP BY 
                                    id_documentos,
                                    id_cuentas
                            ) CON2
                        ) CON3
                        GROUP BY 
                            id_documentos
                    ) CON4
                    LEFT JOIN documentos ON CON4.id_documentos = documentos.id
                ) CON5
                LEFT JOIN entidades ON CON5.id_entidades = entidades.id
                LEFT JOIN tabla10_tipodecomprobantedepagoodocumento ON CON5.id_t10tdoc = tabla10_tipodecomprobantedepagoodocumento.id
                LEFT JOIN users ON CON5.id_user = users.id
            ) CON6
            LEFT JOIN (
                SELECT 
                    id_documentos,
                    GROUP_CONCAT(Num ORDER BY Num SEPARATOR ' | ') AS Num
                FROM (
                    SELECT 
                        id_documentos,
                        CONCAT(mov, ':[', fec, ']:', monto) AS Num
                    FROM (
                        SELECT 
                            id_documentos,
                            mov,
                            DATE_FORMAT(fec, '%d/%m/%Y') AS fec,
                            IF(montodo IS NULL, monto, montodo) AS monto
                        FROM 
                            movimientosdecaja
                        WHERE 
                            id_cuentas IN ('3') 
                            AND id_libro = '3'
                    ) CON1
                ) CON1
                GROUP BY id_documentos
            ) INN1 ON CON6.id = INN1.id_documentos
            LEFT JOIN (
                SELECT 
                    id_documentos,
                    SUM(IF(montodo IS NULL, monto, montodo)) AS montok
                FROM 
                    movimientosdecaja
                WHERE 
                    id_cuentas IN ('3') 
                    AND id_libro = '3'
                GROUP BY id_documentos
            ) INN2 ON CON6.id = INN2.id_documentos
            
        ");
    }
}
