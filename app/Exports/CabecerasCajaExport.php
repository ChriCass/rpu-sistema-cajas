<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpParser\Node\Stmt\Return_;

class CabecerasCajaExport implements WithHeadings, WithStyles
{
    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'TIPO DE CAJA',
            'AÑO',
            'MES',
            'NUMERO',
            'TIPO DE MOVIMIENTO',
            'NUMERO DE MOVIMIENTO',
            'MONTO',
            'TIPO DOC IDEN',
            'NUM IDENT',
            'ENTIDAD',
            'T.DOC',
            'SERIE',
            'CORRELATIVO',
            'CUENTA',
            'OBSERVACION',
            'NUMERO DE OPERACIÓN',
            'MONEDA',
            'FECHA EMISION',
            'FECHA DE VENCIMIENTO',
            'TASA IMPOSITIVA',
            'B.I',
            'IGV',
            'OTROS TRIBUTOS',
            'NO GRAVADO',
            'DETALLE',
            'DESCRIPCION',
            'ES GRAVADO',
            'CANTIDAD',
            'C/U',
            'TOTAL',
            'CENTRO DE COSTOS',
            'CAJA DESTINO'
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
