<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {

            $table->string('condicion_iva', 50)
                  ->default('responsable_inscripto')
                  ->after('name');

            $table->string('tipo_factura_default', 2)
                  ->default('A')
                  ->after('condicion_iva');

            $table->string('cuit', 20)->nullable()->after('document');

            $table->string('direccion', 255)->nullable()->after('cuit');

            $table->decimal('saldo', 14, 2)
                  ->default(0)
                  ->after('direccion');
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn([
                'condicion_iva',
                'tipo_factura_default',
                'cuit',
                'direccion',
                'saldo'
            ]);
        });
    }
};
