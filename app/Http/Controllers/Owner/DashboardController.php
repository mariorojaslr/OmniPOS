<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Centro de Mando Maestro (OWNER)
     */
    public function index()
    {
        // Métricas de Alto Nivel
        $empresasCount   = Empresa::count();
        $empresasActivas = Empresa::where('activo', true)->count();
        $usuariosCount   = User::count();
        $articulosCount  = Schema::hasTable('products') ? \App\Models\Product::count() : 0;
        $clientesCount   = Schema::hasTable('clients') ? \App\Models\Client::count() : 0;
        $facturacionTotal = Schema::hasTable('ventas') ? \App\Models\Venta::whereMonth('created_at', now()->month)->sum('total') : 0;
        
        $data = [
            // KPIs Principales
            'empresasCount'     => $empresasCount,
            'empresasActivas'   => $empresasActivas,
            'usuariosCount'     => $usuariosCount,
            'articulosCount'    => $articulosCount,
            'clientesCount'     => $clientesCount,
            'facturacionMes'    => '$ ' . number_format($facturacionTotal, 0, ',', '.'),
            'mrr'               => '$ ' . number_format($empresasCount * 25000, 0, ',', '.'),
            
            // Salud del Ecosistema
            'saludVentas'       => 94.2,
            'saludGastos'       => 12.5,
            'saludGlobal'       => 88.5,
            'growth'            => 12.5,
            
            // Radar CRM
            'landingVisits'     => 1240,
            'demoEntries'       => 85,
            'conversionRate'    => 12.4,
            
            // Listados Dinámicos
            'empresas'          => Empresa::with('plan')->get(),
            'ultimasEmpresas'   => Empresa::with('plan')->latest()->take(5)->get(),
            'globalActivities'  => Schema::hasTable('activity_logs') ? \App\Models\ActivityLog::with(['empresa', 'user'])->latest()->take(10)->get() : [],
            'crmActivities'     => Schema::hasTable('crm_activities') ? \App\Models\CrmActivity::latest()->take(5)->get() : [],
            'ultimosTickets'    => Schema::hasTable('support_tickets') ? \App\Models\SupportTicket::with('empresa')->latest()->take(5)->get() : [],
            
            // Infraestructura
            'costoProyectado'   => '$ 45.20',
            'dbSize'            => '128 MB',
            'consumoStorage'    => '4.2 GB',
            'archivosSubidos'   => '1.250',
            
            // Agente Social
            'agent_data'        => [
                'facebook'  => ['scanned' => 842, 'hunted' => 12],
                'instagram' => ['scanned' => 1205, 'hunted' => 28],
                'google'    => ['scanned' => 450, 'hunted' => 5],
                'tiktok'    => ['scanned' => 950, 'hunted' => 45],
            ],
            'settings'          => SystemSetting::pluck('value', 'key')->toArray(),
        ];

        return view('owner.dashboard', $data);
    }

    /**
     * Actualizar ajustes globales del sistema
     */
    public function updateSettings(Request $request)
    {
        try {
            foreach ($request->except('_token') as $key => $value) {
                SystemSetting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
            return back()->with('success', 'Configuración maestra actualizada.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Fallo al actualizar ajustes: ' . $e->getMessage());
        }
    }
}
