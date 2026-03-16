<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$owner = \App\Models\User::where('role', 'owner')->first();

if ($owner) {
    echo "OWNER ENCONTRADO:\n";
    echo "Email: " . $owner->email . "\n";

    // Resetear password para estar seguros
    $owner->password = \Illuminate\Support\Facades\Hash::make('password');
    $owner->save();

    echo "Contraseña reseteada temporalmente a: password\n";
}
else {
    echo "NO SE ENCONTRÓ NINGÚN USUARIO 'owner' EN LA BASE DE DATOS.\n";
}
