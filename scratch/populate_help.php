<?php

use App\Models\HelpArticle;

$articles = [
    [
        'route_name' => 'empresa.dashboard',
        'title' => 'Inicio y Panel de Control',
        'content' => '
            <p>Bienvenido al <strong>Panel de Control Central</strong> de MultiPOS. Desde aquí tendrás una visión panorámica e instantánea de la salud de tu negocio.</p>
            <hr>
            <h4>📊 Métricas Clave</h4>
            <ul>
                <li><strong>Ritmo de Ventas:</strong> Monitorea en tiempo real el volumen de facturación comparado con el promedio mensual.</li>
                <li><strong>Gasto vs Inversión:</strong> Analiza rápidamente cuánto capital está saliendo hacia proveedores (inversión) frente a gastos operativos.</li>
                <li><strong>Ventas del Mes:</strong> El acumulado global de todo lo facturado en el periodo actual.</li>
            </ul>
            <div class="alert alert-info">
                <strong>💡 Tip de Arti:</strong> Utiliza los accesos rápidos inferiores para saltar directamente a la gestión de cajeros, clientes o emisión de reportes.
            </div>
        '
    ],
    [
        'route_name' => 'empresa.pos.index',
        'title' => 'Punto de Venta (POS)',
        'content' => '
            <p>El <strong>Terminal de Punto de Venta (POS)</strong> está diseñado para operaciones rápidas de mostrador. Es ideal para cobros ágiles y emisión inmediata de tickets.</p>
            <hr>
            <h4>🛒 Cómo operar</h4>
            <ol>
                <li><strong>Búsqueda Rápida:</strong> Utiliza el lector de código de barras o escribe el nombre del producto en el buscador.</li>
                <li><strong>Cantidades:</strong> Ajusta las cantidades directamente en el listado de la compra.</li>
                <li><strong>Cobro:</strong> Selecciona el método de pago (Efectivo, Tarjeta, Transferencia). El sistema calculará automáticamente el vuelto si es necesario.</li>
            </ol>
            <h4>👤 Clientes</h4>
            <p>Por defecto, las ventas se realizan a "Consumidor Final". Si necesitas asociar la venta a un cliente específico para cuenta corriente, búscalo en el selector superior antes de cobrar.</p>
            <div class="alert alert-warning">
                <strong>⚠️ Importante:</strong> Toda venta realizada aquí impactará inmediatamente en tu caja diaria activa y descontará stock automáticamente.
            </div>
        '
    ],
    [
        'route_name' => 'empresa.products.index',
        'title' => 'Gestión de Artículos',
        'content' => '
            <p>El <strong>Maestro de Artículos</strong> es el corazón de tu inventario. Aquí puedes crear, modificar y eliminar los productos que comercializas.</p>
            <hr>
            <h4>📦 Opciones Disponibles</h4>
            <ul>
                <li><strong>Creación Múltiple:</strong> Puedes crear artículos individuales o variantes si manejas diferentes talles o colores.</li>
                <li><strong>Imágenes:</strong> Sube fotos de alta calidad para que se muestren en tu catálogo público online (Store).</li>
                <li><strong>Precios y Márgenes:</strong> Define el costo y el margen de ganancia; el sistema calculará el precio final (o viceversa).</li>
            </ul>
            <h4>🔍 Filtros de Búsqueda</h4>
            <p>Usa la barra superior para buscar por código interno, código de barras, nombre o filtrar por categoría específica.</p>
        '
    ],
    [
        'route_name' => 'empresa.clientes.index',
        'title' => 'Cartera de Clientes',
        'content' => '
            <p>La <strong>Cartera de Clientes</strong> te permite gestionar la relación comercial con cada persona o empresa que te compra.</p>
            <hr>
            <h4>👥 Funcionalidades</h4>
            <ul>
                <li><strong>Geolocalización:</strong> Al crear un cliente, puedes fijar sus coordenadas GPS. Esto es vital para el módulo de <em>Rutas de Reparto (Smart Delivery)</em>.</li>
                <li><strong>Cuentas Corrientes:</strong> Desde aquí puedes acceder al historial financiero del cliente, ver su saldo deudor y registrar pagos o compensaciones.</li>
                <li><strong>Condición Fiscal:</strong> Asigna si es Consumidor Final, Monotributista o Responsable Inscripto para agilizar la facturación.</li>
            </ul>
        '
    ],
    [
        'route_name' => 'empresa.gps.rutas',
        'title' => 'Smart Route: Recorrido de Reparto',
        'content' => '
            <p>El módulo <strong>Smart Route</strong> utiliza inteligencia logística para organizar tus entregas del día de la manera más eficiente posible.</p>
            <hr>
            <h4>🚚 Cómo planificar un reparto</h4>
            <ol>
                <li><strong>Cargar Pedidos:</strong> Haz clic en "Cargar Pedidos Pendientes" para traer todas las ventas que tienen estado "Pendiente de Envío".</li>
                <li><strong>Añadir Paradas Extra:</strong> Puedes buscar manualmente clientes o proveedores para agregarlos a la ruta.</li>
                <li><strong>Calcular Ruta Óptima:</strong> Al presionar este botón, el sistema ordenará las paradas para minimizar el tiempo de viaje y el consumo de combustible.</li>
                <li><strong>Imprimir Nómina:</strong> Genera la hoja de ruta en PDF para entregársela al chofer.</li>
            </ol>
            <div class="alert alert-info">
                <strong>💡 Tip Logístico:</strong> Si un cliente tiene mal asignada su ubicación, el sistema lo marcará como "PROVINCIA DETECTADA" o similar. Puedes excluirlo apagando su interruptor antes de calcular la ruta.
            </div>
        '
    ],
    [
        'route_name' => 'empresa.tesoreria.index',
        'title' => 'Bancos y Billeteras (Tesorería)',
        'content' => '
            <p>El centro de <strong>Tesorería</strong> centraliza todo el dinero que no está en las cajas físicas (efectivo), es decir, tus cuentas bancarias y billeteras virtuales (MercadoPago, Ualá, etc).</p>
            <hr>
            <h4>🏦 Administración de Fondos</h4>
            <ul>
                <li><strong>Movimientos:</strong> Visualiza los ingresos por ventas digitales y los egresos por pagos a proveedores.</li>
                <li><strong>Transferencias Internas:</strong> Puedes registrar movimientos de dinero entre tus propias cuentas (ej: transferir de MercadoPago a Banco Galicia).</li>
                <li><strong>Conciliación:</strong> Asegúrate de que el saldo que muestra el sistema coincida con el saldo real de tu cuenta bancaria.</li>
            </ul>
        '
    ],
    [
        'route_name' => 'empresa.ventas.manual',
        'title' => 'Facturación Manual y Notas',
        'content' => '
            <p>A diferencia del POS rápido, la <strong>Venta Manual</strong> está pensada para operaciones B2B (Mayoristas) o ventas complejas que requieren más detalles.</p>
            <hr>
            <h4>📝 Características Especiales</h4>
            <ul>
                <li><strong>Cuentas Corrientes:</strong> Puedes vender "A crédito" y que el saldo impacte directamente en la deuda del cliente.</li>
                <li><strong>Remitos Automáticos:</strong> Puedes elegir si esta venta genera o no un remito físico para el depósito.</li>
                <li><strong>Destino de Fondos:</strong> A diferencia de la caja rápida, aquí puedes especificar exactamente a qué cuenta bancaria ingresa el dinero (ideal para transferencias grandes).</li>
            </ul>
        '
    ]
];

foreach ($articles as $data) {
    HelpArticle::updateOrCreate(
        ['route_name' => $data['route_name']],
        [
            'title' => $data['title'],
            'content' => $data['content'],
            'is_active' => true,
        ]
    );
    echo "✔ Manual creado para: " . $data['route_name'] . "\n";
}

echo "\n¡Instalación de manuales completada con éxito!\n";
