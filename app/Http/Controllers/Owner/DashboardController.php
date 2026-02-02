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

            // Total de empresas
            'empresasCount' => Empresa::count(),

            // Empresas activas
            'empresasActivas' => Empresa::where('activo', true)->count(),

            // Empresas vencidas
            'empresasVencidas' => Empresa::whereDate(
                'fecha_vencimiento',
                '<',
                now()->toDateString()
            )->count(),

            // Usuarios asociados a empresas
            'usuariosCount' => User::whereNotNull('empresa_id')->count(),
        ]);
    }
}
