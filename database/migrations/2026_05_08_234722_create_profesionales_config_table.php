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
        Schema::create('profesionales_config', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('tipo_contrato')->default('fijo'); // fijo, comision, mixto
            $table->decimal('sueldo_base', 15, 4)->default(0);
            $table->string('tipo_comision')->default('porcentaje'); // porcentaje, fijo
            $table->decimal('valor_comision', 15, 4)->default(0);
            $table->string('token_portal')->unique()->nullable();
            $table->json('especialidades')->nullable(); // Para filtrar qué puede hacer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profesionales_config');
    }
};
