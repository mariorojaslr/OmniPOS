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
        Schema::create('medical_certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id')->index();
            $table->unsignedBigInteger('client_id')->index(); // Paciente
            $table->unsignedBigInteger('user_id')->index(); // Médico emisor
            $table->string('codigo_validacion')->unique()->index();
            $table->string('tipo');
            $table->text('contenido');
            $table->date('vigencia_hasta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_certificates');
    }
};
