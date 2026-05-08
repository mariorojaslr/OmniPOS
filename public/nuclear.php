<?php
// NUCLEAR 6.7: ERROR HUNTER (SIN LARAVEL)
echo "<body style='background:#000; color:#0f0; font-family:monospace; padding:30px;'>";
echo "<h1>🕵️‍♂️ DETECTIVE NUCLEAR v6.7</h1>";

$logPath = __DIR__.'/../storage/logs/multipos.log';
echo "<h3>🎯 Buscando el Error Real...</h3>";

if (file_exists($logPath)) {
    $content = file($logPath);
    $found = false;
    // Buscamos los últimos errores reales (local.ERROR)
    $errors = [];
    foreach(array_reverse($content) as $line) {
        if(str_contains($line, 'local.ERROR')) {
            $errors[] = $line;
            if(count($errors) > 5) break;
        }
    }

    if(!empty($errors)) {
        echo "<div style='background:#440000; color:#fff; padding:20px; border:2px solid red;'>";
        echo "<h4>🚨 ÚLTIMOS ERRORES ENCONTRADOS:</h4>";
        foreach($errors as $err) {
            echo "<p style='border-bottom:1px solid #660000; padding-bottom:10px;'>" . htmlspecialchars($err) . "</p>";
        }
        echo "</div>";
    } else {
        echo "<div>No se encontraron líneas con 'local.ERROR' en los últimos registros.</div>";
        echo "<h4>Últimas 10 líneas del archivo:</h4><pre style='background:#111; padding:10px;'>";
        $lastLines = array_slice($content, -10);
        foreach($lastLines as $l) echo htmlspecialchars($l);
        echo "</pre>";
    }
} else {
    echo "❌ No se encontró multipos.log";
}
?>
