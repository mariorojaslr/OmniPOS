<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\Empresa;
use App\Models\User;
use App\Models\Venta;
use App\Models\SupportTicket;
use App\Models\CrmActivity;

class OmniPOSSystemSeeder extends Seeder
{
    public function run()
    {
        $medPlus = Empresa::where('nombre_comercial', 'Med Plus')->first();
        if (!$medPlus) return;

        $admin = $medPlus->users()->first();
        if (!$admin) return;

        // 1. Generar Ventas para métricas
        for ($i = 0; $i < 15; $i++) {
            $totalConIva = rand(5000, 50000);
            $totalSinIva = $totalConIva / 1.21;
            $totalIva = $totalConIva - $totalSinIva;

            Venta::create([
                'empresa_id' => $medPlus->id,
                'user_id' => $admin->id,
                'total_sin_iva' => $totalSinIva,
                'total_iva' => $totalIva,
                'total_con_iva' => $totalConIva,
                'subtotal' => $totalSinIva,
                'total' => $totalConIva,
                'metodo_pago' => 'efectivo',
                'created_at' => now()->subHours(rand(1, 72))
            ]);
        }

        // 2. Generar Actividad en la Bitácora
        $acciones = [
            'Realizó una venta de insumos médicos',
            'Registró una nueva historia clínica (HCE)',
            'Actualizó el stock de vacunas',
            'Emitió un presupuesto para cirugía',
            'Creó un nuevo usuario operativo',
            'Configuró el certificado AFIP',
            'Realizó un backup de seguridad',
            'Actualizó los precios de farmacia',
            'Envió reporte de liquidaciones a profesionales',
            'Sincronizó catálogo con tienda online'
        ];

        foreach ($acciones as $idx => $accion) {
            ActivityLog::create([
                'empresa_id' => $medPlus->id,
                'user_id' => $admin->id,
                'description' => $accion,
                'created_at' => now()->subMinutes($idx * 45)
            ]);
        }

        // 3. Generar CRM Activity (Para el Radar del Owner)
        $canales = ['whatsapp', 'facebook', 'instagram', 'web'];
        foreach ($canales as $canal) {
            CrmActivity::create([
                'channel' => $canal,
                'target_name' => 'Lead de Prueba ' . rand(1, 100),
                'details' => 'Interesado en Plan Profesional para Clínica',
                'status' => 'pendiente',
                'created_at' => now()->subHours(rand(1, 24))
            ]);
        }

        // 4. Generar Tickets de Soporte
        SupportTicket::create([
            'empresa_id' => $medPlus->id,
            'user_id' => $admin->id,
            'subject' => 'Consulta sobre integración AFIP',
            'message' => 'Necesito ayuda para configurar los puntos de venta.',
            'status' => 'abierto',
            'priority' => 'alta',
            'created_at' => now()->subHours(2)
        ]);
    }
}
