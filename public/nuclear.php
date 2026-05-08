<?php
// NUCLEAR 6.5: DETECCIÓN DE LOG REAL Y REPARACIÓN DE FILESYSTEM
define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

echo "<body style='background:#050505; color:#0f0; font-family:sans-serif; padding:30px;'>";
echo "<h1 style='color:#3b82f6;'>📡 MONITOR DE SISTEMA MULTIPOS v6.5</h1>";

// 1. LECTOR DE LOG REAL (multipos.log)
$logFile = __DIR__.'/../storage/logs/multipos.log';
echo "<h3>📝 Contenido de multipos.log:</h3>";
if (file_exists($logFile)) {
    $lines = file($logFile);
    $lastErrors = array_slice($lines, -30);
    echo "<pre style='background:#111; color:#ff4444; padding:20px; border:1px solid #441111; overflow:auto; max-height:400px; font-size:11px;'>";
    foreach($lastErrors as $line) echo htmlspecialchars($line);
    echo "</pre>";
} else {
    echo "<div style='color:orange;'>⚠️ No se encontró multipos.log</div>";
}

// 2. DIAGNÓSTICO DE INFRAESTRUCTURA
try {
    echo "<h3>🛠️ Diagnóstico de Servicios:</h3>";
    echo "<div>PHP Version: " . phpversion() . "</div>";
    echo "<div>¿Filesystem disponible?: " . ($app->bound('files') ? '✅ SI' : '❌ NO') . "</div>";
    echo "<div>¿Config disponible?: " . ($app->bound('config') ? '✅ SI' : '❌ NO') . "</div>";
    
    // Intentar booteo suave
    $app->boot();
    echo "<div style='color:green;'>✅ Booteo completado.</div>";

} catch (\Exception $e) {
    echo "<div style='color:red; margin-top:20px;'>";
    echo "<strong>Error durante el diagnóstico:</strong> " . $e->getMessage();
    echo "</div>";
}
?>
