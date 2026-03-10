<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empresa;
use Carbon\Carbon;

class CheckSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multipos:check-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica las fechas de vencimiento de las empresas y suspende cuentas morosas.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Iniciando auditoria de suscripciones...");

        // Buscar empresas que están "activas" PERO su fecha de vencimiento ya pasó.
        // Tolerancia: 1 día de gracia completo (ayer).
        // Si venció ayer, y hoy son las 00:00, se suspende.
        
        $vencidas = Empresa::where('status', 'activa')
            ->whereNotNull('fecha_vencimiento')
            ->whereDate('fecha_vencimiento', '<', Carbon::today())
            ->get();

        foreach ($vencidas as $empresa) {
            $this->info("Suspendiendo a la empresa: {$empresa->nombre_comercial} (Venció el {$empresa->fecha_vencimiento->format('Y-m-d')})");
            
            $empresa->update([
                'status' => 'suspendida'
            ]);
            
            // Aquí en un futuro se le puede enviar el email a: $empresa->email
        }

        $this->info("Auditoría completada. Empresas afectadas: {$vencidas->count()}");
        
        return Command::SUCCESS;
    }
}
