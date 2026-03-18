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
            $table->string('arca_cuit')->nullable()->after('cuit');
            $table->string('arca_punto_venta')->nullable()->after('punto_venta');
            $table->string('arca_certificado')->nullable()->after('arca_punto_venta');
            $table->string('arca_llave')->nullable()->after('arca_certificado');
            $table->string('arca_ambiente')->default('homologacion')->after('arca_llave'); // homologacion, produccion
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn([
                'arca_cuit',
                'arca_punto_venta',
                'arca_certificado',
                'arca_llave',
                'arca_ambiente'
            ]);
        });
    }
};
