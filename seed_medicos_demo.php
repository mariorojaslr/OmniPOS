<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Empresa;
use App\Models\Servicio;
use App\Models\User;
use App\Models\ProfesionalConfig;

try {
    $empresa = Empresa::where('nombre_comercial', 'like', '%Sanatorio del Sol%')
                    ->orWhere('razon_social', 'like', '%Sanatorio del Sol%')
                    ->first();
                    
    if(!$empresa) {
        die("ERROR: No se encontró la empresa 'Sanatorio del Sol'.\n");
    }

    $espCardio = 'Cardiología';
    $espGineco = 'Ginecología';

    $servCardio = Servicio::firstOrCreate(
        ['empresa_id' => $empresa->id, 'nombre' => 'Consulta de Cardiología'],
        ['categoria' => $espCardio, 'precio' => 5000, 'duracion_minutos' => 30, 'activo' => true]
    );

    $servGineco = Servicio::firstOrCreate(
        ['empresa_id' => $empresa->id, 'nombre' => 'Consulta de Ginecología'],
        ['categoria' => $espGineco, 'precio' => 6000, 'duracion_minutos' => 30, 'activo' => true]
    );

    $creados = 0;

    for ($i = 1; $i <= 5; $i++) {
        $email = 'cardiologo' . $i . '@sanatoriodelsol.demo';
        $user = User::where('email', $email)->first();
        if ($user) {
            ProfesionalConfig::updateOrCreate(['user_id' => $user->id, 'empresa_id' => $empresa->id], [
                'especialidades' => [(string)$servCardio->id]
            ]);
            $creados++;
        }
    }

    for ($i = 1; $i <= 5; $i++) {
        $email = 'ginecologa' . $i . '@sanatoriodelsol.demo';
        $user = User::where('email', $email)->first();
        if ($user) {
            ProfesionalConfig::updateOrCreate(['user_id' => $user->id, 'empresa_id' => $empresa->id], [
                'especialidades' => [(string)$servGineco->id]
            ]);
            $creados++;
        }
    }

    echo "ÉXITO: Se han corregido las asignaciones de $creados profesionales. Ahora tienen el ID del servicio médico asignado.\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
