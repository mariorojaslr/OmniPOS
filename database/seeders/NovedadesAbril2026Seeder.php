<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemUpdate;

class NovedadesAbril2026Seeder extends Seeder
{
    public function run()
    {
        // Limpiamos novedades anteriores de abril para no duplicar por título
        SystemUpdate::where('title', 'LIKE', '%Caso de Éxito%')
            ->orWhere('title', 'LIKE', '%Transformación de Materia Prima%')
            ->orWhere('title', 'LIKE', '%Motor de Facturación%')
            ->orWhere('title', 'LIKE', '%Comando Central%')
            ->delete();

        // 1. EQUIVALENCIAS INTELIGENTES (CASO RESMA)
        SystemUpdate::create([
            'title'        => '📄 Caso de Éxito: Equivalencias (Ej: Resmas de Papel)',
            'description'  => '¿Comprás por Resma pero consumís por Hojas? Ahora podés definir "Unidades de Base". Ejemplo Gráfica: - Unidad Base: Hoja (U) - Unidad de Compra: Resma (Factor 500). Al cargar el costo de 1 Resma, MultiPOS calcula automáticamente el valor de cada hoja para tus recetas.',
            'image'        => '/assets/img/novedades/unit_equivalence.png', 
            'type'         => 'Tutorial',
            'publish_date' => '2026-04-07',
        ]);

        // 2. TRANSFORMACIÓN DE PRODUCTOS (CASO EMPANADAS)
        SystemUpdate::create([
            'title'        => '🥟 Transformación de Materia Prima en Stock',
            'description'  => 'Implementamos el flujo de "Órdenes de Producción". Ahora podés convertir tus insumos en stock de venta antes de que ocurra la venta. MultiPOS resta los insumos y SUMA el producto terminado a tu stock de vitrina automáticamente.',
            'image'        => '/assets/img/novedades/production_flow.png',
            'type'         => 'Producción',
            'publish_date' => '2026-04-07',
        ]);

        // 3. FACTURACIÓN ELECTRÓNICA ARCA
        SystemUpdate::create([
            'title'        => '🚀 Motor de Facturación AFIP (ARCA)',
            'description'  => 'Implementación del nuevo protocolo ARCA para facturación electrónica. Incluye generación automática de QR fiscal, validación de CUIT en tiempo real y descarga de comprobantes en alta resolución.',
            'image'        => '/assets/img/novedades/afip_arca_engine.png', 
            'type'         => 'Fiscal',
            'publish_date' => '2026-04-02',
        ]);
        
        // 4. CRM COMANDO CENTRAL
        SystemUpdate::create([
            'title'        => '📊 Comando Central & CRM Estratégico',
            'description'  => 'Lanzamiento del tablero Kanban para gestión de clientes desde el panel OWNER. Ahora el sistema detecta renovaciones automáticas y permite monitorear la salud de cada empresa en tiempo real.',
            'image'        => '/assets/img/novedades/crm_kanban_pro.png',
            'type'         => 'Management',
            'publish_date' => '2026-04-01',
        ]);
    }
}
