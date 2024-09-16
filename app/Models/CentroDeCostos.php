<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DDetalleDocumento;

class CentroDeCostos extends Model
{
    use HasFactory;

    // Especificar el nombre de la tabla
    protected $table = 't_centrodecostos';

    // Especificar la clave primaria
    protected $primaryKey = 'id';

    // Si no usas timestamps en tu tabla, desactívalos
    public $timestamps = false;

    // Definir los campos que pueden ser asignados masivamente
    protected $fillable = [
        'id',
    ];

      // Relación uno a muchos con d_detalledocumentos
      public function detallesDocumentos()
      {
          return $this->hasMany(DDetalleDocumento::class, 'id_centroDeCostos', 'id');
      }
}
