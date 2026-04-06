<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemUpdate;

class NovedadesAbril2026Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 29 MARZO - MULTIMEDIA BUNNY.NET
        SystemUpdate::updateOrCreate(
            ['title' => 'Streaming 4.0: Integración con Bunny.net'],
            [
                'publish_date' => '2026-03-29',
                'type' => 'nuevo',
                'description' => 'Hemos profesionalizado la gestión de medios. Ahora MultiPOS utiliza Bunny.net (Storage & Stream) para alojar sus videos de productos e imágenes pesadas, garantizando una carga instantánea y seguridad de nivel bancario para sus archivos multimedia.',
            ]
        );

        // 01 ABRIL - CRM Y ACTIVACIÓN
        SystemUpdate::updateOrCreate(
            ['title' => 'Centro de Comando CRM Profesional'],
            [
                'publish_date' => '2026-04-01',
                'type' => 'nuevo',
                'image' => 'novedad_crm.png',
                'description' => 'El OWNER ahora cuenta con un Tablero Kanban estratégico para gestionar leads y empresas. ¡Active clientes con un solo arrastrar y soltar! Cada activación sincroniza automáticamente la facturación y los pagos en el historial de suscripciones.',
            ]
        );

        // 05 ABRIL - AFIP ARCA
        SystemUpdate::updateOrCreate(
            ['title' => 'Facturación Electrónica ARCA (AFIP)'],
            [
                'publish_date' => '2026-04-05',
                'type' => 'nuevo',
                'image' => 'novedad_afip.png',
                'description' => '¡MultiPOS ya es legal! Integramos el servicio oficial de ARCA para emitir Facturas A, B, C y Notas de Crédito con CAE real. Incluye QR obligatorio y validez fiscal inmediata para todas sus ventas corporativas.',
            ]
        );

        // 06 ABRIL - PRODUCCIÓN Y VALORIZACIÓN
        SystemUpdate::updateOrCreate(
            ['title' => 'Producción de Elite: Recetas (BOM) y Costos'],
            [
                'publish_date' => '2026-04-06',
                'type' => 'nuevo',
                'image' => 'novedad_produccion.png',
                'description' => 'Lance su producción al siguiente nivel. Defina recetas ("Bill of Materials") para sus productos terminados y automatice el descuento de stock en cascada para sus materias primas. Además, incorporamos el reporte de Valorización de Inventario para conocer su capital real inmovilizado.',
            ]
        );
    }
}
