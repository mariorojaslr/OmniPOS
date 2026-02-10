<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * RUN MIGRATION
     */
    public function up(): void
    {
        Schema::create('empresa_config', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | RELACIÓN CON EMPRESA (1 a 1)
            |--------------------------------------------------------------------------
            */
            $table->foreignId('empresa_id')
                  ->unique()
                  ->constrained('empresas')
                  ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | APARIENCIA
            |--------------------------------------------------------------------------
            */
            $table->string('logo')->nullable();

            // Colores corporativos
            $table->string('color_primary', 20)->default('#1f6feb');
            $table->string('color_secondary', 20)->default('#0d1117');

            // Tema visual
            $table->enum('theme', ['light', 'dark'])->default('light');

            /*
            |--------------------------------------------------------------------------
            | TIMESTAMPS
            |--------------------------------------------------------------------------
            */
            $table->timestamps();
        });
    }

    /**
     * ROLLBACK MIGRATION
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa_config');
    }
};
