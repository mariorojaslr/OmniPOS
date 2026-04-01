<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$e = \App\Models\Empresa::find(1);
if($e) {
    $e->slug = 'empresa-de-prueba';
    $e->save();
    echo "Slug actualizado a 'empresa-de-prueba' con éxito.";
} else {
    echo "No se encontró la empresa ID 1.";
}
