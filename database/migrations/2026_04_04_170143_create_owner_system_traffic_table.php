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
        // Tabla de agregación (Solo 1 registro por día para evitar sobrecarga)
        Schema::create('owner_system_traffic', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique(); // Fecha del registro
            $table->unsignedInteger('landing_visits')->default(0);
            $table->unsignedInteger('demo_clicks')->default(0);
            $table->unsignedInteger('bot_referrals')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_system_traffic');
    }
};
