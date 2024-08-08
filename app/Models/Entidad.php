<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entidad extends Model
{
    use HasFactory;

    // Especifica la tabla asociada
    protected $table = 'entidades';

    // Especifica la clave primaria de la tabla
    protected $primaryKey = 'id';

    // Desactiva la auto-incrementación ya que la clave primaria no es un entero
    public $incrementing = false;

    // Especifica el tipo de clave primaria
    protected $keyType = 'string';

    // Indica si el modelo tiene timestamps
    public $timestamps = false;

    // Especifica los atributos que son asignables en masa
    protected $fillable = [
        'id',
        'descripcion',
        'estado_contribuyente',
        'estado_domiclio',
        'provincia',
        'distrito',
        'direccion',
        'idt02doc',
        'cta1',
        'cta2',
        'cta3',
        'telefono',
        'banco'
    ];
}
