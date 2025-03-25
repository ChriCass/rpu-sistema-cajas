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
        if (!Schema::hasTable('tipos_venta')) {
            Schema::create('tipos_venta', function (Blueprint $table) {
                $table->id();
                $table->string('descripcion');  // VENTA POR HORA, POR DÍA, etc.
                $table->boolean('estado')->default(true);
                $table->timestamps();
            });
        } else {
            Schema::table('tipos_venta', function (Blueprint $table) {
                if (!Schema::hasColumn('tipos_venta', 'descripcion')) {
                    $table->string('descripcion');
                }
                if (!Schema::hasColumn('tipos_venta', 'estado')) {
                    $table->boolean('estado')->default(true);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tipos_venta')) {
            Schema::table('tipos_venta', function (Blueprint $table) {
                if (Schema::hasColumn('tipos_venta', 'descripcion')) {
                    $table->dropColumn('descripcion');
                }
                if (Schema::hasColumn('tipos_venta', 'estado')) {
                    $table->dropColumn('estado');
                }
            });
        }
    }
};
