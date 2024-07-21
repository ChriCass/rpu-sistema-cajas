<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    use HasFactory;

    // Especifica el nombre de la tabla incluyendo el esquema
    protected $table = 'Logistica.familias';

    // Especifica los campos que pueden ser llenados masivamente
    protected $fillable = ['id', 'descripcion', 'id_tipofamilias'];

    // Desactiva las timestamps si no existen en la tabla
    public $timestamps = false;

    // Define la clave primaria
    protected $primaryKey = 'id';

    // Si la clave primaria no es incremental, desactiva la auto-incrementación
    public $incrementing = false;

    // Establece el tipo de clave primaria
    protected $keyType = 'string';

    // Define las relaciones
    public function subfamilias()
    {
        return $this->hasMany(SubFamilia::class, 'id_familias', 'id');
    }

    public function detalles()
    {
        return $this->hasMany(Detalle::class, 'id_familias', 'id');
    }

    public function tipoFamilia()
    {
        return $this->belongsTo(TipoFamilia::class, 'id_tipofamilias', 'id');
    }
}
