<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemUpdate;

class NovedadesAbril2026Seeder extends Seeder
{
    public function run()
    {
        // Limpiamos novedades anteriores de abril para no duplicar
        SystemUpdate::where('version', 'LIKE', 'v2.4%')->delete();

        // 1. FACTURACIÓN ELECTRÓNICA ARCA
        SystemUpdate::create([
            'title' => '🚀 Motor de Facturación AFIP (ARCA)',
            'content' => 'Implementación del nuevo protocolo ARCA para facturación electrónica. Incluye generación automática de QR fiscal, validación de CUIT en tiempo real y descarga de comprobantes en alta resolución.',
            'image_url' => '/assets/img/novedades/afip_arca_engine.png', 
            'version' => 'v2.4.1',
            'publish_date' => '2026-03-29',
            'category' => 'Fiscal',
            'is_active' => true
        ]);

        // 2. CRM COMANDO CENTRAL
        SystemUpdate::create([
            'title' => '📊 Comando Central & CRM Estratégico',
            'content' => 'Lanzamiento del tablero Kanban para gestión de clientes desde el panel OWNER. Ahora el sistema detecta renovaciones automáticas y permite monitorear la salud de cada empresa en tiempo real.',
            'image_url' => '/assets/img/novedades/crm_kanban_pro.png',
            'version' => 'v2.4.2',
            'publish_date' => '2026-04-01',
            'category' => 'Management',
            'is_active' => true
        ]);

        // 3. RECETAS Y PRODUCCIÓN (BOM)
        SystemUpdate::create([
            'title' => '🧁 Producción Inteligente: Recetas (BOM)',
            'content' => 'Módulo de recetas avanzado con "Cascada de Stock". Al vender un producto terminado, el sistema descuenta automáticamente sus ingredientes. Incluye cálculo de rentabilidad neta "Smart Costing".',
            'image_url' => '/assets/img/novedades/production_bom_module.png',
            'version' => 'v2.4.3',
            'publish_date' => '2026-04-06',
            'category' => 'Producción',
            'is_active' => true
        ]);
    }
}
