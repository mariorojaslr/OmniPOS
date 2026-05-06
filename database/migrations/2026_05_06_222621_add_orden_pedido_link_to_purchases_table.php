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
        Schema::table('purchases', function (Blueprint $table) {
            $table->foreignId('orden_pedido_id')->nullable()->constrained('ordenes_pedido')->onDelete('set null');
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->text('instrucciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropColumn('instrucciones');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['orden_pedido_id']);
            $table->dropColumn('orden_pedido_id');
        });
    }
};
