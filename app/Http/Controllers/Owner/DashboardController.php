<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Plan;
use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $today = now()->toDateString();
            
            // Recopilación de métricas reales
            $empresasCount = Empresa::count();
            $empresasActivas = Empresa::where('activo', true)->count();
            $usuariosCount = User::count();
            $articulosCount = Schema::hasTable('products') ? \App\Models\Product::count() : 0;
            $clientesCount = Schema::hasTable('clients') ? \App\Models\Client::count() : 0;
            $facturacionMes = Schema::hasTable('ventas') ? \App\Models\Venta::whereMonth('created_at', now()->month)->sum('total') : 0;
            
            $data = [
                // KPIs Principales
                'empresasCount'     => $empresasCount,
                'empresasActivas'   => $empresasActivas,
                'usuariosCount'     => $usuariosCount,
                'articulosCount'    => $articulosCount,
                'clientesCount'     => $clientesCount,
                'facturacionMes'    => '$ ' . number_format($facturacionMes, 0, ',', '.'),
                'mrr'               => '$ ' . number_format($empresasCount * 25000, 0, ',', '.'),
                
                // Salud y Gráficos
                'saludVentas'       => 94.2,
                'saludGastos'       => 12.5,
                'saludGlobal'       => 88.5,
                'growth'            => 12.5,
                
                // Radar CRM (Placeholders realistas)
                'landingVisits'     => 1240,
                'demoEntries'       => 85,
                'conversionRate'    => 12.4,
                
                // Listados
                'empresas'          => Empresa::with('plan')->get(),
                'ultimasEmpresas'   => Empresa::with('plan')->latest()->take(5)->get(),
                'globalActivities'  => Schema::hasTable('activity_logs') ? \App\Models\ActivityLog::with(['empresa', 'user'])->latest()->take(10)->get() : [],
                'crmActivities'     => Schema::hasTable('crm_activities') ? \App\Models\CrmActivity::latest()->take(5)->get() : [],
                'ultimosTickets'    => Schema::hasTable('support_tickets') ? \App\Models\SupportTicket::with('empresa')->latest()->take(5)->get() : [],
                
                // Infraestructura (Placeholders)
                'costoProyectado'   => '$ 45.20',
                'dbSize'            => '128 MB',
                'consumoStorage'    => '4.2 GB',
                'archivosSubidos'   => '1.250',
                
                // Agente Social
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
