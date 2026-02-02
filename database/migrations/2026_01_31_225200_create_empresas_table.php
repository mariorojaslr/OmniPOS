<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();

            // Datos principales
            $table->string('nombre_comercial');
            $table->string('razon_social')->nullable();

            // Contacto
            $table->string('email')->nullable();
            $table->string('telefono', 50)->nullable();

            // Estado
            $table->boolean('activo')->default(true);
            $table->date('fecha_vencimiento')->nullable();

            // Config futura
            $table->json('configuracion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
