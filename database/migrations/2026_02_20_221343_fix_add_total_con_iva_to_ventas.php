<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {

            // Si NO existe, la crea (sin after)
            if (!Schema::hasColumn('ventas', 'total_con_iva')) {
                $table->decimal('total_con_iva', 15, 2)->default(0);
            }

        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {

            if (Schema::hasColumn('ventas', 'total_con_iva')) {
                $table->dropColumn('total_con_iva');
            }

        });
    }
};
