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
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('client_id')->nullable()->constrained();
            $table->string('numero'); // PRE-0001
            $table->date('fecha');
            $table->date('vencimiento')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('iva', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('moneda')->default('ARS');
            $table->text('notas')->nullable();
            $table->enum('estado', ['pendiente', 'aceptado', 'rechazado', 'convertido', 'vencido'])->default('pendiente');
            $table->unsignedBigInteger('venta_id')->nullable(); // No foreign key constraint yet in case we want to convert later and ventas table is large
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
    }
};
