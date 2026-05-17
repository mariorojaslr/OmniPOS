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
        Schema::create('reseller_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('brand_name')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('primary_color', 20)->default('#4f46e5'); // Ej: un azul indigo por defecto
            $table->decimal('owner_commission_percentage', 5, 2)->default(10.00); // 10.00%
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseller_configs');
    }
};
