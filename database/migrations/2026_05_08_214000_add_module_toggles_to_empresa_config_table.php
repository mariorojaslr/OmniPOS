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
            if (!Schema::hasColumn('empresa_config', 'mod_orden_pedido_extra')) {
                $table->boolean('mod_orden_pedido_extra')->default(false)->after('mod_orden_pedido');
            }
            if (!Schema::hasColumn('empresa_config', 'mod_turnos')) {
                $table->boolean('mod_turnos')->default(false)->after('mod_orden_pedido_extra');
            }
            if (!Schema::hasColumn('empresa_config', 'mod_unidades_medida')) {
                $table->boolean('mod_unidades_medida')->default(false)->after('mod_turnos');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresa_config', function (Blueprint $table) {
            $table->dropColumn(['mod_orden_pedido_extra', 'mod_turnos', 'mod_unidades_medida']);
        });
    }
};
