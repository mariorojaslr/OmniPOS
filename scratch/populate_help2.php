<?php

use App\Models\HelpArticle;

$articles = [
    [
        'route_name' => 'empresa.stock.index',
        'title' => 'Control y Movimientos de Stock',
        'content' => '
            <p>El área de <strong>Control de Stock</strong> registra todos los movimientos físicos de tus artículos, tanto ingresos como egresos o ajustes manuales.</p>
            <hr>
            <h4>📦 Operaciones</h4>
            <ul>
                <li><strong>Ingresos y Ajustes:</strong> Puedes sumar stock manualmente si encuentras diferencias físicas, o registrar bajas por mermas o roturas.</li>
                <li><strong>Trazabilidad:</strong> Cada movimiento queda registrado con la fecha, cantidad y el usuario que lo realizó para auditorías.</li>
            </ul>
        '
    ],
    [
        'route_name' => 'empresa.compras.index',
        'title' => 'Historial de Compras (Abasto)',
        'content' => '
            <p>El <strong>Historial de Compras</strong> es el registro de todas las facturas o remitos que tus proveedores te han emitido.</p>
            <hr>
            <h4>🛒 Cómo funciona</h4>
            <ul>
                <li><strong>Impacto en Stock:</strong> Al registrar una nueva compra, el sistema puede aumentar automáticamente el stock de los productos adquiridos.</li>
                <li><strong>Cuentas Corrientes:</strong> Si la compra es a crédito, el saldo se sumará a la cuenta corriente del proveedor para su futuro pago.</li>
            </ul>
        '
    ],
    [
        'route_name' => 'empresa.proveedores.index',
        'title' => 'Gestión de Proveedores',
        'content' => '
            <p>La <strong>Gestión de Proveedores</strong> centraliza la relación con todas las empresas que te abastecen de productos o insumos.</p>
            <hr>
            <h4>🚚 Herramientas Clave</h4>
            <ul>
                <li><strong>Cuentas Corrientes:</strong> Controla cuánto dinero le debes a cada proveedor y registra los recibos de pago que les emites.</li>
                <li><strong>Datos de Contacto:</strong> Mantén actualizada la información de los preventistas o agentes de cuenta.</li>
            </ul>
        '
    ],
    [
        'route_name' => 'empresa.gastos.index',
        'title' => 'Gestión de Gastos Operativos',
        'content' => '
            <p>La <strong>Gestión de Gastos</strong> te permite registrar salidas de dinero que no corresponden a la compra de mercadería (ej. alquiler, servicios, sueldos).</p>
            <hr>
            <h4>💸 Categorización</h4>
            <p>Clasificar los gastos por categorías te permite, a fin de mes, analizar gráficamente en qué área se está invirtiendo más capital y optimizar tu rentabilidad neta.</p>
        '
    ],
    [
        'route_name' => 'empresa.configuracion.index',
        'title' => 'Configuración General',
        'content' => '
            <p>Aquí ajustas el "ADN" de tu empresa dentro de MultiPOS. Todos los cambios visuales y de comportamiento se hacen aquí.</p>
            <hr>
            <h4>⚙️ Personalización</h4>
            <ul>
                <li><strong>Logo y Colores:</strong> Sube tu logo institucional y elige el color primario. Esto cambiará el aspecto de todo el sistema, incluyendo tu Store público y la cabecera de este manual.</li>
                <li><strong>Datos de Facturación:</strong> Completa tu razón social, CUIT e ingresos brutos para que los comprobantes salgan perfectos.</li>
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

echo "\n¡Segunda tanda de manuales completada con éxito!\n";
