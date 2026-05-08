<?php
// NUCLEAR 6.6: MODO INDEPENDIENTE (SIN LARAVEL)
echo "<body style='background:#000; color:#0f0; font-family:monospace; padding:30px;'>";
echo "<h1>🕵️‍♂️ DETECTIVE NUCLEAR v6.6</h1>";

// 1. LEER EL LOG A MANO (SIN USAR LARAVEL)
$logPath = __DIR__.'/../storage/logs/multipos.log';
echo "<h3>📝 Error Real en multipos.log:</h3>";
if (file_exists($logPath)) {
    $content = file($logPath);
    $lastEntries = array_slice($content, -30);
    echo "<pre style='background:#111; color:#ff4444; padding:15px; border:1px solid #333; overflow:auto;'>";
    foreach($lastEntries as $line) echo htmlspecialchars($line);
    echo "</pre>";
} else {
    echo "❌ No se encontró multipos.log";
}

// 2. VERIFICAR ARCHIVOS CRÍTICOS
echo "<h3>🔎 Verificación de archivos:</h3>";
$files = [
    'vendor/autoload.php',
    'bootstrap/app.php',
    'config/app.php',
    '.env'
];
foreach($files as $f) {
    $exists = file_exists(__DIR__.'/../'.$f) ? "✅ EXISTE" : "❌ NO EXISTE";
    echo "<div>$f: $status $exists</div>";
}

// 3. INTENTAR VER LA VERSIÓN DE PHP
echo "<h3>⚙️ Servidor:</h3>";
echo "<div>PHP: " . phpversion() . "</div>";
echo "<div>CWD: " . getcwd() . "</div>";
?>
