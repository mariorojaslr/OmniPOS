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
        Schema::table('empresas', function (Blueprint $table) {

            // Fecha de cierre contable anual (AFIP)
            $table->date('fecha_cierre_ejercicio')
                  ->nullable()
                  ->after('updated_at')
                  ->comment('Fecha cierre contable anual');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {

            $table->dropColumn('fecha_cierre_ejercicio');

        });
    }
};
