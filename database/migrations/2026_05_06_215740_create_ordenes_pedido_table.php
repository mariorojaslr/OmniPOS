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
        Schema::create('ordenes_pedido', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->onDelete('cascade');
            $table->foreignId('proveedor_id')->constrained('suppliers');
            $table->foreignId('user_id')->constrained();
            $table->string('numero'); // OP-00000001
            $table->date('fecha');
            $table->decimal('total', 15, 2)->default(0);
            $table->enum('estado', ['borrador', 'enviado', 'convertido', 'cancelado'])->default('borrador');
            $table->text('notas_generales')->nullable();
            $table->string('token')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes_pedido');
    }
};
