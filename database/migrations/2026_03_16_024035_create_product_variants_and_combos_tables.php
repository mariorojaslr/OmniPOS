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
        // Añadir flags a la tabla de productos
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_variants')->default(false)->after('active');
            $table->boolean('is_combo')->default(false)->after('has_variants');
        });

        // Tabla de variantes (Talles/Colores)
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->decimal('price', 15, 2)->nullable(); // Precio opcional por variante
            $table->decimal('stock', 15, 2)->default(0);
            $table->timestamps();
        });

        // Tabla de combos (Un producto compuesto por otros)
        Schema::create('product_combos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('child_product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('quantity', 15, 2)->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_combos');
        Schema::dropIfExists('product_variants');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['has_variants', 'is_combo']);
        });
    }
};
