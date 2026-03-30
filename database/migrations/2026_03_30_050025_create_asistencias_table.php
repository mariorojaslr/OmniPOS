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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('empresa_id');
            $table->timestamp('entrada')->useCurrent();
            $table->timestamp('salida')->nullable();
            $table->string('ip_entrada')->nullable();
            $table->string('ip_salida')->nullable();
            $table->text('observaciones')->nullable();
            $table->decimal('vuelto_inicial', 15, 2)->default(0); // Para control de caja al entrar
            $table->decimal('vuelto_final', 15, 2)->nullable();   // Para control de caja al salir
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
