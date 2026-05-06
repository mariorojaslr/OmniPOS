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
        Schema::create('orden_pedido_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orden_pedido_id');
            // $table->foreign('orden_pedido_id')->references('id')->on('ordenes_pedido')->onDelete('cascade');
            
            $table->unsignedBigInteger('product_id')->nullable();
            // $table->foreign('product_id')->references('id')->on('products');
            
            $table->unsignedBigInteger('variant_id')->nullable();
            // $table->foreign('variant_id')->references('id')->on('product_variants');
            
            $table->string('descripcion');
            $table->decimal('cantidad', 15, 2);
            $table->decimal('precio_unitario', 15, 2);
            $table->decimal('precio_anterior', 15, 2)->nullable();
            $table->text('instrucciones')->nullable();
            $table->decimal('subtotal', 15, 2);
            $table->boolean('is_manual')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_pedido_items');
    }
};
