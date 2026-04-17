<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'tres@gmail.com')->first();
if ($user) {
    $user->status = 'activo';
    $user->sub_role = 'admin';
    $user->is_super_admin = 1;
    $user->save();
    echo "✓ Usuario 'Tres' activado y elevado a Admin.\n";
}
