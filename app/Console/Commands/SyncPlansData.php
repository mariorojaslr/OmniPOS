<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Plan;

class SyncPlansData extends Command
{
    protected $signature = 'db:sync-plans';
    protected $description = 'Sincroniza los valores de los planes de suscripción desde Staging a Producción';

    public function handle()
    {
        $this->info("Iniciando sincronización de planes...");

        $plans = [
            [
                "name" => "Básico",
                "description" => "Ideal para pequeños negocios",
                "price" => 25000.00,
                "max_users" => 2,
                "max_products" => 50,
                "max_storage_mb" => 500.00,
                "is_active" => true
            ],
            [
                "name" => "Profesional",
                "description" => "Para empresas en crecimiento",
                "price" => 45000.00,
                "max_users" => 10,
                "max_products" => 500,
                "max_storage_mb" => 5000.00,
                "is_active" => true
            ],
            [
                "name" => "Enterprise",
                "description" => "Control total para grandes empresas",
                "price" => 85000.00,
                "max_users" => 50,
                "max_products" => 5000,
                "max_storage_mb" => 25000.00,
                "is_active" => true
            ]
        ];

        foreach ($plans as $planData) {
            Plan::updateOrCreate(
                ['name' => $planData['name']],
                $planData
            );
            $this->line("Plan '{$planData['name']}' sincronizado.");
        }

        $this->info("✅ Planes sincronizados correctamente.");
    }
}
