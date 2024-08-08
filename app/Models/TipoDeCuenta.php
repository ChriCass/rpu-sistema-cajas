<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDeCuenta extends Model
{
    use HasFactory;

     // Especificar la tabla asociada al modelo
     protected $table = 'tipodecuenta';

     // Especificar la clave primaria de la tabla
     protected $primaryKey = 'id';
 
     // Desactivar las marcas de tiempo (timestamps)
     public $timestamps = false;
 
     // Especificar los atributos que se pueden asignar masivamente
     protected $fillable = [
         'descripcion',
     ];
 
     // Si la clave primaria no es auto-incremental
     public $incrementing = false;
 
     // Si la clave primaria no es un entero
     protected $keyType = 'int';
}
