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
        Schema::create('inventory_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->uuid('uuid')->unique(); // El secreto del QR
            $table->unsignedBigInteger('created_by_id');
            $table->boolean('active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Relaciones
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_sessions');
    }
};
