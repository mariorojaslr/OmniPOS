<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $planes = [
            [
                'name' => 'Básico',
                'description' => 'Ideal para pequeños emprendimientos. Volumen básico de operaciones.',
                'price' => 25000.00,
                'max_users' => 2,
                'max_products' => 100,
                'max_storage_mb' => 2048.00, // 2GB
                'is_active' => true,
            ],
            [
                'name' => 'Profesional',
                'description' => 'Para comercios en crecimiento. Más usuarios y mayor capacidad.',
                'price' => 45000.00,
                'max_users' => 5,
                'max_products' => 500,
                'max_storage_mb' => 10240.00, // 10GB
                'is_active' => true,
            ],
            [
                'name' => 'Empresarial',
                'description' => 'Solución completa para empresas establecidas con alto flujo.',
                'price' => 85000.00,
                'max_users' => 15,
                'max_products' => 2500,
                'max_storage_mb' => 51200.00, // 50GB
                'is_active' => true,
            ],
            [
                'name' => 'Corporativo',
                'description' => 'Para grandes redes de puntos de venta e inventarios extensos.',
                'price' => 150000.00,
                'max_users' => 50,
                'max_products' => 10000,
                'max_storage_mb' => 204800.00, // 200GB
                'is_active' => true,
            ]
        ];

        foreach ($planes as $plan) {
            \App\Models\Plan::firstOrCreate(['name' => $plan['name']], $plan);
        }
    }
}
