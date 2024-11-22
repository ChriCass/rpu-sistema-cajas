<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpParser\Node\Stmt\Return_;

class CabecerasComprobantes implements WithHeadings, WithStyles
{
    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            "TIPO DOC IDEN",
            "NUM IDENT",
            "ENTIDAD",
            "FECHA EMISION",
            "FECHA DE VENCIMIENTO",
            "T.DOC",
            "SERIE",
            "NUMERO",
            "MONEDA",
            "TASA IMPOSITIVA",
            "OBSERVACION",
            "B.I",
            "IGV",
            "OTROS TRIBUTOS",
            "NO GRAVADO",
            "PRECIO",
            "MONTO DETRACCION",
            "MONTO NETO",
            "T.DOC REFERENCIA",
            "SER REFERENCIA",
            "NUM REFERENCIA",
            "CUENTA",
            "DETALLE",
            "DESCRIPCION",
            "ES GRAVADO",
            "CANTIDAD",
            "C/U",
            "TOTAL",
            "CENTRO DE COSTOS"
        ];
        
    }

    /**
     * Aplica estilos a las celdas.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Aplica negrita a la primera fila (encabezados)
            1 => ['font' => ['bold' => true]],
        ];
    }
}
