<?php
// NUCLEAR 6.3: MONITOR DE SISTEMA MULTIPOS
define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

echo "<body style='background:#050505; color:#0f0; font-family:sans-serif; padding:30px;'>";
echo "<h1 style='color:#3b82f6;'>📡 MONITOR DE SISTEMA MULTIPOS v6.3</h1>";

// 1. FORZAR DEBUG MODE EN TIEMPO REAL
config(['app.debug' => true]);
echo "<div>APP_DEBUG: " . (config('app.debug') ? '✅ ACTIVADO' : '❌ DESACTIVADO') . "</div>";
echo "<div>LOG_CHANNEL: " . config('logging.default') . "</div>";

// 2. LISTAR ARCHIVOS EN STORAGE/LOGS
echo "<h3>📂 Archivos en storage/logs:</h3>";
$logDir = __DIR__.'/../storage/logs';
if (is_dir($logDir)) {
    $files = scandir($logDir);
    echo "<ul>";
    foreach($files as $file) {
        if($file != '.' && $file != '..') {
            $size = round(filesize($logDir.'/'.$file) / 1024, 2);
            echo "<li>$file ($size KB)</li>";
        }
    }
    echo "</ul>";
} else {
    echo "❌ El directorio de logs no existe.";
}

// 3. INTENTAR TEST DE LOG
try {
    \Log::info("Test de log desde Nuclear 6.3");
    echo "<div style='color:cyan;'>✅ Intento de escritura en log realizado.</div>";
} catch (\Exception $e) {
    echo "<div style='color:red;'>❌ Error al intentar escribir en log: " . $e->getMessage() . "</div>";
}

// 4. ÚLTIMO ERROR DEL SISTEMA (PHP)
echo "<h3>⚠️ Último error de PHP (error_get_last):</h3>";
$lastError = error_get_last();
if ($lastError) {
    echo "<pre style='background:#222; color:#ff4444; padding:10px;'>";
    print_r($lastError);
    echo "</pre>";
} else {
    echo "No hay errores de PHP capturados.";
}
?>
