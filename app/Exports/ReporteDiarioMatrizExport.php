<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class ReporteDiarioMatrizExport implements FromCollection
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
            'LIBRO',
            'ANNIO',
            'MES',
            'APERTURA',
            'MOV',
            'FECHA',
            'ENTIDADES',
            'NOMBRE',
            'TIP DOC',
            'SERIE',
            'NUMERO',
            'CUENTA',
            'DEBE',
            'HABER',
            'NUMERO DE OPERACION',
            'GLOSA'
         ];
 
         // Combinar las cabeceras con los datos
         return collect([
             $headings, // Añadir las cabeceras como la primera fila
             ...$this->data   // Añadir los datos a continuación
         ]);
    }
}
