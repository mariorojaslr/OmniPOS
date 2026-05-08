<?php
// NUCLEAR 6.8: GLOBAL LOG SCANNER
echo "<body style='background:#000; color:#0f0; font-family:monospace; padding:30px;'>";
echo "<h1>🕵️‍♂️ DETECTIVE NUCLEAR v6.8</h1>";

$logDir = __DIR__.'/../storage/logs';
echo "<h3>📂 Escaneando carpeta de logs ($logDir):</h3>";

if (is_dir($logDir)) {
    $files = array_diff(scandir($logDir), ['.', '..']);
    echo "<ul>";
    foreach($files as $f) {
        $path = $logDir.'/'.$f;
        $mtime = date("H:i:s", filemtime($path));
        $size = round(filesize($path) / 1024, 2);
        echo "<li><strong>$f</strong> - Modificado: $mtime - Tamaño: $size KB ";
        echo "<a href='?view=$f' style='color:yellow;'>[VER CONTENIDO]</a></li>";
    }
    echo "</ul>";

    if (isset($_GET['view'])) {
        $fileToView = $logDir . '/' . basename($_GET['view']);
        if (file_exists($fileToView)) {
            echo "<h3>📄 Contenido de: " . $_GET['view'] . "</h3>";
            $lines = file($fileToView);
            // Mostrar solo las primeras 100 líneas del archivo (donde suele estar el error real)
            echo "<pre style='background:#111; color:#ff4444; padding:15px; border:1px solid #333; overflow:auto;'>";
            foreach(array_slice($lines, 0, 100) as $l) echo htmlspecialchars($l);
            echo "</pre>";
        }
    }
} else {
    echo "❌ No se encontró la carpeta de logs.";
}
?>
