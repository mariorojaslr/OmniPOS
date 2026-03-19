<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('barcode')->nullable()->after('rubro_id')->comment('Código de barras EAN-13, EAN-8, UPC, etc.');
            $table->index(['empresa_id', 'barcode'], 'idx_products_barcode');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasTable('product_variants')) {
                $table->string('barcode')->nullable()->after('price')->comment('Código de barras específico de la variante');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_barcode');
            $table->dropColumn('barcode');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasColumn('product_variants', 'barcode')) {
                $table->dropColumn('barcode');
            }
        });
    }
};
