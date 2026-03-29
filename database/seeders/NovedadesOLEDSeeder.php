<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemUpdate;

class NovedadesOLEDSeeder extends Seeder
{
    /**
     * Corremos las novedades para la versión OLED y Gestión de Pedidos.
     */
    public function run(): void
    {
        // 1. Gestión de Pedidos OLED
        SystemUpdate::updateOrCreate(
            ['title' => 'Gestión de Pedidos OLED: Experiencia Rolls-Royce'],
            [
                'publish_date' => '2026-03-29',
                'type' => 'nuevo',
                'image' => 'novedad_pedidos_oled.png',
                'description' => 'Hemos profesionalizado al 100% la gestión de pedidos por catálogo. El nuevo panel administrativo utiliza una estética "negro absoluto" con tarjetas cristalinas y estados logísticos inteligentes. Además, hemos eliminado los mensajes simples: ahora el cliente recibe una página de éxito inmersiva con un botón directo a su WhatsApp para contactarle instantáneamente.',
            ]
        );

        // 2. Impresión de Etiquetas Inteligente
        SystemUpdate::updateOrCreate(
            ['title' => 'Etiquetado 2.0: Tamaños Dinámicos y Hojas Completas'],
            [
                'publish_date' => '2026-03-29',
                'type' => 'mejora',
                'image' => 'novedad_etiquetas_premium.png',
                'description' => 'El generador de etiquetas ahora es mucho más potente. Incorporamos 3 tamaños estándar (Grande, Mediano, Chico) y una nueva función para "Llenar Hojas Completas" de forma automática. Ahora el sistema calcula cuántas etiquetas entran en una hoja A4 según el recorte elegido, ideal para preparar envíos y depósitos con su marca profesional de forma masiva.',
            ]
        );
    }
}
