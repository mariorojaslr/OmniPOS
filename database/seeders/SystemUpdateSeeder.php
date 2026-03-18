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
        \App\Models\SystemUpdate::updateOrCreate(
            ['title' => '¡Estrenamos Muro de Novedades!'],
            [
                'publish_date' => '2026-03-16',
                'type' => 'nuevo',
                'description' => 'Presentamos nuestra nueva sección de "Novedades". A partir de hoy, podrá visualizar cronológicamente cada mejora, nueva función y corrección que realicemos en MultiPOS. ¡Manténgase al día con la evolución de su plataforma!',
            ]
        );

        \App\Models\SystemUpdate::updateOrCreate(
            ['title' => 'Motor de Importación Masiva de Artículos'],
            [
                'publish_date' => '2026-03-16',
                'type' => 'nuevo',
                'description' => 'Hemos implementado una herramienta estratégica que le permite descargar su catálogo completo en formato CSV y volver a subirlo para actualizar precios y niveles de stock en segundos. Utiliza el separador ";" (punto y coma) para garantizar la compatibilidad total con Microsoft Excel.',
            ]
        );

        \App\Models\SystemUpdate::updateOrCreate(
            ['title' => 'Optimización: Importador de Clientes'],
            [
                'publish_date' => '2026-03-15',
                'type' => 'mejora',
                'description' => 'Ya es posible migrar su base de datos de clientes desde otros sistemas de forma sencilla. El importador detecta duplicados automáticamente mediante el DNI/CUIT o Email, permitiendo limpiezas y actualizaciones masivas de datos de contacto sin generar registros redundantes.',
            ]
        );

        \App\Models\SystemUpdate::updateOrCreate(
            ['title' => 'Asistente de Configuración Inteligente'],
            [
                'publish_date' => '2026-03-14',
                'type' => 'nuevo',
                'description' => 'El Dashboard principal ahora cuenta con recordatorios inteligentes que le guiarán en el proceso de configuración inicial. El sistema le notificará proactivamente si faltan datos fiscales críticos o si su inventario requiere una carga de stock inicial para operar correctamente desde el primer día.',
            ]
        );

        \App\Models\SystemUpdate::updateOrCreate(
            ['title' => 'Rediseño de Comprobante ARCA/AFIP'],
            [
                'publish_date' => '2026-03-18',
                'type' => 'mejora',
                'description' => 'Hemos rediseñado el PDF de ventas para máxima fidelidad con los modelos oficiales (Factura A/B/C y Comprobante X). Incluye aire perimetral de 1cm, letra central detallada y diseño limpio sin renglones para optimizar espacio y tinta.',
            ]
        );

        \App\Models\SystemUpdate::updateOrCreate(
            ['title' => 'Inteligencia de Compras e Inflación'],
            [
                'publish_date' => '2026-03-18',
                'type' => 'nuevo',
                'description' => '¡El sistema ahora te ayuda a decidir tus precios! Al cargar una compra, te muestra el costo de la última compra y resalta el porcentaje de aumento, permitiéndote reaccionar rápido a la inflación.',
            ]
        );

        \App\Models\SystemUpdate::updateOrCreate(
            ['title' => 'Gestión Financiera de Proveedores'],
            [
                'publish_date' => '2026-03-18',
                'type' => 'mejora',
                'description' => 'Ahora puedes registrar pagos manuales en la cuenta corriente del proveedor y editar la información de cabecera (factura, fecha) de compras ya cargadas sin afectar el stock.',
            ]
        );

        \App\Models\SystemUpdate::updateOrCreate(
            ['title' => 'Branding Robusto (Fix Hostinger)'],
            [
                'publish_date' => '2026-03-18',
                'type' => 'mejora',
                'description' => 'Optimizamos la servidura de medios locales para asegurar que tu logo y colores corporativos se visualicen perfectamente en el Dashboard y configuración, incluso en entornos con restricciones técnicas.',
            ]
        );
    }
}
