<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVenta extends Model
{
    use HasFactory;

    protected $table = 'tipos_venta';

    protected $fillable = [
        'descripcion',
        'estado'
    ];

    // Relación con PartesDiarios
    public function partesDiarios()
    {
        return $this->hasMany(ParteDiario::class);
    }
}
