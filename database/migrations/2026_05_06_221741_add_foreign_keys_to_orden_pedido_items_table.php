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
        Schema::table('orden_pedido_items', function (Blueprint $table) {
            $table->foreign('orden_pedido_id')->references('id')->on('ordenes_pedido')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('variant_id')->references('id')->on('product_variants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orden_pedido_items', function (Blueprint $table) {
            $table->dropForeign(['orden_pedido_id']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['variant_id']);
        });
    }
};
