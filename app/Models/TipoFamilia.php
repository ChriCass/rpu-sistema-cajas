<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoFamilia extends Model
{
    use HasFactory;

      // Especifica el nombre de la tabla incluyendo el esquema
      protected $table = 'Logistica.tipofamilia';

      // Especifica los campos que pueden ser llenados masivamente
      protected $fillable = ['id', 'descripcion'];
  
      // Desactiva las timestamps si no existen en la tabla
      public $timestamps = false;
  
      // Define la clave primaria
      protected $primaryKey = 'id';
  
   
      // Si la clave primaria es incremental (lo cual es lo usual con ints), no es necesario cambiar $incrementing y $keyTyp

      public function familias()
      {
          return $this->hasMany(Familia::class, 'id_tipofamilias', 'id');
      }
}
