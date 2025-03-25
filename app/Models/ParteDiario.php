<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParteDiario extends Model
{
    use HasFactory;

    protected $table = 'partes_diarios';

    protected $fillable = [
        'numero_parte',
        'fecha_inicio',
        'fecha_fin',
        'operador_id',
        'unidad_id',
        'entidad_id',
        'tipo_venta_id',
        'lugar_trabajo',
        'hora_inicio_manana',
        'hora_fin_manana',
        'horas_manana',
        'hora_inicio_tarde',
        'hora_fin_tarde',
        'horas_tarde',
        'total_horas',
        'horometro_inicio_manana',
        'horometro_fin_manana',
        'diferencia_manana',
        'horometro_inicio_tarde',
        'horometro_fin_tarde',
        'diferencia_tarde',
        'diferencia_total',
        'interrupciones',
        'horas_trabajadas',
        'precio_hora',
        'importe_cobrar',
        'estado_pago',
        'monto_pagado',
        'observaciones'
    ];

    // Relaciones
    public function operador()
    {
        return $this->belongsTo(Operador::class);
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    public function entidad()
    {
        return $this->belongsTo(Entidad::class);
    }

    public function tipoVenta()
    {
        return $this->belongsTo(TipoVenta::class);
    }

    // Casting de tipos
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'hora_inicio_manana' => 'datetime',
        'hora_fin_manana' => 'datetime',
        'hora_inicio_tarde' => 'datetime',
        'hora_fin_tarde' => 'datetime',
        'horas_manana' => 'decimal:2',
        'horas_tarde' => 'decimal:2',
        'total_horas' => 'decimal:2',
        'horometro_inicio_manana' => 'decimal:2',
        'horometro_fin_manana' => 'decimal:2',
        'diferencia_manana' => 'decimal:2',
        'horometro_inicio_tarde' => 'decimal:2',
        'horometro_fin_tarde' => 'decimal:2',
        'diferencia_tarde' => 'decimal:2',
        'diferencia_total' => 'decimal:2',
        'horas_trabajadas' => 'decimal:2',
        'precio_hora' => 'decimal:2',
        'importe_cobrar' => 'decimal:2',
        'monto_pagado' => 'decimal:2'
    ];
}
