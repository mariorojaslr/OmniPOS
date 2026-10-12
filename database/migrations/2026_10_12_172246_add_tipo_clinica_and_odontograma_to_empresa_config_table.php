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
        Schema::table('empresa_config', function (Blueprint $table) {
            $table->string('tipo_clinica')->default('clinica_general')->after('mod_hce'); // clinica_general, clinica_odontologica, unidad_coronaria, centro_rehabilitacion
            $table->boolean('mod_odontograma')->default(false)->after('tipo_clinica');
            $table->string('terminologia_espacio')->default('consultorio')->after('mod_odontograma'); // consultorio, sillon, box, sala
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresa_config', function (Blueprint $table) {
            $table->dropColumn(['tipo_clinica', 'mod_odontograma', 'terminologia_espacio']);
        });
    }
};
