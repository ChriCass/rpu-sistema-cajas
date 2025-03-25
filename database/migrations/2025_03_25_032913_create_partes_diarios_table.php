<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('partes_diarios')) {
            Schema::create('partes_diarios', function (Blueprint $table) {
                $table->id();
                $table->string('numero_parte');
                $table->date('fecha_inicio');
                $table->date('fecha_fin');
                
                // Relaciones
                $table->foreignId('operador_id')->constrained('operadores');
                $table->foreignId('unidad_id')->constrained('unidades');
                $table->foreignId('entidad_id')->constrained('entidades');
                $table->foreignId('tipo_venta_id')->constrained('tipos_venta');
                
                // Información general
                $table->string('lugar_trabajo');
                
                // Control de horas
                $table->time('hora_inicio_manana')->nullable();
                $table->time('hora_fin_manana')->nullable();
                $table->decimal('horas_manana', 8, 2)->default(0);
                $table->time('hora_inicio_tarde')->nullable();
                $table->time('hora_fin_tarde')->nullable();
                $table->decimal('horas_tarde', 8, 2)->default(0);
                $table->decimal('total_horas', 8, 2)->default(0);
                
                // Control de horómetros
                $table->decimal('horometro_inicio_manana', 8, 2)->default(0);
                $table->decimal('horometro_fin_manana', 8, 2)->default(0);
                $table->decimal('diferencia_manana', 8, 2)->default(0);
                $table->decimal('horometro_inicio_tarde', 8, 2)->default(0);
                $table->decimal('horometro_fin_tarde', 8, 2)->default(0);
                $table->decimal('diferencia_tarde', 8, 2)->default(0);
                $table->decimal('diferencia_total', 8, 2)->default(0);
                
                // Interrupciones
                $table->text('interrupciones')->nullable();
                
                // Valorización
                $table->decimal('horas_trabajadas', 8, 2)->default(0);
                $table->decimal('precio_hora', 10, 2)->default(0);
                $table->decimal('importe_cobrar', 10, 2)->default(0);
                
                // Estado de pago
                $table->tinyInteger('estado_pago')->default(0); // 0: pendiente, 1: pagado, 2: parcial
                $table->decimal('monto_pagado', 10, 2)->default(0);
                
                // Observaciones
                $table->text('observaciones')->nullable();
                
                $table->timestamps();
            });
        } else {
            Schema::table('partes_diarios', function (Blueprint $table) {
                // Aquí añadimos las columnas que falten si la tabla ya existe
                $columns = [
                    'numero_parte' => 'string',
                    'fecha_inicio' => 'date',
                    'fecha_fin' => 'date',
                    'operador_id' => 'foreignId',
                    'unidad_id' => 'foreignId',
                    'entidad_id' => 'foreignId',
                    'tipo_venta_id' => 'foreignId',
                    'lugar_trabajo' => 'string',
                    'hora_inicio_manana' => 'time',
                    'hora_fin_manana' => 'time',
                    'horas_manana' => 'decimal',
                    'hora_inicio_tarde' => 'time',
                    'hora_fin_tarde' => 'time',
                    'horas_tarde' => 'decimal',
                    'total_horas' => 'decimal',
                    'horometro_inicio_manana' => 'decimal',
                    'horometro_fin_manana' => 'decimal',
                    'diferencia_manana' => 'decimal',
                    'horometro_inicio_tarde' => 'decimal',
                    'horometro_fin_tarde' => 'decimal',
                    'diferencia_tarde' => 'decimal',
                    'diferencia_total' => 'decimal',
                    'interrupciones' => 'text',
                    'horas_trabajadas' => 'decimal',
                    'precio_hora' => 'decimal',
                    'importe_cobrar' => 'decimal',
                    'estado_pago' => 'tinyInteger',
                    'monto_pagado' => 'decimal',
                    'observaciones' => 'text'
                ];

                foreach ($columns as $column => $type) {
                    if (!Schema::hasColumn('partes_diarios', $column)) {
                        switch ($type) {
                            case 'string':
                                $table->string($column);
                                break;
                            case 'date':
                                $table->date($column);
                                break;
                            case 'time':
                                $table->time($column)->nullable();
                                break;
                            case 'decimal':
                                $table->decimal($column, 10, 2)->default(0);
                                break;
                            case 'text':
                                $table->text($column)->nullable();
                                break;
                            case 'tinyInteger':
                                $table->tinyInteger($column)->default(0);
                                break;
                            case 'foreignId':
                                $table->foreignId($column)->constrained(str_replace('_id', 's', $column));
                                break;
                        }
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('partes_diarios')) {
            Schema::table('partes_diarios', function (Blueprint $table) {
                // Eliminamos primero las foreign keys
                $foreignKeys = ['operador_id', 'unidad_id', 'entidad_id', 'tipo_venta_id'];
                foreach ($foreignKeys as $key) {
                    if (Schema::hasColumn('partes_diarios', $key)) {
                        $table->dropForeign([$key]);
                        $table->dropColumn($key);
                    }
                }

                // Luego eliminamos el resto de columnas
                $columns = [
                    'numero_parte', 'fecha_inicio', 'fecha_fin', 'lugar_trabajo',
                    'hora_inicio_manana', 'hora_fin_manana', 'horas_manana',
                    'hora_inicio_tarde', 'hora_fin_tarde', 'horas_tarde', 'total_horas',
                    'horometro_inicio_manana', 'horometro_fin_manana', 'diferencia_manana',
                    'horometro_inicio_tarde', 'horometro_fin_tarde', 'diferencia_tarde',
                    'diferencia_total', 'interrupciones', 'horas_trabajadas', 'precio_hora',
                    'importe_cobrar', 'estado_pago', 'monto_pagado', 'observaciones'
                ];

                foreach ($columns as $column) {
                    if (Schema::hasColumn('partes_diarios', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
