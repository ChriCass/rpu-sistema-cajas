<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDeComprobanteDePagoODocumento extends Model
{
    use HasFactory;

   

    // Especificar el nombre de la tabla si no sigue la convención de nombres
    protected $table = 'tabla10_tipodecomprobantedepagoodocumento';

    // Especificar la clave primaria de la tabla
    protected $primaryKey = 'id';

    // Indicar que la clave primaria no es un entero autoincremental
    public $incrementing = false;
    protected $keyType = 'string';

    // Deshabilitar los timestamps (created_at, updated_at)
    public $timestamps = false;
}
