<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('venta_items', function (Blueprint $table) {
            $table->decimal('precio_unitario_sin_iva', 12, 2)->after('cantidad');
            $table->decimal('subtotal_item_sin_iva', 12, 2)->after('precio_unitario_sin_iva');
            $table->decimal('iva_item', 12, 2)->after('subtotal_item_sin_iva');
            $table->decimal('total_item_con_iva', 12, 2)->after('iva_item');
        });
    }

    public function down(): void
    {
        Schema::table('venta_items', function (Blueprint $table) {
            $table->dropColumn([
                'precio_unitario_sin_iva',
                'subtotal_item_sin_iva',
                'iva_item',
                'total_item_con_iva',
            ]);
        });
    }
};
