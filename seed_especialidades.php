<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Especialidad;
use App\Models\Empresa;

$especialidadesBase = [
    ['nombre' => 'Traumatología', 'descripcion' => 'Especialidad en lesiones del sistema musculoesquelético.'],
    ['nombre' => 'Cardiología', 'descripcion' => 'Especialidad en diagnóstico y tratamiento de enfermedades del corazón.'],
    ['nombre' => 'Pediatría', 'descripcion' => 'Atención médica para bebés, niños y adolescentes.'],
    ['nombre' => 'Dermatología', 'descripcion' => 'Especialidad en cuidado y enfermedades de la piel.'],
    ['nombre' => 'Ginecología', 'descripcion' => 'Atención de la salud del sistema reproductor femenino.'],
    ['nombre' => 'Oftalmología', 'descripcion' => 'Cuidado de la visión y tratamiento de enfermedades oculares.'],
    ['nombre' => 'Neurología', 'descripcion' => 'Especialidad en trastornos del sistema nervioso.'],
    ['nombre' => 'Endocrinología', 'descripcion' => 'Especialidad en hormonas, metabolismo y glándulas.'],
    ['nombre' => 'Otorrinolaringología', 'descripcion' => 'Especialidad en oído, nariz y garganta.'],
    ['nombre' => 'Rehabilitación', 'descripcion' => 'Kinesiología y terapia física de recuperación.'],
];

$empresas = Empresa::all();
$count = 0;

foreach ($empresas as $empresa) {
    foreach ($especialidadesBase as $eb) {
        $existe = Especialidad::where('empresa_id', $empresa->id)
            ->where('nombre', $eb['nombre'])
            ->exists();

        if (!$existe) {
            Especialidad::create([
                'empresa_id' => $empresa->id,
                'nombre' => $eb['nombre'],
                'descripcion' => $eb['descripcion'],
                'activo' => true
            ]);
            $count++;
        }
    }
}

echo "=== SEMBRADO DE ESPECIALIDADES EXITOSO ===\n";
echo "Se crearon {$count} especialidades distribuidas en " . $empresas->count() . " empresas.\n";
