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
        Schema::create('recibo_pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recibo_id')->constrained()->onDelete('cascade');
            $table->string('metodo_pago');
            $table->decimal('monto', 12, 2);
            $table->string('referencia')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recibo_pagos');
    }
};
