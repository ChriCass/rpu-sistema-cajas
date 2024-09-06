<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDeCambioSunat extends Model
{
    use HasFactory;

    // Tabla asociada al modelo
    protected $table = 'tipcamsunat';

    // Las columnas que pueden ser rellenadas
    protected $fillable = [
        'fecha', 
        'compra', 
        'venta'
    ];

    // Desactivar timestamps
    public $timestamps = false;

    // Definir tipos de datos de las columnas
    protected $casts = [
        'fecha' => 'date',
        'compra' => 'double',
        'venta' => 'double',
    ];
    
}
