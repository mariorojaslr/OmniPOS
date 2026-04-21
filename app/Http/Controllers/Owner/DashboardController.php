<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;
use App\Models\CrmActivity;
use App\Models\SupportTicket;
use App\Models\SuscripcionPago;

class DashboardController extends Controller
{
    public function index()
    {
        $empresasCount    = Empresa::count();
        $empresasActivas  = Empresa::where('activo', true)->count();
        $usuariosCount    = User::whereNotNull('empresa_id')->count();

        // 💰 FINANZAS REALES (Global)
        $facturacionMesNum = \App\Models\Venta::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_con_iva');

        // Cálculo de MRR (Ingreso Mensual Recurrente)
        $mrrNum = Empresa::where('activo', true)
            ->join('plans', 'empresas.plan_id', '=', 'plans.id')
            ->sum('plans.price');

        // 📦 INFRAESTRUCTURA (Counts Reales)
        $imagenesCountValue = \App\Models\ProductImage::count();
        $videosCountValue   = \App\Models\ProductVideo::count();
        
        // Peso de la DB (en MB) - Blindaje para Producción/Hosting
        try {
            $dbName = config('database.connections.mysql.database');
            $dbSizeQueryResult = \DB::select('SELECT SUM(data_length + index_length) / 1024 / 1024 AS size FROM information_schema.TABLES WHERE table_schema = ?', [$dbName]);
            $dbSizeMB = round($dbSizeQueryResult[0]->size ?? 0, 2);
        } catch (\Exception $e) {
            $dbSizeMB = 0; 
        }

        $consumoGB = round(($imagenesCountValue * 0.5) / 1024, 2); // Estimado 0.5MB por foto
        $costoStorage = $consumoGB * 0.01; // $0.01 USD por GB (Bunny.net aprox)
        $costoTrafico = ($consumoGB * 2.5) * 0.01; // Estimación de tráfico
        
        // Proyección a fin de mes
        $diaActual = max(now()->day, 1);
        $diasMes = now()->daysInMonth;
        $costoProyectadoTotal = (($costoStorage + $costoTrafico) / $diaActual) * $diasMes;

        // 📉 SALUD DEL SISTEMA
        $saludVentas = min(round(($facturacionMesNum / 1000000) * 100), 100);
        $gastosGlobal = \App\Models\Expense::whereMonth('created_at', now()->month)->sum('amount');
        $saludGastos = $facturacionMesNum > 0 ? round(($gastosGlobal / $facturacionMesNum) * 100) : 0;

        // 🛰️ TRÁFICO Y ADQUISICIÓN
        $today = now()->toDateString();
        $traffic = \App\Models\OwnerSystemTraffic::firstOrCreate(['date' => $today]);

        $landingVisits = $traffic->landing_visits;
        $demoEntries = $traffic->demo_clicks;
        $botReferrals = $traffic->bot_referrals;
        
        $conversionRate = $landingVisits > 0 ? round(($demoEntries / $landingVisits) * 100, 1) : 0;

        // ⚙️ CONFIGURACIONES DEL SISTEMA
        $settings = \App\Models\SystemSetting::pluck('value', 'key')->toArray();

        $articulosCountValue = \App\Models\Product::count();
        $clientesCountValue  = \App\Models\Client::count();

        // 📝 ACTIVIDAD CRM E HISTORIAL (Restore)
        $crmActivities = CrmActivity::latest()->limit(10)->get();
        $nuevosLeads   = User::where('status', 'prospecto')->where('role', 'empresa')->latest()->limit(5)->get();

        // 🛰️ AGENTE SOCIAL LIVE (Scanner data restore)
        $channels = ['LinkedIn', 'Instagram', 'Facebook', 'WhatsApp', 'Telegram', 'System Mail'];
        $scanned_counts = CrmActivity::selectRaw('channel, count(*) as total')->groupBy('channel')->pluck('total', 'channel')->toArray();
        $hunted_counts  = User::where('status', 'prospecto')->selectRaw('lead_source, count(*) as total')->groupBy('lead_source')->pluck('total', 'lead_source')->toArray();
        
        $agent_data = [];
        foreach($channels as $ch) {
            $agent_data[$ch] = [
                'name'    => $ch,
                'scanned' => $scanned_counts[$ch] ?? 0,
                'hunted'  => $hunted_counts[$ch] ?? 0
            ];
        }

        // 🎫 SOPORTE Y PAGOS (Old components restored)
        $ultimosTickets = \App\Models\SupportTicket::with('empresa')->orderByDesc('created_at')->limit(5)->get();
        $ultimosPagos   = \App\Models\SuscripcionPago::with('empresa')->orderByDesc('created_at')->limit(5)->get();

        // 🔍 BITÁCORA GLOBAL DE ACTIVIDAD (Omnisciencia del Owner)
        $globalActivities = \App\Models\ActivityLog::with(['user', 'empresa'])
            ->latest()
            ->limit(10)
            ->get();

        return view('owner.dashboard', [
            'total_mrr'         => $mrrNum,
            'globalActivities'  => $globalActivities,
            'empresasCount'    => $empresasCount,
            'empresasActivas'  => $empresasActivas,
            'usuariosCount'    => $usuariosCount,
            'articulosCount'   => number_format($articulosCountValue, 0, ',', '.'),
            'clientesCount'    => number_format($clientesCountValue, 0, ',', '.'),
            'consumoStorage'    => ($consumoGB ?: '0.0') . ' GB',
            'costoStorage'      => '$' . number_format($costoStorage, 2),
            'consumoTrafico'    => ($consumoGB * 2.5 ?: '0.0') . ' GB',
            'costoTrafico'      => '$' . number_format($costoTrafico, 2),
            'landingVisits'     => $landingVisits,
            'demoEntries'       => $demoEntries,
            'conversionRate'    => $conversionRate,
            'archivosSubidos'   => number_format($imagenesCountValue + $videosCountValue),
            'imagenesSubidas'   => number_format($imagenesCountValue),
            'streamingMensual'  => ($videosCountValue * 1.5) . ' hs', 
            'facturacionMes'    => '$' . number_format($facturacionMesNum, 0, ',', '.'),
            'mrr'               => '$' . number_format($mrrNum, 0, ',', '.'),
            'dbSize'            => $dbSizeMB . ' MB',
            'costoProyectado'   => '$' . number_format($costoProyectadoTotal, 2),
            'saludVentas'       => $saludVentas ?: 1, 
            'saludGastos'       => $saludGastos ?: 0,
            'saludGlobal'       => 95, 
            'ultimasEmpresas'   => Empresa::with('plan')->orderByDesc('created_at')->limit(5)->get(),
            'settings'          => $settings,
            'crmActivities'     => $crmActivities,
            'nuevosLeads'       => $nuevosLeads,
            'ultimosTickets'    => $ultimosTickets,
            'ultimosPagos'      => $ultimosPagos,
            'agent_data'        => $agent_data
        ]);
    }

    /**
     * Actualizar configuraciones globales del SaaS
     */
    public function updateSettings(\Illuminate\Http\Request $request)
    {
        try {
            foreach ($request->all() as $key => $value) {
                if (in_array($key, ['afip_tutorial_video'])) {
                    \App\Models\SystemSetting::updateOrCreate(
                        ['key' => $key],
                        ['value' => $value]
                    );
                }
            }

            return redirect()->back()->with('success', 'Ajustes globales actualizados con éxito.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar ajustes: ' . $e->getMessage());
        }
    }
}
