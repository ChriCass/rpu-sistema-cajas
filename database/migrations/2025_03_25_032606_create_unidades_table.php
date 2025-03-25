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
        if (!Schema::hasTable('unidades')) {
            Schema::create('unidades', function (Blueprint $table) {
                $table->id();
                $table->string('numero');  // M-001, etc.
                $table->string('descripcion');
                $table->boolean('estado')->default(true);
                $table->timestamps();
            });
        } else {
            Schema::table('unidades', function (Blueprint $table) {
                if (!Schema::hasColumn('unidades', 'numero')) {
                    $table->string('numero');
                }
                if (!Schema::hasColumn('unidades', 'descripcion')) {
                    $table->string('descripcion');
                }
                if (!Schema::hasColumn('unidades', 'estado')) {
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
        if (Schema::hasTable('unidades')) {
            Schema::table('unidades', function (Blueprint $table) {
                if (Schema::hasColumn('unidades', 'numero')) {
                    $table->dropColumn('numero');
                }
                if (Schema::hasColumn('unidades', 'descripcion')) {
                    $table->dropColumn('descripcion');
                }
                if (Schema::hasColumn('unidades', 'estado')) {
                    $table->dropColumn('estado');
                }
            });
        }
    }
};
