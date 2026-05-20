<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

try {
    $nombresCardiologos = [
        'cardiologo1@sanatoriodelsol.demo' => 'Dr. Alejandro Favaloro',
        'cardiologo2@sanatoriodelsol.demo' => 'Dra. Mariana Cárdenas',
        'cardiologo3@sanatoriodelsol.demo' => 'Dr. Roberto Estévez',
        'cardiologo4@sanatoriodelsol.demo' => 'Dra. Laura Zúñiga',
        'cardiologo5@sanatoriodelsol.demo' => 'Dr. Fernando Villanueva',
    ];

    $nombresGinecologos = [
        'ginecologa1@sanatoriodelsol.demo' => 'Dra. Sofía Larreta',
        'ginecologa2@sanatoriodelsol.demo' => 'Dr. Javier Mendizábal',
        'ginecologa3@sanatoriodelsol.demo' => 'Dra. Elena Castelli',
        'ginecologa4@sanatoriodelsol.demo' => 'Dra. Victoria Ramos',
        'ginecologa5@sanatoriodelsol.demo' => 'Dr. Pablo Echegaray',
    ];

    $actualizados = 0;

    foreach ($nombresCardiologos as $email => $nombreReal) {
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->name = $nombreReal;
            $user->save();
            $actualizados++;
        }
    }

    foreach ($nombresGinecologos as $email => $nombreReal) {
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->name = $nombreReal;
            $user->save();
            $actualizados++;
        }
    }

    echo "ÉXITO: Se actualizaron $actualizados nombres a perfiles realistas.\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
