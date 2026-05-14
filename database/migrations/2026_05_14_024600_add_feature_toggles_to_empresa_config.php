<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresa_config', function (Blueprint $blueprint) {
            // Toggles de módulos (Las "llaves" del revendedor)
            $blueprint->boolean('mod_ventas')->default(true);
            $blueprint->boolean('mod_tesoreria')->default(false); // "Usar bancos o no"
            $blueprint->boolean('mod_logistica')->default(false);
            $blueprint->boolean('mod_compras')->default(true);
            $blueprint->boolean('mod_afiliados')->default(false); // Específico Med Plus
            $blueprint->boolean('mod_hce')->default(false); // Historia Clínica Electrónica
        });
    }

    public function down(): void
    {
        Schema::table('empresa_config', function (Blueprint $blueprint) {
            $blueprint->dropColumn([
                'mod_ventas',
                'mod_tesoreria',
                'mod_logistica',
                'mod_compras',
                'mod_afiliados',
                'mod_hce'
            ]);
        });
    }
};
