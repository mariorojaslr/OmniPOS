<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\Empresa;
use App\Models\EmpresaConfig;
use App\Models\User;
use App\Models\Client;
use App\Models\Servicio;
use App\Models\MedicalRecord;
use App\Models\Turno;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ClinicaDentalPruebaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtener o crear Plan Pro
        $planPro = Plan::firstOrCreate(
            ['name' => 'Plan Pro'],
            [
                'description' => 'Ideal para clínicas y centros médicos de alta demanda',
                'price' => 25000,
                'max_users' => 50,
                'max_products' => 1000,
                'max_storage_mb' => 5120,
                'is_active' => true,
            ]
        );

        // 2. Crear la Empresa "Clínica Dental de Prueba"
        $empresa = Empresa::updateOrCreate(
            ['slug' => 'clinica-dental-prueba'],
            [
                'nombre_comercial' => 'Clínica Dental de Prueba',
                'razon_social' => 'Clínica Dental de Prueba S.R.L.',
                'email' => 'dental@poolhub.com',
                'telefono' => '+5491134567890',
                'activo' => true,
                'plan_id' => $planPro->id,
                'status' => 'activa',
                'is_bonificated' => true, // Acceso 100% libre y permanente
                'fecha_vencimiento' => now()->addYears(10),
                'config_pasarelas' => [
                    'online_booking_enabled' => 1,
                    'online_booking_price' => 0, // Gratuito para pruebas
                    'online_booking_gateway' => 'MercadoPago'
                ],
            ]
        );

        // 3. Configuración del Módulo Odontológico
        EmpresaConfig::updateOrCreate(
            ['empresa_id' => $empresa->id],
            [
                'color_primary' => '#0d9488', // Verde Azulado Odontológico Premium
                'color_secondary' => '#0284c7', // Azul Clínico
                'theme' => 'light',
                'tipo_clinica' => 'clinica_odontologica',
                'mod_odontograma' => true,
                'mod_internaciones' => false, // Odontología no requiere internaciones
                'mod_turnos' => true,
                'mod_hce' => true,
                'mod_campos_dinamicos' => true,
                'mod_afiliados' => true,
                'mod_ventas' => true,
                'mod_compras' => true,
                'terminologia_espacio' => 'consultorio',
            ]
        );

        // 4. Crear Usuarios de Acceso Libre para Prácticas y Demostraciones
        // 4.1. Dueño / Administrador de la clínica
        $admin = User::updateOrCreate(
            ['email' => 'dental@poolhub.com'],
            [
                'name' => 'Dr. Fernando Varela (Director Dental)',
                'password' => Hash::make('demo1234'),
                'role' => 'empresa',
                'sub_role' => 'cajero',
                'empresa_id' => $empresa->id,
                'activo' => 1,
                'must_change_password' => 0,
                'consultorio' => 'Consultorio Dental Central',
                'email_verified_at' => now(),
            ]
        );

        // 4.2. Odontóloga Especialista en Cirugía e Implantes
        $draFlorencia = User::updateOrCreate(
            ['email' => 'florencia.morales@poolhub.com'],
            [
                'name' => 'Dra. Florencia Morales (Cirugía & Implantes)',
                'password' => Hash::make('demo1234'),
                'role' => 'usuario',
                'sub_role' => 'empleado',
                'empresa_id' => $empresa->id,
                'activo' => 1,
                'must_change_password' => 0,
                'consultorio' => 'Sillón Odontológico 1 (Box Quirúrgico)',
                'email_verified_at' => now(),
            ]
        );

        // 4.3. Odontólogo Especialista en Endodoncia y Estética
        $drLucas = User::updateOrCreate(
            ['email' => 'lucas.benitez@poolhub.com'],
            [
                'name' => 'Dr. Lucas Benítez (Endodoncia & Estética)',
                'password' => Hash::make('demo1234'),
                'role' => 'usuario',
                'sub_role' => 'empleado',
                'empresa_id' => $empresa->id,
                'activo' => 1,
                'must_change_password' => 0,
                'consultorio' => 'Sillón Odontológico 2 (Box Estético)',
                'email_verified_at' => now(),
            ]
        );

        // 4.4. Recepcionista / Asistente
        $recepcionista = User::updateOrCreate(
            ['email' => 'recepcion.dental@poolhub.com'],
            [
                'name' => 'Carla Rossi (Recepción & Admisión)',
                'password' => Hash::make('demo1234'),
                'role' => 'usuario',
                'sub_role' => 'empleado',
                'empresa_id' => $empresa->id,
                'activo' => 1,
                'must_change_password' => 0,
                'consultorio' => 'Recepción Principal',
                'email_verified_at' => now(),
            ]
        );

        // 5. Configurar Prestadores y Comisiones
        $profesionales = [
            $admin->id => ['tipo' => 'porcentaje', 'valor' => 50],
            $draFlorencia->id => ['tipo' => 'porcentaje', 'valor' => 45],
            $drLucas->id => ['tipo' => 'porcentaje', 'valor' => 40],
        ];

        foreach ($profesionales as $userId => $conf) {
            \App\Models\ProfessionalConfig::updateOrCreate(
                ['empresa_id' => $empresa->id, 'user_id' => $userId],
                [
                    'tipo_contrato' => 'comision',
                    'tipo_comision' => $conf['tipo'],
                    'valor_comision' => $conf['valor'],
                    'sueldo_base' => 0,
                    'especialidades' => ['Odontología General', 'Cirugía Dental', 'Endodoncia', 'Ortodoncia'],
                    'token_portal' => bin2hex(random_bytes(16)),
                ]
            );
        }

        // 6. Servicios Odontológicos con Precios y Duración
        $servicios = [
            [
                'nombre' => 'Consulta Diagnóstica & Relevamiento Odontograma',
                'categoria' => 'Diagnóstico',
                'precio' => 15000,
                'duracion_minutos' => 30,
                'comision_porcentaje' => 30,
                'activo' => 1
            ],
            [
                'nombre' => 'Limpieza Profunda & Profilaxis con Ultrasonido',
                'categoria' => 'Prevención',
                'precio' => 18000,
                'duracion_minutos' => 30,
                'comision_porcentaje' => 35,
                'activo' => 1
            ],
            [
                'nombre' => 'Restauración Estética con Resina Fotocurable',
                'categoria' => 'Operatoria Dental',
                'precio' => 24000,
                'duracion_minutos' => 45,
                'comision_porcentaje' => 40,
                'activo' => 1
            ],
            [
                'nombre' => 'Endodoncia Mecanizada (Tratamiento de Conducto)',
                'categoria' => 'Endodoncia',
                'precio' => 55000,
                'duracion_minutos' => 60,
                'comision_porcentaje' => 45,
                'activo' => 1
            ],
            [
                'nombre' => 'Blanqueamiento Dental LED en Consultorio',
                'categoria' => 'Estética Dental',
                'precio' => 68000,
                'duracion_minutos' => 45,
                'comision_porcentaje' => 35,
                'activo' => 1
            ],
            [
                'nombre' => 'Corona de Porcelana Pura E-Max',
                'categoria' => 'Prótesis Dental',
                'precio' => 110000,
                'duracion_minutos' => 45,
                'comision_porcentaje' => 50,
                'activo' => 1
            ],
            [
                'nombre' => 'Implante Dental Oseointegrado de Titanio',
                'categoria' => 'Implantología',
                'precio' => 150000,
                'duracion_minutos' => 60,
                'comision_porcentaje' => 50,
                'activo' => 1
            ],
            [
                'nombre' => 'Extracción Quirúrgica Simple y Sutura',
                'categoria' => 'Cirugía',
                'precio' => 28000,
                'duracion_minutos' => 30,
                'comision_porcentaje' => 40,
                'activo' => 1
            ],
        ];

        $serviciosCreados = [];
        foreach ($servicios as $s) {
            $serviciosCreados[$s['nombre']] = Servicio::updateOrCreate(
                ['empresa_id' => $empresa->id, 'nombre' => $s['nombre']],
                $s
            );
        }

        // 7. Pacientes Odontológicos de Demostración
        // 7.1. Juan Manuel Díaz (Adulto con Odontograma Completo)
        $paciente1 = Client::updateOrCreate(
            ['empresa_id' => $empresa->id, 'email' => 'juan.diaz@email.com'],
            [
                'name' => 'Juan Manuel Díaz',
                'document' => '34120980',
                'phone' => '+5491155443322',
                'address' => 'Av. Santa Fe 2450, 4to B',
                'city' => 'CABA',
                'province' => 'CABA',
                'active' => 1,
                'is_affiliate' => 1,
                'affiliate_status' => 'active',
            ]
        );

        // 7.2. Sofía Antonella Gómez (Adulto Joven - Estética)
        $paciente2 = Client::updateOrCreate(
            ['empresa_id' => $empresa->id, 'email' => 'sofia.gomez@email.com'],
            [
                'name' => 'Sofía Antonella Gómez',
                'document' => '39880123',
                'phone' => '+5491166778899',
                'address' => 'Calle Corrientes 1840',
                'city' => 'CABA',
                'province' => 'CABA',
                'active' => 1,
                'is_affiliate' => 1,
                'affiliate_status' => 'active',
            ]
        );

        // 7.3. Mateo Rossi (Pediátrico - Odontopediatría)
        $paciente3 = Client::updateOrCreate(
            ['empresa_id' => $empresa->id, 'email' => 'mateo.rossi@email.com'],
            [
                'name' => 'Mateo Rossi (Pediátrico)',
                'document' => '58210345',
                'phone' => '+5491144332211',
                'address' => 'Av. Belgrano 920',
                'city' => 'CABA',
                'province' => 'CABA',
                'active' => 1,
                'is_affiliate' => 0,
            ]
        );

        // 8. Historias Clínicas con Odontogramas Pre-cargados
        // Odontograma Adulto de Juan Manuel Díaz
        $odontogramaJuan = [
            '18' => ['caras' => [], 'estado_general' => 'ausente'],
            '16' => ['caras' => ['c' => 'caries', 'm' => 'caries'], 'estado_general' => 'sano'],
            '14' => ['caras' => ['c' => 'obturacion'], 'estado_general' => 'sano'],
            '21' => ['caras' => [], 'estado_general' => 'corona'],
            '24' => ['caras' => ['c' => 'obturacion', 'v' => 'obturacion'], 'estado_general' => 'sano'],
            '36' => ['caras' => ['c' => 'endodoncia'], 'estado_general' => 'endodoncia'],
            '46' => ['caras' => [], 'estado_general' => 'implante'],
            '48' => ['caras' => [], 'estado_general' => 'ausente'],
        ];

        MedicalRecord::updateOrCreate(
            [
                'empresa_id' => $empresa->id,
                'client_id' => $paciente1->id,
                'specialty' => 'Odontología Integral',
            ],
            [
                'user_id' => $draFlorencia->id,
                'reason_for_visit' => 'Chequeo bucodental semestral y relevamiento de odontograma completo.',
                'diagnosis' => "Paciente masculino de 38 años. Se evidencia caries activa ocluso-mesial en pieza 16. Corona de porcelana en 21 en buen estado. Endodoncia en pieza 36 con buen sellado apical. Implante oseointegrado en cuadrante 4 (pieza 46). Ausencia quirúrgica de terceros molares 18 y 48.",
                'treatment' => "1. Remoción de caries y restauración con resina estética fotocurable en pieza 16.\n2. Profilaxis con ultrasonido y pulido con pasta diamantada.\n3. Control radiográfico de la corona en pieza 21.",
                'internal_notes' => 'Excelente salud periodontal general. Se indica continuar con cepillo eléctrico y uso de hilo dental diario.',
                'specialized_data' => [
                    'vitals' => [
                        'sys' => '120', 'dia' => '80', 'glucose' => '88', 'heart' => '70', 'weight' => '78'
                    ],
                    'medications' => [
                        [
                            'name' => 'Ibuprofeno 400mg',
                            'dose' => '1 comprimido',
                            'frequency' => 'cada 8 hs en caso de molestia',
                            'route' => 'Oral'
                        ]
                    ],
                    'dynamic_fields' => [
                        'Índice de Placa' => 'Bajo (15%)',
                        'Oclusión' => 'Clase I de Angle',
                        'Encías' => 'Rosadas, sin sangrado al sondaje'
                    ],
                    'odontograma' => $odontogramaJuan
                ]
            ]
        );

        // Odontograma de Sofía (Estética)
        $odontogramaSofia = [
            '11' => ['caras' => ['v' => 'obturacion', 'c' => 'obturacion'], 'estado_general' => 'sano'],
            '21' => ['caras' => ['v' => 'obturacion', 'c' => 'obturacion'], 'estado_general' => 'sano'],
            '26' => ['caras' => ['c' => 'caries'], 'estado_general' => 'sano'],
            '37' => ['caras' => ['c' => 'obturacion'], 'estado_general' => 'sano'],
        ];

        MedicalRecord::updateOrCreate(
            [
                'empresa_id' => $empresa->id,
                'client_id' => $paciente2->id,
                'specialty' => 'Estética Dental',
            ],
            [
                'user_id' => $drLucas->id,
                'reason_for_visit' => 'Consulta por estética de sector anterior y evaluación de blanqueamiento.',
                'diagnosis' => "Paciente femenina de 27 años. Presenta carillas directas de resina en 11 y 21 con leve tinción marginal. Caries oclusal incipiente en molar 26. Higiene excelente.",
                'treatment' => "1. Pulido estético de resinas anteriores en 11 y 21.\n2. Blanqueamiento dental LED en consultorio (sesión de 45 minutos).\n3. Turno programado para apertura y resina en pieza 26.",
                'internal_notes' => 'Sensibilidad dental transitoria leve prevista tras la sesión de blanqueamiento.',
                'specialized_data' => [
                    'vitals' => ['sys' => '110', 'dia' => '70', 'glucose' => '82', 'heart' => '68', 'weight' => '56'],
                    'medications' => [],
                    'dynamic_fields' => [
                        'Tono Dental Inicial' => 'A3.5 (Escala Vita)',
                        'Tono Dental Objetivo' => 'B1',
                        'Sensibilidad Previa' => 'Negativa'
                    ],
                    'odontograma' => $odontogramaSofia
                ]
            ]
        );

        // 9. Turnos para HOY en el Hub de Recepción
        $hoy = Carbon::today()->format('Y-m-d');

        // Turno 1: Juan Manuel Díaz a las 09:30 (en Sala de Espera)
        Turno::updateOrCreate(
            [
                'empresa_id' => $empresa->id,
                'client_id' => $paciente1->id,
                'fecha' => $hoy,
                'hora_inicio' => '09:30:00'
            ],
            [
                'user_id' => $draFlorencia->id,
                'servicio_id' => $serviciosCreados['Restauración Estética con Resina Fotocurable']->id ?? null,
                'hora_fin' => '10:15:00',
                'estado' => 'confirmado',
                'estado_cola' => 'sala_espera',
                'hora_arribo' => Carbon::now()->subMinutes(12)->format('H:i:s'),
                'monto' => 24000,
                'notas' => 'Turno confirmado vía portal online. Reparación pieza 16.',
            ]
        );

        // Turno 2: Sofía Gómez a las 10:30 (Llamado a Sillón)
        Turno::updateOrCreate(
            [
                'empresa_id' => $empresa->id,
                'client_id' => $paciente2->id,
                'fecha' => $hoy,
                'hora_inicio' => '10:30:00'
            ],
            [
                'user_id' => $drLucas->id,
                'servicio_id' => $serviciosCreados['Blanqueamiento Dental LED en Consultorio']->id ?? null,
                'hora_fin' => '11:15:00',
                'estado' => 'confirmado',
                'estado_cola' => 'llamado',
                'hora_arribo' => Carbon::now()->subMinutes(2)->format('H:i:s'),
                'monto' => 68000,
                'notas' => 'Paciente lista en sillón para sesión de blanqueamiento.',
            ]
        );

        // Turno 3: Mateo Rossi a las 11:30 (Pendiente de arribo)
        Turno::updateOrCreate(
            [
                'empresa_id' => $empresa->id,
                'client_id' => $paciente3->id,
                'fecha' => $hoy,
                'hora_inicio' => '11:30:00'
            ],
            [
                'user_id' => $draFlorencia->id,
                'servicio_id' => $serviciosCreados['Consulta Diagnóstica & Relevamiento Odontograma']->id ?? null,
                'hora_fin' => '12:00:00',
                'estado' => 'confirmado',
                'estado_cola' => 'pendiente',
                'monto' => 15000,
                'notas' => 'Control infantil escolar y topación con flúor.',
            ]
        );

        echo "🦷 ¡Clínica Dental de Prueba creada con ÉXITO!\n";
        echo "   - Empresa: Clínica Dental de Prueba (slug: clinica-dental-prueba)\n";
        echo "   - Acceso Libre / Bonificada: SÍ\n";
        echo "   - Admin: dental@poolhub.com | Clave: demo1234\n";
        echo "   - Dra. Florencia: florencia.morales@poolhub.com | Clave: demo1234\n";
        echo "   - Dr. Lucas: lucas.benitez@poolhub.com | Clave: demo1234\n";
        echo "   - Recepción: recepcion.dental@poolhub.com | Clave: demo1234\n";
        echo "   - Portal Reserva Online: /c/clinica-dental-prueba/reservar-turno\n";
        echo "   - Pantalla TV Turnero: /portal/turnero/clinica-dental-prueba/tv\n";
    }
}
