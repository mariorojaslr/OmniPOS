<?php

use App\Models\SystemUpdate;
use Carbon\Carbon;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Borramos lo que hay para poner lo nuevo completo y ordenado
SystemUpdate::truncate();

$updates = [
    [
        'title' => '💸 Gestión de Gastos Operativos (¡NUEVO!)',
        'description' => "¡Toma el control total de tus finanzas! \n\nAhora puedes registrar todos esos gastos que no son compras a proveedores: luz, alquiler, sueldos, insumos de oficina y más. \n\n**¿Cómo se hace?**\n1. Ve al nuevo menú **Gastos**.\n2. Haz clic en **Registrar Gasto**.\n3. Selecciona una categoría y pon el monto.\n4. **TIP PRO:** ¡Puedes PEGAR una foto del ticket (Ctrl+V) en la descripción!",
        'type' => 'nuevo',
        'publish_date' => '2026-03-17',
        'link_tutorial' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
    ],
    [
        'title' => '💳 Notas de Crédito y Devoluciones',
        'description' => "¡Gestiona devoluciones de forma profesional! \n\n¿Un cliente te devuelve algo? ¿Tú le devuelves al proveedor? \n\n**¿Cómo se hace?**\n- En el **POS**, selecciona 'Nota de Crédito' al cobrar. El stock volverá a tu inventario.\n- en **Compras**, selecciona 'Nota de Crédito' al cargar el comprobante del proveedor. Tu deuda bajará automáticamente.",
        'type' => 'mejora',
        'publish_date' => '2026-03-16',
        'link_tutorial' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
    ],
    [
        'title' => '🏷️ Gestión de Rubros y Categorías',
        'description' => "Organiza tu mercadería para encontrarla en segundos. \n\n**¿Cómo se hace?**\n1. Entra a **Productos > Gestionar Rubros**.\n2. Crea tus categorías (ej: Bebidas, Almacén, Limpieza).\n3. Al crear un producto, asígnale su rubro. ¡Tus reportes serán mucho más claros ahora!",
        'type' => 'nuevo',
        'publish_date' => '2026-03-16',
        'link_tutorial' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
    ],
    [
        'title' => '📈 Actualización Masiva de Precios',
        'description' => "¡Gánale a la inflación en un clic! \n\n**¿Cómo se hace?**\n1. Ve a **Productos > Actualización de Precios**.\n2. Elige un Rubro específico.\n3. Pon el porcentaje (ej: 10%) y dale a 'Actualizar'. ¡Listo! Todos los precios suben al instante.",
        'type' => 'mejora',
        'publish_date' => '2026-03-16',
        'link_tutorial' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
    ],
    [
        'title' => '🎨 Diseño Premium Glassmorphism',
        'description' => "Disfruta de una interfaz moderna, limpia y profesional. \n\nHemos aplicado el estilo **Glassmorphism** (efecto cristal) en todo el sistema. Es más agradable a la vista y resalta la información importante. ¡MultiPOS ahora se siente tan bien como se ve!",
        'type' => 'mejora',
        'publish_date' => '2026-03-14'
    ]
];

foreach ($updates as $data) {
    SystemUpdate::create($data);
}

echo "Timeline actualizada con éxito.\n";
