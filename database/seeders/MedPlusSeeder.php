<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\Empresa;
use App\Models\EmpresaConfig;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MedPlusSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Planes Base
        $planPro = Plan::updateOrCreate(
            ['name' => 'Plan Pro'],
            [
                'description' => 'Ideal para centros médicos en expansión',
                'price' => 25000,
                'max_users' => 50,
                'max_products' => 1000,
                'max_storage_mb' => 5120, // 5GB
                'is_active' => true,
            ]
        );

        // 2. Crear Revendedor de prueba
        $revendedor = User::updateOrCreate(
            ['email' => 'revendedor@poolhub.com'],
            [
                'name' => 'Revendedor Principal',
                'password' => Hash::make('poolhub2026'),
                'role' => 'revendedor',
                'activo' => true,
                'status' => 'activo',
            ]
        );

        // 3. Crear Empresa Med Plus
        $medPlus = Empresa::updateOrCreate(
            ['slug' => 'med-plus'],
            [
                'nombre_comercial' => 'Med Plus',
                'razon_social' => 'Med Plus Centro Médico S.A.',
                'email' => 'admin@medplus.com',
                'telefono' => '1122334455',
                'activo' => true,
                'plan_id' => $planPro->id,
                'reseller_id' => $revendedor->id,
                'status' => 'activa',
                'fecha_vencimiento' => now()->addYear(),
            ]
        );

        // 4. Configuración Visual de Med Plus
        EmpresaConfig::updateOrCreate(
            ['empresa_id' => $medPlus->id],
            [
                'color_primary' => '#0ea5e9', // Azul Médico OmniPOS
                'color_secondary' => '#FFFFFF',
                'logo' => null, 
                'mod_turnos' => true,
                'mod_hce' => true,
                'mod_afiliados' => true,
                'mod_afip' => true,
                'mod_pagos' => true,
                'mod_backups' => true,
                'theme' => 'dark',
            ]
        );

        // 5. Crear algunos datos de ejemplo (Pacientes y Turnos)
        $paciente = \DB::table('pacientes')->insertGetId([
            'empresa_id' => $medPlus->id,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'dni' => '12345678',
            'email' => 'juan@example.com',
            'telefono' => '1122334455',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        for ($i = 8; $i <= 18; $i++) {
            \DB::table('turnos')->insert([
                'empresa_id' => $medPlus->id,
                'paciente_id' => $paciente,
                'profesional_id' => 1,
                'fecha' => now()->format('Y-m-d'),
                'hora' => str_pad($i, 2, '0', STR_PAD_LEFT) . ':00',
                'estado' => $i < 12 ? 'atendido' : 'pendiente',
                'motivo' => 'Consulta de Control',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 5. Crear Administrador de Med Plus
        User::updateOrCreate(
            ['email' => 'admin@medplus.com'],
            [
                'name' => 'Admin Med Plus',
                'password' => Hash::make('medplus123'),
                'role' => 'empresa',
                'empresa_id' => $medPlus->id,
                'activo' => true,
                'status' => 'activo',
            ]
        );
    }
}
