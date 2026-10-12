<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\EmpresaConfig;
use App\Models\User;
use App\Models\Client;
use App\Models\Servicio;
use App\Models\MedicalRecord;
use App\Models\Turno;
use Carbon\Carbon;

class ClinicaOdontologicaDemoSeeder extends Seeder
{
    public function run(): void
    {
        $empresa = Empresa::where('slug', 'clinica-demo')->first();
        if (!$empresa) {
            $empresa = Empresa::first();
        }

        if (!$empresa) {
            echo "No se encontró empresa base.\n";
            return;
        }

        // 1. Asegurar configuración clínica en EmpresaConfig
        $config = EmpresaConfig::firstOrCreate(
            ['empresa_id' => $empresa->id],
            [
                'tipo_clinica' => 'clinica_odontologica',
                'mod_odontograma' => true,
                'mod_internaciones' => false,
                'mod_turnos' => true,
                'mod_hce' => true,
                'terminologia_espacio' => 'consultorio',
            ]
        );

        $config->update([
            'tipo_clinica' => 'clinica_odontologica',
            'mod_odontograma' => true,
            'mod_internaciones' => false,
            'mod_turnos' => true,
            'mod_hce' => true,
            'terminologia_espacio' => 'consultorio',
        ]);

        // 2. Crear o actualizar Servicios Odontológicos
        $serviciosOdonto = [
            [
                'nombre' => 'Limpieza Profunda & Profilaxis con Ultrasonido',
                'categoria' => 'Odontología',
                'precio' => 18000,
                'duracion_minutos' => 30,
                'comision_porcentaje' => 35,
                'activo' => 1
            ],
            [
                'nombre' => 'Restauración Estética con Resina Fotocurable',
                'categoria' => 'Odontología',
                'precio' => 24000,
                'duracion_minutos' => 45,
                'comision_porcentaje' => 40,
                'activo' => 1
            ],
            [
                'nombre' => 'Endodoncia Unirradicular (Tratamiento de Conducto)',
                'categoria' => 'Odontología',
                'precio' => 55000,
                'duracion_minutos' => 60,
                'comision_porcentaje' => 45,
                'activo' => 1
            ],
            [
                'nombre' => 'Corona de Porcelana Pura sobre Implante',
                'categoria' => 'Odontología',
                'precio' => 120000,
                'duracion_minutos' => 45,
                'comision_porcentaje' => 50,
                'activo' => 1
            ],
            [
                'nombre' => 'Blanqueamiento Dental LED en Consultorio',
                'categoria' => 'Odontología',
                'precio' => 65000,
                'duracion_minutos' => 45,
                'comision_porcentaje' => 35,
                'activo' => 1
            ],
            [
                'nombre' => 'Extracción Dental Simple y Sutura',
                'categoria' => 'Odontología',
                'precio' => 28000,
                'duracion_minutos' => 30,
                'comision_porcentaje' => 40,
                'activo' => 1
            ],
        ];

        foreach ($serviciosOdonto as $s) {
            Servicio::updateOrCreate(
                ['empresa_id' => $empresa->id, 'nombre' => $s['nombre']],
                $s
            );
        }

        // 3. Crear u obtener Odontólogos del equipo
        $doctorUser = User::where('empresa_id', $empresa->id)->where('email', 'medico@poolhub.com')->first();
        if ($doctorUser) {
            $doctorUser->update([
                'name' => 'Dra. Sofía Martínez (Odontóloga Especialista)',
                'consultorio' => 'Sillón Dental 1 (Box Odontológico)'
            ]);
        }

        $adminUser = User::where('empresa_id', $empresa->id)->where('role', 'empresa')->first();
        if ($adminUser) {
            $adminUser->update([
                'consultorio' => 'Consultorio Dental 2'
            ]);
        }

        // 4. Enriquecer HCE con Odontograma cargado para Carlos Mendoza
        $carlos = Client::where('empresa_id', $empresa->id)->where('name', 'like', '%Carlos Mendoza%')->first();
        if ($carlos) {
            $odontoEjemplo = [
                '18' => [
                    'caras' => [],
                    'estado_general' => 'ausente'
                ],
                '16' => [
                    'caras' => ['c' => 'caries', 'm' => 'caries'],
                    'estado_general' => 'sano'
                ],
                '14' => [
                    'caras' => ['c' => 'obturacion'],
                    'estado_general' => 'sano'
                ],
                '21' => [
                    'caras' => [],
                    'estado_general' => 'corona'
                ],
                '36' => [
                    'caras' => ['c' => 'endodoncia'],
                    'estado_general' => 'endodoncia'
                ],
                '46' => [
                    'caras' => [],
                    'estado_general' => 'implante'
                ],
                '48' => [
                    'caras' => [],
                    'estado_general' => 'ausente'
                ]
            ];

            // Crear una evolución odontológica completa
            MedicalRecord::create([
                'empresa_id' => $empresa->id,
                'client_id' => $carlos->id,
                'user_id' => $doctorUser ? $doctorUser->id : $adminUser->id,
                'specialty' => 'Odontología Integral',
                'reason_for_visit' => 'Consulta diagnóstica inicial y relevamiento de odontograma completo.',
                'diagnosis' => "Paciente adulto que presenta caries activa oclusomesial en pieza 16. Corona cerámica adaptada en 21. Se constata endodoncia en pieza 36 e implante oseointegrado en 46. Ausencia de piezas 18 y 48 (extracciones previas).",
                'treatment' => "1. Apertura y remoción de tejido cariado en pieza 16 + obturación con resina compuesta estética.\n2. Profilaxis y tartrectomía con ultrasonido.\n3. Control radiográfico de la endodoncia en pieza 36.",
                'internal_notes' => 'Paciente muy colaborador. Se indica cepillado interdental y uso de hilo.',
                'specialized_data' => [
                    'vitals' => [
                        'sys' => '120', 'dia' => '80', 'glucose' => '90', 'heart' => '72', 'weight' => '82'
                    ],
                    'medications' => [
                        [
                            'name' => 'Ibuprofeno 600mg',
                            'dose' => '1 comprimido',
                            'frequency' => 'cada 8 horas en caso de dolor post-tratamiento',
                            'route' => 'Oral'
                        ]
                    ],
                    'labs' => [],
                    'dynamic_fields' => [
                        'Higiene Bucal' => 'Buena',
                        'Sensibilidad' => 'Leve en cuadrante superior derecho',
                        'Placa Bacteriana' => 'Moderada'
                    ],
                    'odontograma' => $odontoEjemplo
                ]
            ]);

            // Turno odontológico de prueba para hoy
            $servicioLimpieza = Servicio::where('empresa_id', $empresa->id)->where('nombre', 'like', '%Limpieza%')->first();
            Turno::updateOrCreate(
                [
                    'empresa_id' => $empresa->id,
                    'client_id' => $carlos->id,
                    'fecha' => Carbon::today()->format('Y-m-d'),
                    'hora_inicio' => '10:00:00'
                ],
                [
                    'user_id' => $doctorUser ? $doctorUser->id : $adminUser->id,
                    'servicio_id' => $servicioLimpieza ? $servicioLimpieza->id : null,
                    'hora_fin' => '10:30:00',
                    'estado' => 'confirmado',
                    'estado_cola' => 'sala_espera',
                    'hora_arribo' => Carbon::now()->subMinutes(5)->format('H:i:s'),
                    'monto' => 18000,
                    'notas' => 'Turno confirmado vía web. Odontología General.',
                ]
            );
        }

        echo "✅ Demo Odontológica enriquecida con éxito: Configuración, Odontograma, Servicios y Turnos listos.\n";
    }
}
