<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumentoIdentidad extends Model
{
    use HasFactory;
    // Especifica la tabla asociada con este modelo
    protected $table = 'tabla02_tipodedocumentodeidentidad';

    // Especifica la clave primaria
    protected $primaryKey = 'id';

    // La clave primaria no es un entero autoincrementable
    public $incrementing = false;

    // Especifica el tipo de clave primaria
    protected $keyType = 'string';

    // Desactiva las marcas de tiempo (timestamps) si la tabla no tiene created_at y updated_at
    public $timestamps = false;

    // Definir los campos que pueden ser asignados masivamente
    protected $fillable = ['id', 'descripcion', 'abreviado'];

}
