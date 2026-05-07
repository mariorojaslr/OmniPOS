<?php
// 🚨 OPERACIÓN LIMPIEZA NUCLEAR
echo "<h1>🚀 INICIANDO REINICIO DE MOTOR PHP...</h1>";

if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "<p>✅ OPcache reseteado.</p>";
}

if (function_exists('apcu_clear_cache')) {
    apcu_clear_cache();
    echo "<p>✅ APCu reseteado.</p>";
}

// Intentar limpiar las carpetas de cache de Laravel
$cachePath = __DIR__ . '/../bootstrap/cache/*.php';
foreach (glob($cachePath) as $file) {
    unlink($file);
    echo "<p>🗑️ Eliminado cache: " . basename($file) . "</p>";
}

echo "<h2>HORA DEL SERVIDOR: " . date('H:i:s') . "</h2>";
echo "<p><b>Si ves esto, PHP ya no está congelado.</b></p>";
echo "<hr><a href='/login'>👉 IR AL LOGIN (DEBERÍA CARGAR O MOSTRAR ERROR)</a>";
