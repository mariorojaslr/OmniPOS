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
        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->default('prospecto')->after('role'); // prospecto, pendiente_pago, activo, suspendido
            $table->string('lead_source')->nullable()->after('status'); // de donde vino
            $table->string('country')->nullable()->after('lead_source'); 
            $table->text('crm_notes')->nullable()->after('country');
            $table->string('payment_voucher')->nullable()->after('crm_notes'); // Ruta al comprobante de pago
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
