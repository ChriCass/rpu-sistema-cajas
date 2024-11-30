<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class BalanceCuentasExport implements FromCollection
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
            'TIPO DE CUENTA',
            'CUENTA',
            'DEBE',
            'HABER',
            'SUM DEBE',
            'SUM HABER'
        ];
        

        // Combinar las cabeceras con los datos
        return collect([
            $headings, // Añadir las cabeceras como la primera fila
            ...$this->data   // Añadir los datos a continuación
        ]);
    }
}
