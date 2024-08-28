<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DTipoMovimiento extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'd_tipomovimientos';

    protected $fillable = [
        'descripcion',
    ];

    // Relación con el modelo Documento (1 a muchos)
    public function documentos()
    {
        return $this->hasMany(Documento::class, 'id_tipmov');
    }
}
