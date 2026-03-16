<?php
/**
 * SCRIPT DE EMERGENCIA: Crear storage link en Hostinger
 * cuando exec() y symlink() están deshabilitados.
 * 
 * Ejecutar DESDE EL NAVEGADOR: https://staging.gentepiola.net/fix_storage.php
 * BORRAR DESPUÉS DE USAR.
 */

// Seguridad mínima
define('SECRET', 'multipos2026');
if (($_GET['key'] ?? '') !== SECRET) {
    die('Acceso denegado. Usa ?key=multipos2026');
}

$target = __DIR__ . '/storage/app/public';
$link   = __DIR__ . '/public/storage';

echo "<pre style='font-family:monospace;padding:20px;'>\n";
echo "=== FIX STORAGE LINK ===\n\n";

// Verificar si ya existe
if (file_exists($link) || is_link($link)) {
    echo "⚠️  Ya existe: $link\n";
    echo "   Es symlink: " . (is_link($link) ? 'SÍ' : 'NO') . "\n";
    echo "   Es directorio: " . (is_dir($link) ? 'SÍ' : 'NO') . "\n\n";
    
    if (is_link($link)) {
        echo "✅ El symlink YA EXISTE correctamente.\n";
        echo "   Apunta a: " . readlink($link) . "\n";
    }
} else {
    // Intentar con symlink()
    if (function_exists('symlink')) {
        $result = @symlink($target, $link);
        if ($result) {
            echo "✅ Symlink creado exitosamente con symlink()\n";
        } else {
            echo "❌ symlink() falló. Error: " . error_get_last()['message'] . "\n";
        }
    } else {
        echo "❌ symlink() no disponible.\n";
    }
}

echo "\n=== VERIFICACIÓN ===\n";
echo "Target existe: " . (file_exists($target) ? 'SÍ (' . $target . ')' : 'NO - ERROR') . "\n";
echo "Link existe:   " . (file_exists($link)   ? 'SÍ (' . $link . ')' : 'NO') . "\n";

// Probar escribir un archivo de prueba en storage
$testFile = $target . '/test_link.txt';
file_put_contents($testFile, 'test ' . date('Y-m-d H:i:s'));
$readable = file_exists(__DIR__ . '/public/storage/test_link.txt');
echo "Prueba acceso público: " . ($readable ? '✅ OK - Las imágenes deberían verse' : '❌ FALLA') . "\n";
@unlink($testFile);
@unlink(__DIR__ . '/public/storage/test_link.txt');

echo "\n=== FUNCIONES DISPONIBLES ===\n";
$funcs = ['symlink', 'exec', 'shell_exec', 'system', 'passthru', 'readlink', 'realpath'];
foreach ($funcs as $f) {
    echo str_pad($f, 12) . ": " . (function_exists($f) ? '✅' : '❌') . "\n";
}

echo "\n⚠️  ACORDATE DE BORRAR ESTE ARCHIVO DESPUÉS DE USAR.\n";
echo "</pre>";
