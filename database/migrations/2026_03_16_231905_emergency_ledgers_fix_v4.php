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
        if (Schema::hasTable('supplier_ledgers')) {
            // Agregamos empresa_id si falta
            if (!Schema::hasColumn('supplier_ledgers', 'empresa_id')) {
                try {
                    Schema::table('supplier_ledgers', function (Blueprint $table) {
                        $table->unsignedBigInteger('empresa_id')->after('id')->nullable();
                    });
                } catch (\Exception $e) {}
            }

            // Agregamos supplier_id si falta
            if (!Schema::hasColumn('supplier_ledgers', 'supplier_id')) {
                try {
                    Schema::table('supplier_ledgers', function (Blueprint $table) {
                        $table->unsignedBigInteger('supplier_id')->after('empresa_id')->nullable();
                    });
                } catch (\Exception $e) {}
            }

            // Agregamos type si falta
            if (!Schema::hasColumn('supplier_ledgers', 'type')) {
                try {
                    Schema::table('supplier_ledgers', function (Blueprint $table) {
                        $table->enum('type', ['debit', 'credit'])->after('supplier_id')->nullable();
                    });
                } catch (\Exception $e) {}
            }

            // Agregamos amount si falta
            if (!Schema::hasColumn('supplier_ledgers', 'amount')) {
                try {
                    Schema::table('supplier_ledgers', function (Blueprint $table) {
                        $table->decimal('amount', 15, 2)->after('type')->default(0);
                    });
                } catch (\Exception $e) {}
            }

            // Agregamos description si falta
            if (!Schema::hasColumn('supplier_ledgers', 'description')) {
                try {
                    Schema::table('supplier_ledgers', function (Blueprint $table) {
                        $table->string('description')->nullable()->after('amount');
                    });
                } catch (\Exception $e) {}
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
