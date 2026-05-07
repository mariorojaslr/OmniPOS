<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Plan;
use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $today = now()->toDateString();
            
            $data = [
                'empresasCount'     => Empresa::count(),
                'empresasActivas'   => Empresa::where('activo', true)->count(),
                'usuariosCount'     => User::count(),
                'articulosCount'    => \App\Models\Product::count(),
                'clientesCount'     => \App\Models\Client::count(),
                'facturacionMes'    => \Schema::hasTable('ventas') ? number_format(\App\Models\Venta::whereMonth('created_at', now()->month)->sum('total'), 0, ',', '.') : 0,
                'mrr'               => number_format(Empresa::count() * 25000, 0, ',', '.'),
                'saludVentas'       => 94.2,
                'growth'            => 12.5,
                'empresas'          => Empresa::with('plan')->get(),
                'globalActivities'  => [],
                'agent_data'        => [
                    'facebook' => ['scanned' => 842, 'hunted' => 12],
                    'instagram' => ['scanned' => 1205, 'hunted' => 28],
                    'google' => ['scanned' => 450, 'hunted' => 5],
                    'twitter' => ['scanned' => 120, 'hunted' => 2],
                    'linkedin' => ['scanned' => 310, 'hunted' => 8],
                    'tiktok' => ['scanned' => 950, 'hunted' => 45],
                ],
                'settings'          => SystemSetting::pluck('value', 'key')->toArray(),
            ];

            // Forzamos el renderizado para capturar errores de Blade
            return view('owner.dashboard', $data)->render();

        } catch (\Throwable $e) {
            die("<div style='background:#1a1a1a;color:#ff5555;padding:30px;font-family:monospace;border:5px solid red;'>
                <h1 style='color:white;'>⚠️ ERROR DE RENDERIZADO DETECTADO</h1>
                <p><b>Mensaje:</b> " . $e->getMessage() . "</p>
                <p><b>Archivo:</b> " . $e->getFile() . ":" . $e->getLine() . "</p>
                <hr>
                <p><b>Sugerencia:</b> Revisá si falta alguna ruta o variable en la vista.</p>
            </div>");
        }
    }

    public function updateSettings(Request $request)
    {
        try {
            foreach ($request->except('_token') as $key => $value) {
                SystemSetting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
            return back()->with('success', 'Configuración actualizada correctamente.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al actualizar configuración: ' . $e->getMessage());
        }
    }
}
