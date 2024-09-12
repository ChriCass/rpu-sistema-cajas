<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    // Especificar el nombre de la tabla
    protected $table = 'l_productos';

    // Especificar la clave primaria
    protected $primaryKey = 'id';

    // Indicar que la clave primaria no es auto-incremental
    public $incrementing = false;

    // Definir el tipo de la clave primaria como string (varchar)
    protected $keyType = 'string';

    // Si no usas timestamps en tu tabla, desactívalos
    public $timestamps = false;

    // Definir los campos que pueden ser asignados masivamente
    protected $fillable = [
        'id',
        'id_detalle',
    ];
}
