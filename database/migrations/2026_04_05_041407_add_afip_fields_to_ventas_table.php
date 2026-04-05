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
        Schema::table('ventas', function (Blueprint $table) {
            $table->string('cae', 20)->nullable()->after('numero_comprobante');
            $table->date('cae_vencimiento')->nullable()->after('cae');
            $table->text('afip_error')->nullable()->after('cae_vencimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['cae', 'cae_vencimiento', 'afip_error']);
        });
    }
};
