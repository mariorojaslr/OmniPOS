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
        Schema::table('recibo_pagos', function (Blueprint $table) {
            $table->string('banco')->nullable()->after('referencia');
            $table->date('fecha_emision')->nullable()->after('banco');
            $table->date('fecha_acreditacion')->nullable()->after('fecha_emision');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recibo_pagos', function (Blueprint $table) {
            $table->dropColumn(['banco', 'fecha_emision', 'fecha_acreditacion']);
        });
    }
};
