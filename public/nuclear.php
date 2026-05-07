<?php
echo "<h1>🚀 OPERACIÓN DESBLOQUEO TOTAL</h1>";

// 1. Resetear OPcache
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "<p>✅ OPcache reseteado.</p>";
}

// 2. MATAR EL MODO MANTENIMIENTO
$maintenanceFile = __DIR__ . '/../storage/framework/maintenance.php';
if (file_exists($maintenanceFile)) {
    unlink($maintenanceFile);
    echo "<p>🔓 <b>MODO MANTENIMIENTO DESACTIVADO (Archivo eliminado).</b></p>";
} else {
    echo "<p>ℹ️ El sistema no parece estar en modo mantenimiento (físicamente).</p>";
}

// 3. Limpiar caches de Laravel
$cacheFiles = [
    __DIR__ . '/../bootstrap/cache/config.php',
    __DIR__ . '/../bootstrap/cache/routes-v7.php',
    __DIR__ . '/../bootstrap/cache/services.php',
    __DIR__ . '/../bootstrap/cache/packages.php'
];

foreach ($cacheFiles as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "<p>🗑️ Cache eliminada: " . basename($file) . "</p>";
    }
}

echo "<h2>HORA: " . date('H:i:s') . "</h2>";
echo "<hr><a href='/login'>👉 INTENTAR ENTRAR AL LOGIN AHORA</a>";
