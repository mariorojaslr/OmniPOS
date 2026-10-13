<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;
use App\Models\Empresa;
use App\Models\EmpresaConfig;
use App\Models\Turno;
use App\Models\Servicio;
use App\Models\Client;
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
            $hashedPassword = Hash::make('demo1234');

            // 1. OWNER - admin@poolhub.com
            User::updateOrCreate(
                ['email' => 'admin@poolhub.com'],
                [
                    'name' => 'Administrador Global (Owner)',
                    'password' => $hashedPassword,
                    'role' => 'owner',
                    'sub_role' => 'cajero',
                    'activo' => 1,
                    'status' => 'activo',
                    'must_change_password' => 0,
                    'email_verified_at' => now(),
                ]
            );

            // Asegurar que si el Owner original es mario.rojas.coach@gmail.com, también acepte demo1234
            $mario = User::where('email', 'mario.rojas.coach@gmail.com')->first();
            if ($mario) {
                $mario->update([
                    'password' => $hashedPassword,
                    'activo' => 1,
                    'status' => 'activo',
                ]);
            }

            // 2. EMPRESA DENTAL (clinica-dental-prueba)
            $clinicaDental = Empresa::where('slug', 'clinica-dental-prueba')->first();
            if (!$clinicaDental) {
                $clinicaDental = Empresa::create([
                    'slug' => 'clinica-dental-prueba',
                    'nombre_comercial' => 'Clínica Dental de Prueba',
                    'razon_social' => 'Clínica Dental de Prueba S.R.L.',
                    'email' => 'dental@poolhub.com',
                    'telefono' => '+5491134567890',
                    'activo' => 1,
                    'status' => 'activa',
                    'is_bonificated' => 1,
                    'fecha_vencimiento' => now()->addYears(10),
                ]);
            } else {
                $clinicaDental->update([
                    'activo' => 1,
                    'status' => 'activa',
                    'is_bonificated' => 1,
                    'fecha_vencimiento' => now()->addYears(10),
                ]);
            }

            // 3. EMPRESA DENTAL ALIAS (dental-premium) para que ambas URLs funcionen
            $dentalPremium = Empresa::updateOrCreate(
                ['slug' => 'dental-premium'],
                [
                    'nombre_comercial' => 'Dental Premium (Clínica Odontológica)',
                    'razon_social' => 'Dental Premium S.R.L.',
                    'email' => 'admin@dentalpremium.com',
                    'telefono' => '+5491134567890',
                    'activo' => 1,
                    'status' => 'activa',
                    'is_bonificated' => 1,
                    'fecha_vencimiento' => now()->addYears(10),
                ]
            );

            EmpresaConfig::updateOrCreate(
                ['empresa_id' => $dentalPremium->id],
                [
                    'color_primary' => '#0d9488',
                    'color_secondary' => '#0284c7',
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

            // 4. USUARIOS DENTALES
            // dental@poolhub.com (Asociado a clinica-dental-prueba)
            User::updateOrCreate(
                ['email' => 'dental@poolhub.com'],
                [
                    'name' => 'Dr. Fernando Varela (Director Dental)',
                    'password' => $hashedPassword,
                    'role' => 'empresa',
                    'sub_role' => 'cajero',
                    'empresa_id' => $clinicaDental->id,
                    'activo' => 1,
                    'status' => 'activo',
                    'must_change_password' => 0,
                    'consultorio' => 'Consultorio Central',
                    'email_verified_at' => now(),
                ]
            );

            // admin@dentalpremium.com (Asociado a dental-premium)
            User::updateOrCreate(
                ['email' => 'admin@dentalpremium.com'],
                [
                    'name' => 'Dr. Fernando Varela (Dental Premium)',
                    'password' => $hashedPassword,
                    'role' => 'empresa',
                    'sub_role' => 'cajero',
                    'empresa_id' => $dentalPremium->id,
                    'activo' => 1,
                    'status' => 'activo',
                    'must_change_password' => 0,
                    'consultorio' => 'Consultorio Dental 1',
                    'email_verified_at' => now(),
                ]
            );

            // 5. DEMO MÉDICA (Sanatorio del Sol - clinica-demo)
            $clinicaDemo = Empresa::where('slug', 'clinica-demo')->first();
            if ($clinicaDemo) {
                User::updateOrCreate(
                    ['email' => 'demo@poolhub.com'],
                    [
                        'name' => 'Dr. Leandro Gómez (Admin Demo Médica)',
                        'password' => $hashedPassword,
                        'role' => 'empresa',
                        'sub_role' => 'cajero',
                        'empresa_id' => $clinicaDemo->id,
                        'activo' => 1,
                        'status' => 'activo',
                        'must_change_password' => 0,
                        'email_verified_at' => now(),
                    ]
                );
            }

            // 6. Turnos de ejemplo para dental-premium por si abren el Smart TV con ese slug
            $paciente = Client::firstOrCreate(
                ['empresa_id' => $dentalPremium->id, 'document' => '34120980'],
                [
                    'name' => 'Juan Manuel Díaz',
                    'email' => 'juan.diaz@email.com',
                    'phone' => '+5491155443322',
                    'active' => 1,
                ]
            );

            $adminPremium = User::where('email', 'admin@dentalpremium.com')->first();
            $hoy = Carbon::today()->format('Y-m-d');

            Turno::updateOrCreate(
                ['empresa_id' => $dentalPremium->id, 'client_id' => $paciente->id, 'fecha' => $hoy, 'hora_inicio' => '10:00:00'],
                [
                    'user_id' => $adminPremium?->id,
                    'hora_fin' => '10:45:00',
                    'estado' => 'confirmado',
                    'estado_cola' => 'sala_espera',
                    'hora_arribo' => Carbon::now()->subMinutes(5)->format('H:i:s'),
                    'monto' => 24000,
                    'notas' => 'Consulta odontológica y odontograma.',
                ]
            );

        } catch (\Exception $e) {
            \Log::error('Error en seed unificación de credenciales: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
