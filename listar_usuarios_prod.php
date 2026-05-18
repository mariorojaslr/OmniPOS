<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- 👥 LISTADO DE USUARIOS EN LA BASE DE DATOS DEL SERVIDOR ---\n";
try {
    $users = DB::table('users')->select('id', 'name', 'email', 'role', 'empresa_id', 'activo')->get();
    if ($users->isEmpty()) {
        echo "⚠️ No hay ningún usuario en la tabla 'users'.\n";
    } else {
        foreach ($users as $user) {
            echo "ID: {$user->id} | Nombre: {$user->name} | Email: {$user->email} | Rol: {$user->role} | Empresa: {$user->empresa_id} | Activo: {$user->activo}\n";
        }
    }
} catch (\Exception $e) {
    echo "❌ Error al leer los usuarios: " . $e->getMessage() . "\n";
}
