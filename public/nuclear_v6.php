<?php
// NUCLEAR 6.1: MONITOR DE SISTEMA MULTIPOS (FORZADO)
// HORA DE GENERACIÓN: <?php echo date('H:i:s'); ?>
define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

echo "<body style='background:#050505; color:#0f0; font-family:sans-serif; padding:30px;'>";
echo "<h1 style='color:#3b82f6;'>📡 MONITOR DE SISTEMA MULTIPOS v6.1</h1>";

// 1. LECTOR DE LOGS
$logPath = __DIR__.'/../storage/logs/laravel.log';
echo "<h3>📝 Últimos Errores (laravel.log):</h3>";
if (file_exists($logPath)) {
    $lines = file($logPath);
    $errors = array_slice($lines, -20);
    echo "<pre style='background:#111; color:#ff4444; padding:20px; border:1px solid #441111; overflow:auto; max-height:400px;'>";
    foreach($errors as $e) echo htmlspecialchars($e);
    echo "</pre>";
} else {
    echo "❌ No se encontró el archivo de log en $logPath";
}

// 2. ESPACIO EN DISCO
$free = disk_free_space("/") / 1024 / 1024;
echo "<h3>📊 Estado del Disco:</h3>";
echo "<div>Espacio Libre: " . round($free, 2) . " MB</div>";

// 3. PERMISOS
echo "<h3>📁 Permisos de Escritura:</h3>";
$folders = ['storage/framework/sessions', 'storage/framework/views', 'storage/logs'];
foreach($folders as $f) {
    $full = __DIR__.'/../' . $f;
    $status = is_writable($full) ? "✅ OK" : "❌ BLOQUEADO";
    echo "<div>$f: $status</div>";
}
?>
