<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class CajaExport implements FromCollection
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
           'Id documentos',
           'Familia',
           'Subfamilia',
           'Detalle',
           'Enntidad',
           'Numero',
           'Monto',
           'Glosa'
        ];

        // Combinar las cabeceras con los datos
        return collect([
            $headings, // Añadir las cabeceras como la primera fila
            ...$this->data   // Añadir los datos a continuación
        ]);
    }
}
