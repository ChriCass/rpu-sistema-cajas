<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDeCaja extends Model
{
    use HasFactory;

    protected $table = 'tipodecaja';

    protected $fillable = ['descripcion'];

    public function aperturas()
    {
        return $this->hasMany(Apertura::class, 'id_tipo');
    }
    
}
