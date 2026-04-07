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

        // 1. EQUIVALENCIAS INTELIGENTES (CASO RESMA)
        SystemUpdate::create([
            'title' => '📄 Caso de Éxito: Equivalencias (Ej: Resmas de Papel)',
            'content' => '¿Comprás por Resma pero consumís por Hojas? Ahora podés definir "Unidades de Base". 
            
Ejemplo Gráfica:
- Unidad Base: Hoja (U)
- Unidad de Compra: Resma (Factor 500)
            
Al cargar el costo de 1 Resma, MultiPOS calcula automáticamente el valor de cada hoja para tus recetas, garantizando que el costo de producción sea 100% exacto.',
            'image_url' => '/assets/img/novedades/unit_equivalence.png', 
            'version' => 'v2.4.4',
            'publish_date' => '2026-04-07',
            'category' => 'Tutorial',
            'is_active' => true
        ]);

        // 2. TRANSFORMACIÓN DE PRODUCTOS (CASO EMPANADAS)
        SystemUpdate::create([
            'title' => '🥟 Transformación de Materia Prima en Stock',
            'content' => 'Implementamos el flujo de "Órdenes de Producción". Ahora podés convertir tus insumos en stock de venta antes de que ocurra la venta.
            
Flujo de Trabajo:
1. Definís la Receta (Carne, Papa, Masa).
2. Generás un "Lote de Producción" (Ej: 100 unidades).
3. MultiPOS resta la carne/papa y SUMA las 100 empanadas a tu stock de vitrina.
            
Esto te permite tener un control quirúrgico del inventario real en mostrador y saber exactamente qué margen de utilidad (Markup) estás ganando sobre el costo transformado.',
            'image_url' => '/assets/img/novedades/production_flow.png',
            'version' => 'v2.4.5',
            'publish_date' => '2026-04-07',
            'category' => 'Producción',
            'is_active' => true
        ]);

        // Mantenemos las de CRM y AFIP que ya estaban
        SystemUpdate::create([
            'title' => '🚀 Motor de Facturación AFIP (ARCA)',
            'content' => 'Implementación del nuevo protocolo ARCA para facturación electrónica. Incluye generación automática de QR fiscal, validación de CUIT en tiempo real y descarga de comprobantes en alta resolución.',
            'image_url' => '/assets/img/novedades/afip_arca_engine.png', 
            'version' => 'v2.4.1',
            'publish_date' => '2026-04-02',
            'category' => 'Fiscal',
            'is_active' => true
        ]);
        
        SystemUpdate::create([
            'title' => '📊 Comando Central & CRM Estratégico',
            'content' => 'Lanzamiento del tablero Kanban para gestión de clientes desde el panel OWNER. Ahora el sistema detecta renovaciones automáticas y permite monitorear la salud de cada empresa en tiempo real.',
            'image_url' => '/assets/img/novedades/crm_kanban_pro.png',
            'version' => 'v2.4.2',
            'publish_date' => '2026-04-01',
            'category' => 'Management',
            'is_active' => true
        ]);
    }
}
