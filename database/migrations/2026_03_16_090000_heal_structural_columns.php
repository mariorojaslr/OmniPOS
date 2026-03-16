<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Esta migración sana las columnas críticas que producción ha perdido o nunca tuvo
     * para permitir el correcto funcionamiento de los reportes y la nivelación de datos.
     */
    public function up(): void
    {
        // 1. Sanar tabla VENTAS
        Schema::table('ventas', function (Blueprint $table) {
            if (!Schema::hasColumn('ventas', 'subtotal')) {
                $table->decimal('subtotal', 15, 2)->default(0)->after('cliente_condicion');
            }
            if (!Schema::hasColumn('ventas', 'total')) {
                $table->decimal('total', 15, 2)->default(0)->after('iva');
            }
        });

        // 2. Sanar tabla PRODUCTS
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'precio_sin_iva')) {
                $table->decimal('precio_sin_iva', 15, 2)->nullable();
            }
        });

        // 3. Sanar tabla VENTA_ITEMS
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
            if (!Schema::hasColumn('venta_items', 'total_item_con_iva')) {
                $table->decimal('total_item_con_iva', 15, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        // No es recomendable revertir una sanación estructural en caliente
    }
};
