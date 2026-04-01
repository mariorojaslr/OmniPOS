<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Empresa;
use Illuminate\Support\Str;

$empresas = Empresa::all();
foreach($empresas as $e) {
    if(!$e->slug) {
        $baseSlug = Str::slug($e->nombre_comercial ?? 'empresa-' . $e->id);
        $e->slug = $baseSlug;
        $e->save();
        echo "Slug generado para {$e->nombre_comercial}: {$e->slug}\n";
    }
}
echo "Proceso completado.";
