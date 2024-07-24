<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mes extends Model
{
    use HasFactory;

    protected $table = 'meses';

    protected $fillable = ['descripcion'];

    public function aperturas()
    {
        return $this->hasMany(Apertura::class, 'id_mes');
    }
}
