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
        Schema::table('turnos', function (Blueprint $table) {
            if (!Schema::hasColumn('turnos', 'hora_arribo')) {
                $table->time('hora_arribo')->nullable();
            }
            if (!Schema::hasColumn('turnos', 'estado_cola')) {
                $table->string('estado_cola', 30)->default('pendiente')->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('turnos', function (Blueprint $table) {
            $table->dropColumn(['hora_arribo', 'estado_cola']);
        });
    }
};
