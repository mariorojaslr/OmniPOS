<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orden_pago_pagos', function (Blueprint $table) {
            $table->unsignedBigInteger('finanza_cuenta_id')->nullable()->after('orden_pago_id');
            $table->foreign('finanza_cuenta_id')->references('id')->on('finanzas_cuentas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('orden_pago_pagos', function (Blueprint $table) {
            $table->dropForeign(['finanza_cuenta_id']);
            $table->dropColumn('finanza_cuenta_id');
        });
    }
};
