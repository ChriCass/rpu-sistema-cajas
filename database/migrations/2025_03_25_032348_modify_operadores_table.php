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
        Schema::table('operadores', function (Blueprint $table) {
            // Verificamos si las columnas no existen antes de crearlas
            if (!Schema::hasColumn('operadores', 'nombre')) {
                $table->string('nombre');
            }
            if (!Schema::hasColumn('operadores', 'estado')) {
                $table->boolean('estado')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operadores', function (Blueprint $table) {
            // Solo eliminamos las columnas si existen
            if (Schema::hasColumn('operadores', 'nombre')) {
                $table->dropColumn('nombre');
            }
            if (Schema::hasColumn('operadores', 'estado')) {
                $table->dropColumn('estado');
            }
        });
    }
};
