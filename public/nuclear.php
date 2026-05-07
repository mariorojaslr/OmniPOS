<?php
// NUCLEAR 6.4: BOOT PROFUNDO
define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

// Intentar un arranque más agresivo para Laravel 11/12
$app = require_once __DIR__.'/../bootstrap/app.php';

echo "<body style='background:#050505; color:#0f0; font-family:sans-serif; padding:30px;'>";
echo "<h1 style='color:#3b82f6;'>📡 MONITOR DE SISTEMA MULTIPOS v6.4</h1>";

try {
    // Forzar el booteo de los Service Providers
    $app->boot();
    echo "<div style='color:green;'>✅ Laravel ha booteado correctamente los proveedores.</div>";

    // Intentar leer config
    $env = $app->make('config')->get('app.env');
    echo "<div>Entorno: <strong>$env</strong></div>";
    echo "<div>Debug: <strong>" . ($app->make('config')->get('app.debug') ? 'SI' : 'NO') . "</strong></div>";

} catch (\Exception $e) {
    echo "<div style='color:red; border:1px solid red; padding:20px;'>";
    echo "<h3>❌ Error Crítico en el Booteo:</h3>";
    echo $e->getMessage();
    echo "<pre style='font-size:10px; color:#aaa;'>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}

// Escáner de logs (V6.3 mejorado)
echo "<h3>📂 Archivos en storage/logs:</h3>";
$logDir = __DIR__.'/../storage/logs';
if (is_dir($logDir)) {
    $files = array_diff(scandir($logDir), ['.', '..']);
    foreach($files as $f) echo "<div>$f</div>";
}
?>
