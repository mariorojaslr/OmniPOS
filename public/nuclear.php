<?php
/**
 * NUCLEAR 2.0 - MultiPOS Diagnostic Tool
 */
header('Content-Type: text/html; charset=utf-8');
echo "<body style='background:#111; color:#eee; font-family:sans-serif; padding:20px;'>";
echo "<h1>🚀 NUCLEAR 2.0: MODO DIAGNÓSTICO TOTAL</h1>";

// 1. LIMPIEZA DE CACHÉ
echo "<h2>🧹 LIMPIEZA DE SISTEMA:</h2>";
try {
    if (function_exists('opcache_reset')) {
        opcache_reset();
        echo "✅ OPcache reseteado.<br>";
    }
    
    // Forzar borrado de archivos de caché de rutas y servicios
    $cacheFiles = [
        __DIR__ . '/../bootstrap/cache/services.php',
        __DIR__ . '/../bootstrap/cache/packages.php',
        __DIR__ . '/../bootstrap/cache/routes-v7.php',
        __DIR__ . '/../bootstrap/cache/config.php'
    ];
    
    foreach ($cacheFiles as $file) {
        if (file_exists($file)) {
            unlink($file);
            echo "🗑️ Cache eliminada: " . basename($file) . "<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ Error en limpieza: " . $e->getMessage() . "<br>";
}

// 2. ÚLTIMOS ERRORES (LOGS)
echo "<h2>📜 ÚLTIMOS LOGS (storage/logs/laravel.log):</h2>";
$logPath = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($logPath)) {
    $lines = file($logPath);
    $lastLines = array_slice($lines, -60);
    echo "<pre style='background:#000; color:#0f0; padding:15px; border-radius:8px; border:1px solid #333; max-height:500px; overflow:auto; font-size:12px; line-height:1.4;'>";
    foreach ($lastLines as $line) {
        echo htmlspecialchars($line);
    }
    echo "</pre>";
} else {
    echo "<p style='color:orange;'>⚠️ Archivo de log no encontrado en $logPath</p>";
}

// 3. INSPECCIÓN DE BASE DE DATOS
echo "<h2>📋 TABLAS ENCONTRADAS:</h2>";
try {
    include __DIR__ . '/../vendor/autoload.php';
    $app = include __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(Illuminate\Http\Request::capture());

    $tables = DB::select('SHOW TABLES');
    echo "<div style='display:grid; grid-template-columns:repeat(4,1fr); gap:5px;'>";
    foreach ($tables as $table) {
        $name = array_values((array)$table)[0];
        echo "<span style='font-size:11px; background:#222; padding:3px; border-radius:3px;'>$name</span>";
    }
    echo "</div>";
} catch (Exception $e) {
    echo "❌ Error DB: " . $e->getMessage();
}

echo "<hr style='border:1px solid #333; margin:40px 0;'>";
echo "<a href='owner/dashboard' style='background:#d4af37; color:#000; padding:15px 30px; text-decoration:none; font-weight:bold; border-radius:8px;'>PROBAR DASHBOARD AHORA</a>";
echo "</body>";
