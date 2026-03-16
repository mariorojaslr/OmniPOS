<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\SystemUpdate::create([
            'title' => '¡Estrenamos Muro de Novedades!',
            'publish_date' => '2026-03-16',
            'type' => 'nuevo',
            'description' => 'Presentamos nuestra nueva sección de "Novedades". A partir de hoy, podrá visualizar cronológicamente cada mejora, nueva función y corrección que realicemos en MultiPOS. ¡Manténgase al día con la evolución de su plataforma!',
        ]);

        \App\Models\SystemUpdate::create([
            'title' => 'Motor de Importación Masiva de Artículos',
            'publish_date' => '2026-03-16',
            'type' => 'nuevo',
            'description' => 'Hemos implementado una herramienta estratégica que le permite descargar su catálogo completo en formato CSV y volver a subirlo para actualizar precios y niveles de stock en segundos. Utiliza el separador ";" (punto y coma) para garantizar la compatibilidad total con Microsoft Excel.',
        ]);

        \App\Models\SystemUpdate::create([
            'title' => 'Optimización: Importador de Clientes',
            'publish_date' => '2026-03-15',
            'type' => 'mejora',
            'description' => 'Ya es posible migrar su base de datos de clientes desde otros sistemas de forma sencilla. El importador detecta duplicados automáticamente mediante el DNI/CUIT o Email, permitiendo limpiezas y actualizaciones masivas de datos de contacto sin generar registros redundantes.',
        ]);

        \App\Models\SystemUpdate::create([
            'title' => 'Asistente de Configuración Inteligente',
            'publish_date' => '2026-03-14',
            'type' => 'nuevo',
            'description' => 'El Dashboard principal ahora cuenta con recordatorios inteligentes que le guiarán en el proceso de configuración inicial. El sistema le notificará proactivamente si faltan datos fiscales críticos o si su inventario requiere una carga de stock inicial para operar correctamente desde el primer día.',
        ]);

        \App\Models\SystemUpdate::create([
            'title' => 'Mejoras de Estabilidad en Reportes de Ranking',
            'publish_date' => '2026-03-12',
            'type' => 'arreglo',
            'description' => 'Se han realizado ajustes técnicos en los motores de generación de reportes (Excel/PDF) para asegurar una experiencia fluida. Ahora, ante cualquier eventualidad con librerías externas de servidor, el sistema proporcionará alertas claras y alternativas de descarga seguras.',
        ]);
    }
}
