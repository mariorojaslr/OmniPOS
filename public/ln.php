<?php
// ARCHIVO PUENTE PARA CREAR EL LINK DE STORAGE EN HOSTINGER
$target = __DIR__ . '/../storage/app/public';
$link = __DIR__ . '/storage';

echo "<h1>MultiPOS Storage Linker</h1>";
echo "Origen: $target <br>";
echo "Destino: $link <br>";

if (file_exists($link)) {
    echo "<h2 style='color:orange;'>El enlace 'storage' ya existe. Intentando borrarlo para refrescar...</h2>";
    if (is_link($link)) {
        unlink($link);
    } else {
        // Si es una carpeta real, la renombramos por seguridad
        rename($link, $link . '_backup_' . time());
    }
}

try {
    if (symlink($target, $link)) {
        echo "<h2 style='color:green;'>¡ÉXITO! Enlace simbólico creado correctamente.</h2>";
        echo "<p>Ya podés borrar este archivo ln.php de tu servidor.</p>";
    } else {
        echo "<h2 style='color:red;'>FALLÓ: No se pudo crear el enlace simbólico. Puede que tu hosting lo tenga deshabilitado.</h2>";
    }
} catch (Exception $e) {
    echo "<h2 style='color:red;'>ERROR: " . $e->getMessage() . "</h2>";
}
