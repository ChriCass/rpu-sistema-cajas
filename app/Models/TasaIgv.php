<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasaIgv extends Model
{
    use HasFactory;

      // Especifica la tabla asociada con este modelo
      protected $table = 'tasas_igv';

      // Especifica la clave primaria
      protected $primaryKey = 'id';
  
      // La clave primaria es un entero autoincrementable, así que no es necesario especificar $incrementing
      // Laravel asumirá automáticamente que la clave primaria es de tipo entero (int)
  
      // Desactiva las marcas de tiempo (timestamps) si la tabla no tiene created_at y updated_at
      public $timestamps = false;
  
      // Definir los campos que pueden ser asignados masivamente
      protected $fillable = ['tasa', 'numero'];

}
