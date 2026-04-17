<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'tres@gmail.com')->first();
if ($user) {
    $user->password = \Hash::make('password');
    $user->save();
    echo "✓ Contraseña de 'Tres' actualizada a 'password' para la demo.\n";
} else {
    echo "✗ No se encontró el usuario 'Tres'.\n";
}
