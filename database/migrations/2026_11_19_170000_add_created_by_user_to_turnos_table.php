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
            if (!Schema::hasColumn('turnos', 'created_by_user_id')) {
                // ID del usuario que creó el turno (médico, secretaria/asistente o recepción)
                $table->unsignedBigInteger('created_by_user_id')->nullable()->after('user_id');
                $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('turnos', 'origen_creacion')) {
                // Origen: 'medico_consulta', 'asistente_recepcion', 'web_paciente', 'whatsapp'
                $table->string('origen_creacion', 50)->default('asistente_recepcion')->after('created_by_user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('turnos', function (Blueprint $table) {
            if (Schema::hasColumn('turnos', 'created_by_user_id')) {
                $table->dropForeign(['created_by_user_id']);
                $table->dropColumn('created_by_user_id');
            }
            if (Schema::hasColumn('turnos', 'origen_creacion')) {
                $table->dropColumn('origen_creacion');
            }
        });
    }
};
