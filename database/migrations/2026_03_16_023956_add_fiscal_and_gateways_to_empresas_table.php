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
            $table->string('cuit')->nullable()->after('razon_social');
            $table->string('condicion_iva')->nullable()->after('cuit'); // Monotributista, Responsable Inscripto, etc.
            $table->string('iibb')->nullable()->after('condicion_iva');
            $table->date('inicio_actividades')->nullable()->after('iibb');
            $table->integer('punto_venta')->default(1)->after('inicio_actividades');
            $table->string('direccion_fiscal')->nullable()->after('punto_venta');
            
            // Configuración de pasarelas de pago (JSON)
            // Estructura sugerida: {"mercadopago": true, "mobex": false, "paypal": false, "stripe": false}
            $table->json('config_pasarelas')->nullable()->after('direccion_fiscal');
            
            $table->integer('dia_cierre_periodo')->default(0)->after('config_pasarelas'); // 1-31
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn([
                'cuit',
                'condicion_iva',
                'iibb',
                'inicio_actividades',
                'punto_venta',
                'direccion_fiscal',
                'config_pasarelas',
                'dia_cierre_periodo'
            ]);
        });
    }
};
