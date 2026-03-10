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
        if (!Schema::hasColumn('suscripcion_pagos', 'empresa_id')) {
            Schema::table('suscripcion_pagos', function (Blueprint $table) {
                $table->unsignedBigInteger('empresa_id')->after('id');
                $table->unsignedBigInteger('plan_id')->nullable()->after('empresa_id');
                $table->decimal('monto', 10, 2)->after('plan_id');
                $table->date('fecha_pago')->after('monto');
                $table->string('metodo')->default('manual')->after('fecha_pago'); // manual, mercadopago, stripe
                $table->string('estado')->default('aprobado')->after('metodo'); // pendiente, aprobado, rechazado
                $table->string('nro_comprobante')->nullable()->after('estado');
                $table->text('notas')->nullable()->after('nro_comprobante');

                $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
                $table->foreign('plan_id')->references('id')->on('plans')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suscripcion_pagos', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropForeign(['plan_id']);
            $table->dropColumn([
                'empresa_id', 'plan_id', 'monto', 'fecha_pago', 'metodo', 'estado', 'nro_comprobante', 'notas'
            ]);
        });
    }
};
