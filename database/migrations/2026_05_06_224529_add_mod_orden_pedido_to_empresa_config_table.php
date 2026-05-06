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
            $table->boolean('mod_orden_pedido')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresa_config', function (Blueprint $table) {
            $table->dropColumn('mod_orden_pedido');
        });
    }
};
