<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoDeCaja extends Model
{
    use HasFactory;

    protected $table = 'movimientosdecaja';
    public $timestamps = false;
    protected $fillable = [
        'id_libro',
        'id_apertura',
        'mov',
        'fec',
        'id_documentos',
        'id_cuentas',
        'id_dh',
        'monto',
        'montodo',
        'fecha_registro',
        'glosa'
    ];
    protected $dates = [
        'fec',
        'fecha_registro'
    ];

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class, 'id_cuentas');
    }

    public function documento()
    {
        return $this->belongsTo(Documento::class, 'id_documentos');
    }

    public function apertura()
    {
        return $this->belongsTo(Apertura::class, 'id_apertura');
    }
}
