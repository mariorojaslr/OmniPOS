<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemUpdate;
use Carbon\Carbon;

class NovedadesMarzo2026Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $novedades = [
            [
                'title'         => '🚀 Magic Scan Pro 4.0: Tu celular es un escáner',
                'description'   => 'Hemos optimizado el motor de escaneo móvil. Ahora la cámara de cualquier smartphone procesa códigos de barras en milisegundos con una precisión industrial.',
                'type'          => 'Mejora de Función',
                'publish_date'  => Carbon::parse('2026-03-19'),
                'link_tutorial' => '#'
            ],
            [
                'title'         => '🤝 Inventario Colaborativo por QR',
                'description'   => 'Novedad absoluta: Habilitá sesiones de stock por QR para que todo tu equipo escanee al mismo tiempo con sus propios teléfonos. Sincronización en tiempo real.',
                'type'          => 'Nueva Característica',
                'publish_date'  => Carbon::parse('2026-03-19'),
                'link_tutorial' => '#'
            ],
            [
                'title'         => '✨ Acceso Demo Instantáneo para Clientes',
                'description'   => 'Facilitamos la prueba de MultiPOS. Ahora con solo un clic podrás mostrar a tus clientes potenciales toda la potencia de la plataforma sin registro.',
                'type'          => 'Estrategia de Venta',
                'publish_date'  => Carbon::parse('2026-03-19'),
                'link_tutorial' => '#'
            ],
            [
                'title'         => '🧹 Reset de Datos de Prueba en 1 Clic',
                'description'   => 'Nueva herramienta interna para el OWNER. Ahora podés limpiar transacciones de prueba de una empresa para que empiece de cero real sin tocar su catálogo.',
                'type'          => 'Utilidad de Sistema',
                'publish_date'  => Carbon::parse('2026-03-19'),
                'link_tutorial' => '#'
            ],
            [
                'title'         => '🖨️ Etiquetas PDF Optimizadas y Centradas',
                'description'   => 'Rediseñamos el generador de etiquetas. Ahora con un motor HTML para que tus códigos de barras se impriman nítidos, centrados y 100% legibles.',
                'type'          => 'Mejora Visual',
                'publish_date'  => Carbon::parse('2026-03-19'),
                'link_tutorial' => '#'
            ],
            [
                'title'         => '🖼️ Nueva Landing Page 4.0 Premium',
                'description'   => 'Renovamos nuestra imagen pública con secciones avanzadas de Reportes 360, Gestión de Proveedores y Gastos con estética premium.',
                'type'          => 'Design Update',
                'publish_date'  => Carbon::parse('2026-03-19'),
                'link_tutorial' => '#'
            ]
        ];

        foreach ($novedades as $n) {
            // Evitar duplicados por título
            SystemUpdate::updateOrCreate(['title' => $n['title']], $n);
        }
    }
}
