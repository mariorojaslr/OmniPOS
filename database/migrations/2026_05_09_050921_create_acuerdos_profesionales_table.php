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
        Schema::create('acuerdos_profesionales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            
            $table->enum('tipo_comision', ['porcentaje', 'monto_fijo'])->default('porcentaje');
            $table->decimal('valor', 15, 2)->default(0);

            $table->timestamps();

            // Evitar duplicados de acuerdo para el mismo profesional y servicio
            $table->unique(['user_id', 'servicio_id'], 'user_servicio_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acuerdos_profesionales');
    }
};
