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
        Schema::create('crm_activities', function (Blueprint $table) {
            $table->id();
            $table->string('channel'); // LinkedIn, Instagram, etc.
            $table->string('target_name')->nullable();
            $table->string('target_origin')->nullable(); // Ciudad o Red
            $table->text('details'); // Lo que se dijeron
            $table->string('status')->default('contacto_inicial'); // Interesado, Hunted, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_activities');
    }
};
