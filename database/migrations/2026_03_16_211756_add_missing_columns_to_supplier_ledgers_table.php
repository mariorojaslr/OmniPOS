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
        Schema::table('supplier_ledgers', function (Blueprint $table) {
            if (!Schema::hasColumn('supplier_ledgers', 'empresa_id')) {
                $table->unsignedBigInteger('empresa_id')->after('id');
            }
            if (!Schema::hasColumn('supplier_ledgers', 'supplier_id')) {
                $table->unsignedBigInteger('supplier_id')->after('empresa_id');
            }
            if (!Schema::hasColumn('supplier_ledgers', 'type')) {
                $table->enum('type', ['debit', 'credit'])->after('supplier_id');
            }
            if (!Schema::hasColumn('supplier_ledgers', 'amount')) {
                $table->decimal('amount', 15, 2)->after('type');
            }
            if (!Schema::hasColumn('supplier_ledgers', 'description')) {
                $table->string('description')->nullable()->after('amount');
            }
            if (!Schema::hasColumn('supplier_ledgers', 'paid')) {
                $table->boolean('paid')->default(false)->after('description');
            }

            // Las FKs ya existen en la tabla base según inspección.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_ledgers', function (Blueprint $table) {
            //
        });
    }
};
