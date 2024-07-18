<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubFamilia extends Model
{
    use HasFactory;

    // Especifica el nombre de la tabla incluyendo el esquema
    protected $table = 'Logistica.subfamilias';

    // Especifica los campos que pueden ser llenados masivamente
    protected $fillable = ['id_familias', 'id', 'desripcion', 'new_id'];

    // Desactiva las timestamps si no existen en la tabla
    public $timestamps = false;

    // No hay una clave primaria definida explícitamente en tu esquema, 
        // así que debemos decirle a Eloquent que no existe una clave primaria en la tabla
    
    
     // Define la nueva clave primaria
     protected $primaryKey = 'new_id';
     public $incrementing = true;
     protected $keyType = 'int';

    // Define las relaciones
    public function familia()
    {
        return $this->belongsTo(Familia::class, 'id_familias', 'id');
    }
}
