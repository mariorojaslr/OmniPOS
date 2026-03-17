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
        'content' => '### ¿Qué hay de nuevo?
- **Universal Branding:** Nuevo isotipo aplicado a Favicons y Dashboard.
- **Landing Page Pro:** Una nueva cara para vender el sistema con secciones de Logística y Reportes.
- **Login Renovado:** Interfaz de acceso con el nuevo escudo oficial.

**Cómo se hace:** No tenés que hacer nada, ¡ya está aplicado en todo el sistema! Solo disfrutá de la nueva imagen profesional.',
        'version' => '4.0.0',
        'type' => 'feature',
        'released_at' => Carbon::now(),
    ],
    [
        'title' => '📦 Módulo de Logística e Inventario',
        'description' => 'Control total sobre tu stock con alertas inteligentes y gestión de rubros.',
        'content' => '### Potencia tu Almacén
- **Alertas de Stock:** El sistema te avisa cuando un producto llega al mínimo.
- **Rubros y Marcas:** Clasificación avanzada para encontrar todo en segundos.
- **Auditoría de Movimientos:** Seguimiento de entradas y salidas.

**Cómo se hace:** 
1. Entrá a **Productos** > **Stock**.
2. Configurá la "Alerta Mínima" en cada producto.
3. El dashboard te mostrará un aviso rojo cuando sea hora de reponer.',
        'version' => '3.9.0',
        'type' => 'feature',
        'released_at' => Carbon::now()->subDays(1),
    ],
    [
        'title' => '📊 Analítica y Reportes Avanzados',
        'description' => 'Visualizá la rentabilidad real de tu negocio con gráficos interactivos.',
        'content' => '### Decisiones basadas en datos
- **Margen de Ganancia:** Cálculo automático de utilidad neta.
- **Ranking de Productos:** Conocé cuáles son tus "estrellas".
- **Filtros Temporales:** Compará ventas entre meses o semanas.

**Cómo se hace:** 
Visitá la nueva sección de **Reportes**. Podés exportar los datos o ver los gráficos en tiempo real para saber cuánto estás ganando realmente hoy.',
        'version' => '3.8.5',
        'type' => 'improvement',
        'released_at' => Carbon::now()->subDays(2),
    ],
    [
        'title' => '💸 Gestión de Gastos Operativos',
        'description' => 'Registrá salidas de dinero (Sueldos, Alquiler, Luz) y adjuntá comprobantes con Ctrl+V.',
        'content' => '### Control Financiero Total
- **Categorías Custom:** Creá tus propias categorías de gastos.
- **Fotos Instantáneas:** Pegá la imagen del ticket directamente desde el portapapeles.
- **Balance Integrado:** Los gastos se restan automáticamente de tu utilidad.

**Cómo se hace:**
1. Andá al menú **Gastos**.
2. Dale a **Nuevo Gasto**.
3. Si tenés la foto del ticket, hacé **Ctrl + V** dentro del formulario y se guardará sola.',
        'version' => '3.8.0',
        'type' => 'feature',
        'released_at' => Carbon::now()->subDays(3),
    ]
];

foreach ($updates as $data) {
    SystemUpdate::create($data);
}

echo "✅ Línea de tiempo actualizada con el Branding v4.0 y nuevos módulos.";
