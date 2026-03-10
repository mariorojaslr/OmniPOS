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
        Schema::create('suscripcion_pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->decimal('monto', 10, 2);
            $table->date('fecha_pago');
            $table->string('metodo')->default('manual'); // manual, mercadopago, stripe
            $table->string('estado')->default('aprobado'); // pendiente, aprobado, rechazado
            $table->string('nro_comprobante')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suscripcion_pagos');
    }
};
