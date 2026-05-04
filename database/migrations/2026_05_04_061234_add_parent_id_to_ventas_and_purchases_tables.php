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
        Schema::table('ventas', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('client_id')->comment('ID de la venta original en caso de ser NC');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('supplier_id')->comment('ID de la compra original en caso de ser NC');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas_and_purchases_tables', function (Blueprint $table) {
            //
        });
    }
};
