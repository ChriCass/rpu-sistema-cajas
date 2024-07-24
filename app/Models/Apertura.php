<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mes;
use App\Models\TipoDeCaja;
class Apertura extends Model
{
    use HasFactory;

    protected $table = 'aperturas';

    protected $fillable = [
        'id_tipo',
        'numero',
        'año',
        'id_mes',
        'fecha',
    ];

    public function tipoDeCaja()
    {
        return $this->belongsTo(TipoDeCaja::class, 'id_tipo');
    }

    public function mes()
    {
        return $this->belongsTo(Mes::class, 'id_mes');
    }
}
