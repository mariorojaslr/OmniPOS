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
            $table->foreignId('finanza_cuenta_id')->nullable()->after('recibo_id')->constrained('finanzas_cuentas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recibo_pagos', function (Blueprint $table) {
            $table->dropForeign(['finanza_cuenta_id']);
            $table->dropColumn('finanza_cuenta_id');
        });
    }
};
