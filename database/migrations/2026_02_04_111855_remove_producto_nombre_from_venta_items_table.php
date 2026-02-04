<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('venta_items', function (Blueprint $table) {
            if (Schema::hasColumn('venta_items', 'producto_nombre')) {
                $table->dropColumn('producto_nombre');
            }
        });
    }

    public function down(): void
    {
        Schema::table('venta_items', function (Blueprint $table) {
            $table->string('producto_nombre')->after('product_id');
        });
    }
};
