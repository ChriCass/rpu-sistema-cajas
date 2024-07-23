<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    use HasFactory;

     // Especifica el nombre de la tabla incluyendo el esquema
     protected $table = 'detalle';

     // Especifica los campos que pueden ser llenados masivamente
     protected $fillable = ['id_familias', 'id_subfamilia', 'id', 'descripcion', 'id_cuenta'];
 
     // Desactiva las timestamps si no existen en la tabla
     public $timestamps = false;
 
     // Define la clave primaria
     protected $primaryKey = 'id';
 
     // Si la clave primaria no es incremental, desactiva la auto-incrementación
     public $incrementing = false;
 
     // Establece el tipo de clave primaria
     protected $keyType = 'string';
 
     // Define las relaciones
     public function familia()
     {
         return $this->belongsTo(Familia::class, 'id_familias', 'id');
     }
 
     public function subfamilia()
     {
         return $this->belongsTo(Subfamilia::class, 'id_subfamilia', 'id');
     }
}
