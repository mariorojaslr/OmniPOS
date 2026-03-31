<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('owner.dashboard', [

            // Métricas Reales
            'empresasCount'    => Empresa::count(),
            'empresasActivas'  => Empresa::where('activo', true)->count(),
            'empresasVencidas' => Empresa::whereDate('fecha_vencimiento', '<', now()->toDateString())->count(),
            'usuariosCount'    => User::whereNotNull('empresa_id')->count(),

            // Métricas Simuladas (hasta aplicar bunny real tracker)
            'consumoStorage'    => '45.2 GB',
            'consumoTrafico'    => '128.4 GB',
            'archivosSubidos'   => '1,432',
            'imagenesSubidas'   => '1,105',
            'streamingMensual'  => '340 hs',
            'facturacionMes'    => '$225,000',
            'mrr'               => '$650,000',

            // Últimas Empresas
            'ultimasEmpresas'   => Empresa::orderByDesc('created_at')->limit(5)->get(),
        ]);
    }
}
