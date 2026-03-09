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
            if (!Schema::hasColumn('purchases', 'invoice_type')) {
                $table->string('invoice_type')->nullable()->after('purchase_date');
            }
            if (!Schema::hasColumn('purchases', 'invoice_number')) {
                $table->string('invoice_number')->nullable()->after('invoice_type');
            }
            if (!Schema::hasColumn('purchases', 'status')) {
                $table->string('status')->default('confirmado')->after('payment_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['invoice_type', 'invoice_number', 'status']);
        });
    }
};
