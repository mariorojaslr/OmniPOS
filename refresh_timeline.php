<?php

use App\Models\SystemUpdate;
use Carbon\Carbon;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Borramos lo que haya para reconstruir limpio
SystemUpdate::truncate();

$updates = [
    [
        'title' => '💎 Identidad Visual Premium (v4.0)',
        'description' => 'Hemos renovado totalmente la marca MultiPOS. Nuevo logo metálico Silver & Gold con estética Glassmorphism.',
        'publish_date' => Carbon::now(),
        'type' => 'feature',
        'link_tutorial' => '#', // Opcional
    ],
    [
        'title' => '📦 Módulo de Logística e Inventario',
        'description' => 'Control total sobre tu stock con alertas inteligentes y gestión de rubros.',
        'publish_date' => Carbon::now()->subDays(1),
        'type' => 'feature',
    ],
    [
        'title' => '📊 Analítica y Reportes Avanzados',
        'description' => 'Visualizá la rentabilidad real de tu negocio con gráficos interactivos.',
        'publish_date' => Carbon::now()->subDays(2),
        'type' => 'improvement',
    ],
    [
        'title' => '💸 Gestión de Gastos Operativos',
        'description' => 'Registrá salidas de dinero (Sueldos, Alquiler, Luz) y adjuntá comprobantes con Ctrl+V.',
        'publish_date' => Carbon::now()->subDays(3),
        'type' => 'feature',
    ]
];

foreach ($updates as $data) {
    SystemUpdate::create($data);
}

echo "✅ Línea de tiempo actualizada correctamente.";
