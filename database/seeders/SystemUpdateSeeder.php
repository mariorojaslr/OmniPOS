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

        \App\Models\SystemUpdate::updateOrCreate(
            ['title' => 'Optimización de Logística: Etiquetas QR'],
            [
                'publish_date' => '2026-03-18',
                'type' => 'arreglo',
                'description' => 'Hemos corregido la generación de códigos QR en las etiquetas de envío (cajas). Ahora el sistema utiliza una API dedicada para garantizar que el QR esté siempre visible y listo para ser escaneado por el control interno.',
            ]
        );
        \App\Models\SystemUpdate::updateOrCreate(
            ['title' => 'Logística 4.0: Barcode & Inventario Colaborativo'],
            [
                'publish_date' => '2026-03-19',
                'type' => 'nuevo',
                'image' => 'hero_scanner_mobile.png',
                'description' => '¡MultiPOS se vuelve profesional! Implementamos el escaneo de código de barras real en el POS y la carga masiva de stock mediante "Sesiones Colaborativas": ahora cualquier colaborador puede usar su celular como un escáner de stock simplemente escaneando un QR temporal. Además, habilitamos el Hub de Etiquetas para generar e imprimir sus propios códigos de barras en formato PDF.',
            ]
        );

        \App\Models\SystemUpdate::updateOrCreate(
            ['title' => 'Evolución Visual: Catálogo Nuclear Fluid 100%'],
            [
                'publish_date' => '2026-03-21',
                'type' => 'mejora',
                'description' => 'Hemos rediseñado el catálogo público para ofrecer una experiencia inmersiva y ultrarrápida. El nuevo diseño utiliza una retícula inteligente que aprovecha el 100% de cualquier pantalla, acompañada de una navegación Glassmorphism y filtros dinámicos premium. ¡Su vitrina digital nunca se vio tan profesional!',
            ]
        );
    }
}
