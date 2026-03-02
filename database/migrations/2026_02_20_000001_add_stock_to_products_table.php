<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            if (!Schema::hasColumn('products', 'stock')) {
                $table->decimal('stock', 12, 2)->default(0)->after('price');
            }

            if (!Schema::hasColumn('products', 'stock_min')) {
                $table->decimal('stock_min', 12, 2)->default(0)->after('stock');
            }

            if (!Schema::hasColumn('products', 'stock_ideal')) {
                $table->decimal('stock_ideal', 12, 2)->default(0)->after('stock_min');
            }

        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['stock', 'stock_min', 'stock_ideal']);
        });
    }
};
