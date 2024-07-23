<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    use HasFactory;

    // Definir el nombre de la tabla
    protected $table = 'cuentas';

    // Definir la clave primaria
    protected $primaryKey = 'id';

    // Indicar que la clave primaria no es autoincremental
    public $incrementing = false;

    // Definir el tipo de datos de la clave primaria
    protected $keyType = 'int';

    // Permitir la asignación masiva en estos campos
    protected $fillable = [
        'id',
        'Descripcion',
        'id_tCuenta',
    ];

    // Deshabilitar las marcas de tiempo
    public $timestamps = false;
}
