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

// 4. Listado de tablas
try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
    echo "<h3>📋 TABLAS ENCONTRADAS:</h3><ul>";
    foreach ($tables as $table) {
        $tableName = current((array)$table);
        echo "<li>$tableName</li>";
    }
    echo "</ul>";
} catch (\Exception $e) {
    echo "<p style='color:red'>❌ Error al listar tablas: " . $e->getMessage() . "</p>";
}

// 5. Herramientas de Base de Datos
if (isset($_GET['migrate'])) {
    try {
        echo "<h3>🛠️ EJECUTANDO MIGRACIONES...</h3>";
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        echo "<pre>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";
        echo "<p>✅ Migraciones completadas.</p>";
    } catch (\Exception $e) {
        echo "<p style='color:red'>❌ Error en migración: " . $e->getMessage() . "</p>";
    }
}

if (isset($_GET['seed_plans'])) {
    try {
        echo "<h3>🌱 SEMBRANDO PLANES BÁSICOS...</h3>";
        $planes = [
            ['name' => 'Emprendedor', 'price' => 25000, 'max_users' => 2, 'max_products' => 100, 'is_active' => true],
            ['name' => 'Negocio', 'price' => 45000, 'max_users' => 5, 'max_products' => 500, 'is_active' => true],
            ['name' => 'Empresa', 'price' => 85000, 'max_users' => 15, 'max_products' => 2000, 'is_active' => true],
            ['name' => 'Premium', 'price' => 150000, 'max_users' => 50, 'max_products' => 10000, 'is_active' => true],
        ];
        foreach ($planes as $p) {
            \App\Models\Plan::updateOrCreate(['name' => $p['name']], $p);
        }
        echo "<p>✅ Planes creados/actualizados.</p>";
    } catch (\Exception $e) {
        echo "<p style='color:red'>❌ Error al sembrar planes: " . $e->getMessage() . "</p>";
    }
}

echo "<hr>";
echo "<div style='background:#f0f0f0;padding:15px;border-radius:10px;'>";
echo "<h3>🛠️ ACCIONES DE EMERGENCIA:</h3>";
echo "<a href='?migrate=1' style='display:inline-block;padding:10px;background:blue;color:white;text-decoration:none;border-radius:5px;margin-right:10px;'>🚀 EJECUTAR MIGRACIONES (Crear tablas faltantes)</a>";
echo "<a href='?seed_plans=1' style='display:inline-block;padding:10px;background:green;color:white;text-decoration:none;border-radius:5px;'>🌱 CREAR PLANES BÁSICOS</a>";
echo "</div>";

echo "<hr><a href='/login'>👉 INTENTAR ENTRAR AL LOGIN AHORA</a>";
