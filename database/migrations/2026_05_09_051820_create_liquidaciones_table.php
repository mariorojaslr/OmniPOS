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
        Schema::create('liquidaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->date('fecha_emision');
            $table->decimal('monto_total', 15, 2);
            $table->date('periodo_desde');
            $table->date('periodo_hasta');
            
            $table->enum('estado', ['pendiente', 'pagado', 'anulado'])->default('pendiente');
            $table->string('metodo_pago')->nullable();
            $table->text('notas')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liquidaciones');
    }
};
