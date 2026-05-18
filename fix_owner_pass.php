<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "--- 🔑 PROCESANDO USUARIO OWNER EN EL SERVIDOR ---\n";

$owner = User::where('email', 'mario.rojas.coach@gmail.com')->first();

if (!$owner) {
    // Si no existe, lo creamos
    $owner = new User();
    $owner->email = 'mario.rojas.coach@gmail.com';
    $owner->name = 'Mario Rojas';
    echo "ℹ️ Creando nuevo usuario Owner...\n";
} else {
    echo "ℹ️ Usuario existente encontrado, actualizando datos...\n";
}

$owner->role = 'owner';
$owner->activo = true;
$owner->status = 'activo';
$owner->password = Hash::make('Rojas*250007');
$owner->save();

echo "✅ ÉXITO: El Owner ('{$owner->email}') está activo y su contraseña ha sido establecida a: Rojas*250007\n";
