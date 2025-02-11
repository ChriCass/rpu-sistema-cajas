<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class ReporteInconcistenciasExport implements FromCollection
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
            'ID',
            'ENTIDADES',
            'NOMBRE ENTIDAD',
            'TIPO DE DOCUMENTO',
            'SERIE',
            'NUMERO'
        ];
        
        

        // Combinar las cabeceras con los datos
        return collect([
            $headings, // Añadir las cabeceras como la primera fila
            ...$this->data   // Añadir los datos a continuación
        ]);
    }
}
