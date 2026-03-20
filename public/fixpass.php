<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Boot Laravel (esto faltaba)
|--------------------------------------------------------------------------
*/
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

/*
|--------------------------------------------------------------------------
| CAMBIAR PASSWORD
|--------------------------------------------------------------------------
*/

$email = 'uno@gmail.com';
$newPassword = '12345678';

$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ Usuario no encontrado";
    exit;
}

$user->password = Hash::make($newPassword);
$user->save();

echo "✅ Password cambiado correctamente para {$email}";
