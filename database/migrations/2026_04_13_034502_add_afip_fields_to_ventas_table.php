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
            if (!Schema::hasColumn('ventas', 'qr_data')) {
                $table->text('qr_data')->nullable()->after('cae_vencimiento');
            }
            if (!Schema::hasColumn('ventas', 'afip_error')) {
                $table->text('afip_error')->nullable()->after('qr_data');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['qr_data', 'afip_error']);
        });
    }
};
