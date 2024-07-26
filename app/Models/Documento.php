<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos';

    // Indicar que no se manejen automáticamente las marcas de tiempo
    public $timestamps = false;

    // Definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'id_detalle',
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
        'serieMon',
        'observaciones',
        'numeroMod',
        'id_Usuario',
        'fecha_Registro',
        'id_dest_tipcaja',
    ];

    // Definir los campos que son de tipo fecha
    protected $dates = [
        'fechaEmi',
        'fechaVen',
        'fecha_Registro'
    ];
}
