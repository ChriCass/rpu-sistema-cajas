<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class ReporteAnaliticoCostoExport implements FromCollection
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
            'FECHA',
            'FAMILIA',
            'SUBFAMILIA',
            'DETALLE',
            'ENTIDAD',
            'SERIE',
            'NUMERO',
            'MONTO',
            'GLOSA',
            'CENTRO DE COSTOS'
        ];
        
        

        // Combinar las cabeceras con los datos
        return collect([
            $headings, // Añadir las cabeceras como la primera fila
            ...$this->data   // Añadir los datos a continuación
        ]);
    }
}
