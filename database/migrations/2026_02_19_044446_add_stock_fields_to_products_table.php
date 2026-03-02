<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            // Stock actual (para futuro Kardex)
            if (!Schema::hasColumn('products', 'stock_actual')) {
                $table->decimal('stock_actual', 12, 2)->default(0)->after('price');
            }

            // Stock mínimo
            if (!Schema::hasColumn('products', 'stock_min')) {
                $table->decimal('stock_min', 12, 2)->default(0)->after('stock_actual');
            }

            // Stock ideal
            if (!Schema::hasColumn('products', 'stock_ideal')) {
                $table->decimal('stock_ideal', 12, 2)->default(0)->after('stock_min');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            if (Schema::hasColumn('products', 'stock_actual')) {
                $table->dropColumn('stock_actual');
            }

            if (Schema::hasColumn('products', 'stock_min')) {
                $table->dropColumn('stock_min');
            }

            if (Schema::hasColumn('products', 'stock_ideal')) {
                $table->dropColumn('stock_ideal');
            }
        });
    }
};
