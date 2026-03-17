<?php

use App\Models\SystemUpdate;
use Carbon\Carbon;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$updates = [
    [
        'title' => '📦 Gestión de Rubros (Categorías)',
        'description' => "¡Ya puedes organizar tus productos de forma profesional! \n\nHemos implementado la gestión completa de Rubros. Ahora puedes crear categorías personalizadas para clasificar tu inventario, facilitando la búsqueda y la generación de reportes específicos.",
        'type' => 'nuevo',
        'publish_date' => '2026-03-16',
        'link_tutorial' => 'https://www.youtube.com/embed/dQw4w9WgXcQ' // Ejemplo para testear el reproductor
    ],
    [
        'title' => '🚀 Actualización Masiva de Precios',
        'description' => "Administra la inflación en segundos. \n\nNueva herramienta que permite actualizar precios de forma masiva por rubro, aplicando porcentajes o montos fijos. Ahorra horas de trabajo manual y mantén tus márgenes de ganancia siempre al día.",
        'type' => 'nuevo',
        'publish_date' => '2026-03-16',
        'link_tutorial' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
    ],
    [
        'title' => '🛡️ Sanación Indestructible de Base de Datos',
        'description' => "Tu tranquilidad es nuestra prioridad. \n\nImplementamos un motor de sanación automática que detecta y corrige cualquier inconsistencia en la estructura de tu base de datos. Se acabaron los errores de 'columna no encontrada'. El sistema ahora es más robusto y resistente que nunca.",
        'type' => 'mejora',
        'publish_date' => '2026-03-16'
    ],
    [
        'title' => '💳 Notas de Crédito y Devoluciones',
        'description' => "Ciclo comercial completo. \n\nAhora puedes registrar devoluciones de clientes en el POS y notas de crédito de proveedores en Compras. El sistema revierte automáticamente el stock y ajusta los saldos en las cuentas corrientes de forma inteligente.",
        'type' => 'nuevo',
        'publish_date' => '2026-03-16',
        'link_tutorial' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
    ]
];

foreach ($updates as $data) {
    if (!SystemUpdate::where('title', $data['title'])->exists()) {
        SystemUpdate::create($data);
        echo "Añadido: " . $data['title'] . "\n";
    }
}

echo "Proceso finalizado.\n";
