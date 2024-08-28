<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasaIgv extends Model
{
    use HasFactory;

    protected $table = 'tasas_igv';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tasa', // El nombre de la tasa
        'numero', // El valor numérico de la tasa
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false; // Si no necesitas timestamps, ponlo en false
}
