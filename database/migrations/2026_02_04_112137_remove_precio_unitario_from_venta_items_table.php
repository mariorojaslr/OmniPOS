<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('venta_items', function (Blueprint $table) {
            if (Schema::hasColumn('venta_items', 'precio_unitario')) {
                $table->dropColumn('precio_unitario');
            }
        });
    }

    public function down(): void
    {
        Schema::table('venta_items', function (Blueprint $table) {
            $table->decimal('precio_unitario', 12, 2)->after('cantidad');
        });
    }
};
