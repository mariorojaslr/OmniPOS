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
        // 1. SANAR RUBROS
        if (!Schema::hasTable('rubros')) {
            Schema::create('rubros', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('empresa_id');
                $table->string('nombre');
                $table->boolean('activo')->default(true);
                $table->timestamps();
                $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            });
        }

        // 2. SANAR PRODUCTS (RUBRO_ID)
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'rubro_id')) {
                    $table->unsignedBigInteger('rubro_id')->nullable()->after('empresa_id');
                    try {
                        $table->foreign('rubro_id')->references('id')->on('rubros')->onDelete('set null');
                    } catch (\Exception $e) {}
                }
            });
        }

        // 3. SANAR SUPPLIER_LEDGERS
        if (Schema::hasTable('supplier_ledgers')) {
            Schema::table('supplier_ledgers', function (Blueprint $table) {
                if (!Schema::hasColumn('supplier_ledgers', 'type')) {
                    $table->enum('type', ['debit', 'credit'])->after('id')->nullable();
                }
                if (!Schema::hasColumn('supplier_ledgers', 'amount')) {
                    $table->decimal('amount', 15, 2)->after('type')->default(0);
                }
                if (!Schema::hasColumn('supplier_ledgers', 'empresa_id')) {
                    $table->unsignedBigInteger('empresa_id')->after('id')->nullable();
                }
                if (!Schema::hasColumn('supplier_ledgers', 'supplier_id')) {
                    $table->unsignedBigInteger('supplier_id')->after('empresa_id')->nullable();
                }
            });
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
