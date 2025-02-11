<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class ReporteRegistroComprasExport implements FromCollection
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        // Cabeceras manuales según la estructura deseada
        $headings = [
            'FECHA EMISION',
            'FECHA VENCIMIENTO',
            'TIPO DE DOCUMENTO',
            'SERIE',
            'NUMERO',
            'TIPO DE IDENTIDAD',
            'ID ENTIDAD',
            'ENTIDAD',
            'TASA',
            'BAS IMP',
            'IGV',
            'NO GRAVADAS',
            'OTROS TRIBUTOS',
            'PRECIO',
            'DETRACCION',
            'MONTO NETO',
            'MONEDA',
            'TIPO DOC REFERENCIA',
            'SERIE REFERENCIA',
            'NUMERO REFERENCIA',
            'OBSERVACIONES'
        ];
        

        // Combinar las cabeceras con los datos
        return collect([
            $headings, // Añadir las cabeceras como la primera fila
            ...$this->data   // Añadir los datos a continuación
        ]);
    }
}
