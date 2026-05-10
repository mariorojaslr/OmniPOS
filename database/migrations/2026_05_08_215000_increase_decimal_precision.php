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
        // Aumentar precisión decimal a 4 dígitos para cantidades y precios críticos
        
        Schema::table('venta_items', function (Blueprint $table) {
            $table->decimal('cantidad', 15, 4)->change();
            $table->decimal('precio_unitario_sin_iva', 15, 4)->change();
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->decimal('quantity', 15, 4)->change();
            $table->decimal('cost', 15, 4)->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 15, 4)->change();
            $table->decimal('cost', 15, 4)->change();
            if (Schema::hasColumn('products', 'stock_actual')) {
                $table->decimal('stock_actual', 15, 4)->change();
            }
            if (Schema::hasColumn('products', 'stock_min')) {
                $table->decimal('stock_min', 15, 4)->change();
            }
        });

        Schema::table('stock_movimientos', function (Blueprint $table) {
            $table->decimal('cantidad', 15, 4)->change();
        });

        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->decimal('cantidad', 15, 4)->change();
                $table->decimal('precio', 15, 4)->change();
            });
        }
        
        if (Schema::hasTable('orden_pedido_items')) {
            Schema::table('orden_pedido_items', function (Blueprint $table) {
                $table->decimal('cantidad', 15, 4)->change();
                $table->decimal('precio_unitario', 15, 4)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No revertimos a 2 decimales para evitar pérdida de datos ya cargados con alta precisión
    }
};
