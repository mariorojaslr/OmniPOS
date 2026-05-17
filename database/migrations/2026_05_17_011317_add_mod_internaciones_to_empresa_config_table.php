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
        Schema::table('empresa_config', function (Blueprint $table) {
            // Agregar interruptor para habilitar el módulo de internaciones
            if (!Schema::hasColumn('empresa_config', 'mod_internaciones')) {
                $table->boolean('mod_internaciones')->default(false)->after('mod_unidades_medida');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresa_config', function (Blueprint $table) {
            // Eliminar columna si se revierte la migración
            if (Schema::hasColumn('empresa_config', 'mod_internaciones')) {
                $table->dropColumn('mod_internaciones');
            }
        });
    }
};
