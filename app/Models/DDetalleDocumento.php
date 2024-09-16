<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DDetalleDocumento extends Model
{
    use HasFactory;

    protected $table = 'd_detalledocumentos';

    protected $fillable = [
        'id_referencia',
        'orden',
        'id_producto',
        'observaciones',
        'id_tasas',
        'cantidad',
        'cu',
        'total',
    ];

    public $timestamps = false;
    
    // Relación con el modelo Documento (muchos a 1)
    public function documento()
    {
        return $this->belongsTo(Documento::class, 'id_referencia');
    }
}
