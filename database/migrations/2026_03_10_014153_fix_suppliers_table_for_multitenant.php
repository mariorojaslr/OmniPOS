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
        Schema::table('suppliers', function (Blueprint $table) {
            if (!Schema::hasColumn('suppliers', 'empresa_id')) {
                $table->foreignId('empresa_id')->nullable()->constrained('empresas')->onDelete('cascade');
            }
            if (!Schema::hasColumn('suppliers', 'document')) {
                $table->string('document', 20)->nullable();
            }
            if (!Schema::hasColumn('suppliers', 'cuit')) {
                $table->string('cuit', 20)->nullable();
            }
            if (!Schema::hasColumn('suppliers', 'direccion')) {
                $table->string('direccion')->nullable();
            }
            if (!Schema::hasColumn('suppliers', 'condicion_iva')) {
                $table->string('condicion_iva', 50)->nullable();
            }
            if (!Schema::hasColumn('suppliers', 'tipo_factura_default')) {
                $table->string('tipo_factura_default', 5)->nullable();
            }
            if (!Schema::hasColumn('suppliers', 'saldo')) {
                $table->decimal('saldo', 15, 2)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            if (Schema::hasColumn('suppliers', 'empresa_id')) {
                $table->dropForeign(['empresa_id']);
                $table->dropColumn('empresa_id');
            }
            if (Schema::hasColumn('suppliers', 'document'))
                $table->dropColumn('document');
            if (Schema::hasColumn('suppliers', 'cuit'))
                $table->dropColumn('cuit');
            if (Schema::hasColumn('suppliers', 'direccion'))
                $table->dropColumn('direccion');
            if (Schema::hasColumn('suppliers', 'condicion_iva'))
                $table->dropColumn('condicion_iva');
            if (Schema::hasColumn('suppliers', 'tipo_factura_default'))
                $table->dropColumn('tipo_factura_default');
            if (Schema::hasColumn('suppliers', 'saldo'))
                $table->dropColumn('saldo');
        });
    }
};
