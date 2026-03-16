<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'precio_sin_iva')) {
                $table->decimal('precio_sin_iva', 15, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('products', 'costo_sin_iva')) {
                $table->decimal('costo_sin_iva', 15, 2)->nullable()->after('cost');
            }
        });

        Schema::table('venta_items', function (Blueprint $table) {
            if (!Schema::hasColumn('venta_items', 'precio_unitario_sin_iva')) {
                $table->decimal('precio_unitario_sin_iva', 15, 2)->nullable();
            }
            if (!Schema::hasColumn('venta_items', 'subtotal_item_sin_iva')) {
                $table->decimal('subtotal_item_sin_iva', 15, 2)->nullable();
            }
            if (!Schema::hasColumn('venta_items', 'iva_item')) {
                $table->decimal('iva_item', 15, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['precio_sin_iva', 'costo_sin_iva']);
        });
    }
};
