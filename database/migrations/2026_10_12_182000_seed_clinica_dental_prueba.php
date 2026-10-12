<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Plan;
use App\Models\Empresa;
use App\Models\EmpresaConfig;
use App\Models\User;
use App\Models\Client;
use App\Models\Servicio;
use App\Models\ProfesionalConfig;
use App\Models\MedicalRecord;
use App\Models\Turno;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            // 1. Obtener o crear Plan
            $plan = Plan::first();
            $planId = $plan ? $plan->id : null;

            // 2. Crear o actualizar la empresa Clínica Dental de Prueba
            $empresa = Empresa::updateOrCreate(
                ['slug' => 'clinica-dental-prueba'],
                [
                    'nombre_comercial' => 'Clínica Dental de Prueba',
                    'razon_social' => 'Clínica Dental de Prueba S.R.L.',
                    'email' => 'dental@poolhub.com',
                    'telefono' => '+5491134567890',
                    'activo' => true,
                    'plan_id' => $planId,
                    'status' => 'activa',
                    'is_bonificated' => true, // Bonificada permanentemente
                    'fecha_vencimiento' => now()->addYears(10),
                    'config_pasarelas' => [
                        'online_booking_enabled' => 1,
                        'online_booking_price' => 0,
                        'online_booking_gateway' => 'MercadoPago'
                    ],
                ]
            );

            // 3. Configuración del Módulo Odontológico
            EmpresaConfig::updateOrCreate(
                ['empresa_id' => $empresa->id],
                [
                    'color_primary' => '#0d9488', // Verde Azulado Odontológico
                    'color_secondary' => '#0284c7', // Azul Clínico
                    'theme' => 'light',
                    'tipo_clinica' => 'clinica_odontologica',
                    'mod_odontograma' => true,
                    'mod_internaciones' => false,
                    'mod_turnos' => true,
                    'mod_hce' => true,
                    'mod_campos_dinamicos' => true,
                    'mod_afiliados' => true,
                    'mod_ventas' => true,
                    'mod_compras' => true,
                    'terminologia_espacio' => 'consultorio',
                ]
            );

            // 4. Usuarios de Acceso Libre
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

            // 5. Profesionales Config
            foreach ([$admin->id, $draFlorencia->id, $drLucas->id] as $uid) {
                ProfesionalConfig::updateOrCreate(
                    ['empresa_id' => $empresa->id, 'user_id' => $uid],
                    [
                        'tipo_contrato' => 'comision',
                        'tipo_comision' => 'porcentaje',
                        'valor_comision' => 45,
                        'sueldo_base' => 0,
                        'especialidades' => ['Odontología General', 'Cirugía Dental', 'Endodoncia', 'Ortodoncia'],
                        'token_portal' => bin2hex(random_bytes(16)),
                    ]
                );
            }

            // 6. Servicios Odontológicos
            $servicios = [
                ['nombre' => 'Consulta Diagnóstica & Odontograma', 'categoria' => 'Diagnóstico', 'precio' => 15000, 'duracion_minutos' => 30],
                ['nombre' => 'Limpieza Profunda & Profilaxis con Ultrasonido', 'categoria' => 'Prevención', 'precio' => 18000, 'duracion_minutos' => 30],
                ['nombre' => 'Restauración con Resina Fotocurable', 'categoria' => 'Operatoria Dental', 'precio' => 24000, 'duracion_minutos' => 45],
                ['nombre' => 'Endodoncia Mecanizada (Tratamiento de Conducto)', 'categoria' => 'Endodoncia', 'precio' => 55000, 'duracion_minutos' => 60],
                ['nombre' => 'Blanqueamiento Dental LED en Consultorio', 'categoria' => 'Estética Dental', 'precio' => 68000, 'duracion_minutos' => 45],
                ['nombre' => 'Corona de Porcelana Pura E-Max', 'categoria' => 'Prótesis Dental', 'precio' => 110000, 'duracion_minutos' => 45],
                ['nombre' => 'Implante Dental Oseointegrado de Titanio', 'categoria' => 'Implantología', 'precio' => 150000, 'duracion_minutos' => 60],
                ['nombre' => 'Extracción Quirúrgica Simple y Sutura', 'categoria' => 'Cirugía', 'precio' => 28000, 'duracion_minutos' => 30],
            ];

            $serviciosMap = [];
            foreach ($servicios as $s) {
                $serviciosMap[$s['nombre']] = Servicio::updateOrCreate(
                    ['empresa_id' => $empresa->id, 'nombre' => $s['nombre']],
                    array_merge($s, ['comision_porcentaje' => 40, 'activo' => 1])
                );
            }

            // 7. Pacientes Odontológicos
            $pacienteJuan = Client::updateOrCreate(
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

            $pacienteSofia = Client::updateOrCreate(
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

            // 8. Historias Clínicas con Odontograma
            $odontogramaJuan = [
                '18' => ['caras' => [], 'estado_general' => 'ausente'],
                '16' => ['caras' => ['c' => 'caries', 'm' => 'caries'], 'estado_general' => 'sano'],
                '14' => ['caras' => ['c' => 'obturacion'], 'estado_general' => 'sano'],
                '21' => ['caras' => [], 'estado_general' => 'corona'],
                '36' => ['caras' => ['c' => 'endodoncia'], 'estado_general' => 'endodoncia'],
                '46' => ['caras' => [], 'estado_general' => 'implante'],
                '48' => ['caras' => [], 'estado_general' => 'ausente'],
            ];

            MedicalRecord::updateOrCreate(
                [
                    'empresa_id' => $empresa->id,
                    'client_id' => $pacienteJuan->id,
                    'specialty' => 'Odontología Integral',
                ],
                [
                    'user_id' => $draFlorencia->id,
                    'reason_for_visit' => 'Chequeo bucodental semestral y relevamiento de odontograma completo.',
                    'diagnosis' => "Paciente masculino de 38 años. Caries ocluso-mesial activa en pieza 16. Corona cerámica en 21. Endodoncia en 36. Implante en 46. Terceros molares ausentes.",
                    'treatment' => "Apertura y restauración con resina estética en 16. Profilaxis con ultrasonido.",
                    'specialized_data' => [
                        'vitals' => ['sys' => '120', 'dia' => '80', 'glucose' => '88', 'heart' => '70', 'weight' => '78'],
                        'medications' => [['name' => 'Ibuprofeno 400mg', 'dose' => '1 comp', 'frequency' => 'cada 8 hs', 'route' => 'Oral']],
                        'dynamic_fields' => ['Higiene Bucal' => 'Buena', 'Oclusión' => 'Clase I de Angle'],
                        'odontograma' => $odontogramaJuan
                    ]
                ]
            );

            // 9. Turnos para hoy en la sala de espera
            $hoy = Carbon::today()->format('Y-m-d');

            Turno::updateOrCreate(
                ['empresa_id' => $empresa->id, 'client_id' => $pacienteJuan->id, 'fecha' => $hoy, 'hora_inicio' => '09:30:00'],
                [
                    'user_id' => $draFlorencia->id,
                    'servicio_id' => $serviciosMap['Restauración con Resina Fotocurable']->id ?? null,
                    'hora_fin' => '10:15:00',
                    'estado' => 'confirmado',
                    'estado_cola' => 'sala_espera',
                    'hora_arribo' => Carbon::now()->subMinutes(10)->format('H:i:s'),
                    'monto' => 24000,
                    'notas' => 'Turno web confirmado. Sala de espera.',
                ]
            );

            Turno::updateOrCreate(
                ['empresa_id' => $empresa->id, 'client_id' => $pacienteSofia->id, 'fecha' => $hoy, 'hora_inicio' => '10:30:00'],
                [
                    'user_id' => $drLucas->id,
                    'servicio_id' => $serviciosMap['Blanqueamiento Dental LED en Consultorio']->id ?? null,
                    'hora_fin' => '11:15:00',
                    'estado' => 'confirmado',
                    'estado_cola' => 'llamado',
                    'hora_arribo' => Carbon::now()->subMinutes(2)->format('H:i:s'),
                    'monto' => 68000,
                    'notas' => 'Paciente en sillón. Blanqueamiento LED.',
                ]
            );

        } catch (\Exception $e) {
            \Log::error('Error en seed_clinica_dental_prueba: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No destruir datos en rollback
    }
};
