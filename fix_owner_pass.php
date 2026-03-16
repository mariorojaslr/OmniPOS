<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$owner = App\Models\User::where('role', 'owner')->first();
if ($owner) {
    if (password_verify('Rojas*250007', $owner->password)) {
        echo 'La clave de tu Owner (' . $owner->email . ') YA ES tu contraseña usual Rojas*... No se necesitan cambios.' . PHP_EOL;
    }
    else {
        $owner->password = \Illuminate\Support\Facades\Hash::make('Rojas*250007');
        $owner->save();
        echo 'ÉXITO: Se restauró la contraseña a Rojas*250007 para el Owner: ' . $owner->email . PHP_EOL;
    }
}
else {
    echo 'No hay Owner en la DB!' . PHP_EOL;
}
