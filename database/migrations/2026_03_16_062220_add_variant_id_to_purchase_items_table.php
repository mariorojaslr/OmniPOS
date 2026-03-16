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
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->nullable()->after('id');
            $table->unsignedBigInteger('variant_id')->nullable()->after('product_id');
            $table->decimal('iva', 12, 2)->default(0)->after('cost');

            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropColumn(['empresa_id', 'variant_id', 'iva']);
        });
    }
};
