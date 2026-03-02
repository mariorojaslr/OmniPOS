<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kardex_movimientos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('empresa_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Tipo de movimiento
            $table->enum('tipo', [
                'entrada',   // carga / compra
                'salida',    // venta
                'ajuste'     // corrección manual
            ]);

            $table->decimal('cantidad', 10, 2);

            // Stock luego del movimiento
            $table->decimal('stock_resultante', 10, 2);

            $table->string('origen')->nullable(); // POS, AJUSTE, IMPORT, etc

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kardex_movimientos');
    }
};
