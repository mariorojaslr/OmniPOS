<?php
// NUCLEAR 6.9: SYSTEM RECOVERY
echo "<body style='background:#000; color:#0f0; font-family:monospace; padding:30px;'>";
echo "<h1>🕵️‍♂️ DETECTIVE NUCLEAR v6.9 - RECUPERACIÓN</h1>";

require __DIR__.'/../vendor/autoload.php';

try {
    echo "<h3>🛠️ Intentando Reparación de Motor...</h3>";
    
    // Intentar registrar manualmente el Filesystem si Laravel no lo hace
    $app = new \Illuminate\Foundation\Application(realpath(__DIR__.'/../'));
    
    echo "<div>¿App instanciada?: ✅ SI</div>";
    
    if (!$app->bound('files')) {
        echo "<div style='color:yellow;'>⚠️ Filesystem no vinculado. Intentando vinculación manual...</div>";
        $app->instance('files', new \Illuminate\Filesystem\Filesystem);
    }
    
    echo "<div>¿Filesystem vinculado?: " . ($app->bound('files') ? '✅ SI' : '❌ NO') . "</div>";

    // Intentar cargar la configuración
    echo "<h3>⚙️ Verificando Configuración:</h3>";
    if (file_exists(__DIR__.'/../.env')) {
        echo "<div>.env: ✅ EXISTE</div>";
    } else {
        echo "<div style='color:red;'>.env: ❌ NO EXISTE (Esto mataría el Dashboard)</div>";
    }

} catch (\Exception $e) {
    echo "<div style='color:red;'>❌ Error en recuperación: " . $e->getMessage() . "</div>";
}

// Lector de Log rápido
$logFile = __DIR__.'/../storage/logs/multipos.log';
if (file_exists($logFile)) {
    echo "<h3>📄 Último Error en multipos.log:</h3>";
    $lines = file($logFile);
    echo "<pre style='background:#111; color:#ff4444; padding:15px; border:1px solid #333;'>";
    echo htmlspecialchars(end($lines)); // Mostrar la última línea
    echo "</pre>";
}
?>
