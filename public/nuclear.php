<?php
// NUCLEAR 6.2: MONITOR DE SISTEMA MULTIPOS
// DIAGNÓSTICO BASADO EN DATOS REALES
define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

echo "<body style='background:#050505; color:#0f0; font-family:sans-serif; padding:30px;'>";
echo "<h1 style='color:#3b82f6;'>📡 MONITOR DE SISTEMA MULTIPOS v6.2</h1>";

// 1. LECTOR DE LOGS (LA VERDADERA CAUSA)
$logPath = __DIR__.'/../storage/logs/laravel.log';
echo "<h3>📝 Últimos Errores en laravel.log:</h3>";
if (file_exists($logPath)) {
    $lines = file($logPath);
    $last20 = array_slice($lines, -20);
    echo "<pre style='background:#111; color:#ff4444; padding:20px; border:1px solid #441111; overflow:auto; max-height:400px; font-size:12px;'>";
    if(empty($last20)) {
        echo "No se registraron errores en el log.";
    } else {
        foreach($last20 as $line) {
            echo htmlspecialchars($line);
        }
    }
    echo "</pre>";
} else {
    echo "<div style='color:orange;'>⚠️ No se encontró el archivo de log en: $logPath</div>";
}

// 2. ESPACIO EN DISCO
$free = disk_free_space("/") / 1024 / 1024;
echo "<h3>📊 Estado del Disco:</h3>";
echo "<div>Espacio Libre: " . round($free, 2) . " MB</div>";

// 3. PERMISOS
echo "<h3>📁 Permisos de Carpetas Críticas:</h3>";
$folders = ['storage/framework/sessions', 'storage/framework/views', 'storage/logs'];
foreach($folders as $f) {
    $full = __DIR__.'/../' . $f;
    $status = is_writable($full) ? "✅ OK (Escribible)" : "❌ BLOQUEADO (Sin permisos)";
    echo "<div><strong>$f</strong>: $status</div>";
}
?>
