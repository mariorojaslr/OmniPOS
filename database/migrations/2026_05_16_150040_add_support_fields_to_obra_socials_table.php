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
        Schema::table('obras_sociales', function (Blueprint $table) {
            $table->string('api_token')->nullable()->unique()->after('activo');
            $table->json('config_json')->nullable()->after('api_token'); // Para guardar lógica de numeración tipo APOS
        });
    }

    public function down(): void
    {
        Schema::table('obras_sociales', function (Blueprint $table) {
            $table->dropColumn(['api_token', 'config_json']);
        });
    }
};
