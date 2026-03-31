<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Str;

class SystemRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza slugs de empresas y sub_roles de usuarios';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando actualización de datos del sistema...');

        // 1. Actualizar Slugs de Empresas
        $empresas = Empresa::all();
        $countEmpresas = 0;
        foreach ($empresas as $empresa) {
            if (!$empresa->slug) {
                $baseSlug = Str::slug($empresa->nombre_comercial ?: $empresa->nombre);
                $empresa->update(['slug' => $baseSlug . '-' . $empresa->id]);
                $countEmpresas++;
            }
        }
        $this->info("✅ Se actualizaron $countEmpresas slugs de empresas.");

        // 2. Asegurar que todos los usuarios tengan un sub_role por defecto
        $countUsers = User::whereNull('sub_role')->orWhere('sub_role', '')->update(['sub_role' => 'cajero']);
        $this->info("✅ Se actualizaron $countUsers sub_roles de usuarios.");

        $this->info('✨ Proceso completado con éxito.');
        return 0;
    }
}
