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
        Schema::create('orders', function (Blueprint $table) {

            $table->id();

            // Empresa
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')
                  ->references('id')
                  ->on('empresas')
                  ->onDelete('cascade');

            // Datos cliente
            $table->string('nombre_cliente');
            $table->string('email');
            $table->string('telefono');
            $table->string('direccion')->nullable();

            // Métodos
            $table->string('metodo_entrega'); // retiro_local / envio_domicilio
            $table->string('metodo_pago');    // manual / online

            // Estados
            $table->string('estado')->default('pendiente');
            // pendiente / confirmado / cancelado / pendiente_pago / pagado

            // Total
            $table->decimal('total', 12, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
