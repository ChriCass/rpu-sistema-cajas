<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoDeCajaCustom extends Model
{
    protected $table = 'movimientosdecaja_vista';
    public $timestamps = false;

    protected $fillable = [
        'id_representativo',
        'apl',
        'fec',
        'mov',
        'promedio',
        'vacio',
    ];
}
