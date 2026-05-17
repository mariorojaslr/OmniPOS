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
        Schema::table('turnos', function (Blueprint $table) {
            $table->foreignId('obra_social_id')->nullable()->constrained('obras_sociales')->onDelete('set null')->after('cliente_nombre_manual');
            $table->string('nro_afiliado_os')->nullable()->after('obra_social_id');
            // Referencia directa a ventas, sin restricción foreign de la db si la tabla no se llama igual o para evitar dependencias
            $table->unsignedBigInteger('venta_id')->nullable()->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('turnos', function (Blueprint $table) {
            $table->dropForeign(['obra_social_id']);
            $table->dropColumn(['obra_social_id', 'nro_afiliado_os', 'venta_id']);
        });
    }
};
