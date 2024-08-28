<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'documentos';

    protected $fillable = [
        'id_tipmov',
        'fechaEmi',
        'fechaVen',
        'id_t10tdoc',
        'id_t02tcom',
        'id_entidades',
        'id_t04tipmon',
        'id_tasasIgv',
        'serie',
        'numero',
        'totalBi',
        'descuentoBi',
        'recargoBi',
        'basImp',
        'IGV',
        'totalNg',
        'descuentoNg',
        'recargoNg',
        'noGravadas',
        'otroTributo',
        'precio',
        'detraccion',
        'montoNeto',
        'id_t10tdocMod',
        'observaciones',
        'serieMod',
        'numeroMod',
        'id_Usuario',
        'fecha_Registro',
        'id_dest_tipcaja',
    ];

    // Relación con el modelo DDetalleDocumento (1 a muchos)
    public function detalles()
    {
        return $this->hasMany(DDetalleDocumento::class, 'id_referencia');
    }

    // Relación con el modelo DTipoMovimiento (1 a 1)
    public function tipoMovimiento()
    {
        return $this->belongsTo(DTipoMovimiento::class, 'id_tipmov');
    }

}
