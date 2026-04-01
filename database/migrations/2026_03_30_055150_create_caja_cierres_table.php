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
        Schema::create('caja_cierres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('asistencia_id')->nullable()->constrained('asistencias')->onDelete('set null');
            
            // Tiempos
            $table->timestamp('fecha_apertura')->useCurrent();
            $table->timestamp('fecha_cierre')->nullable();
            
            // Dinero
            $table->decimal('saldo_inicial', 15, 2)->default(0);
            $table->decimal('ventas_efectivo', 15, 2)->default(0);
            $table->decimal('ventas_tarjeta', 15, 2)->default(0);      // 💳 Tarjetas
            $table->decimal('ventas_transferencia', 15, 2)->default(0); // 🏦 Transferencias
            $table->decimal('otros_ingresos', 15, 2)->default(0);
            $table->decimal('egresos', 15, 2)->default(0);
            
            // Totales
            $table->decimal('saldo_esperado', 15, 2)->default(0); // Inicial + Efectivo + Ingresos - Egresos
            $table->decimal('saldo_real', 15, 2)->default(0);    // Lo que el usuario dice que hay físicamente
            $table->decimal('diferencia', 15, 2)->default(0);    // Saldo Real - Saldo Esperado (Faltante si < 0)
            
            $table->text('observaciones')->nullable();
            $table->string('estado')->default('abierta'); // abierta, cerrada
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_cierres');
    }
};
