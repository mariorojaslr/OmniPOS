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
        Schema::table('empresas', function (Blueprint $table) {
            $table->decimal('custom_price', 10, 2)->nullable()->after('plan_id');
            $table->integer('custom_max_products')->nullable()->after('custom_price');
            $table->integer('custom_max_users')->nullable()->after('custom_max_products');
            $table->decimal('custom_max_storage_mb', 10, 2)->nullable()->after('custom_max_users');
            $table->boolean('is_bonificated')->default(false)->after('custom_max_storage_mb');
            $table->date('grace_period_until')->nullable()->after('is_bonificated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn([
                'custom_price',
                'custom_max_products',
                'custom_max_users',
                'custom_max_storage_mb',
                'is_bonificated',
                'grace_period_until'
            ]);
        });
    }
};
