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
        try {
            $today = now()->toDateString();
            
            // Métricas de Facturación (Global)
            $facturacionMesNum = \App\Models\Venta::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_con_iva');

            $gastosGlobal = \App\Models\Expense::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount');

            // Cálculo de MRR (Ingreso Mensual Recurrente)
            $mrrNum = Empresa::where('activo', true)
                ->join('plans', 'empresas.plan_id', '=', 'plans.id')
                ->sum('plans.price');

            // Métricas de Infraestructura
            $imagenesCountValue = \App\Models\ProductImage::count();
            $videosCountValue   = \App\Models\ProductVideo::count();
            
            // Peso de la DB (en MB) - Blindaje para Producción/Hosting
            try {
                $dbName = config('database.connections.mysql.database');
                $dbSizeQueryResult = \DB::select('SELECT SUM(data_length + index_length) / 1024 / 1024 AS size FROM information_schema.TABLES WHERE table_schema = ?', [$dbName]);
                $dbSizeMB = round($dbSizeQueryResult[0]->size ?? 0, 2);
            } catch (\Throwable $e) {
                $dbSizeMB = 0; 
            }

            $consumoGB = round(($imagenesCountValue * 0.5) / 1024, 2); // Estimado 0.5MB por foto
            $costoStorage = $consumoGB * 0.01; // $0.01 USD por GB (Bunny.net aprox)

            // KPIs de Salud
            $saludVentas = min(round(($facturacionMesNum / 1000000) * 100), 100);
            $saludGastos = $facturacionMesNum > 0 ? round(($gastosGlobal / $facturacionMesNum) * 100) : 0;
            $saludServer = 98; // Placeholder para monitoreo real

            // Tráfico del Sistema (Desactivado temporalmente por falta de columna en DB)
            // $traffic = \App\Models\OwnerSystemTraffic::firstOrCreate(['date' => $today]);
            // $traffic->increment('hits');

            // CRM Quick View
            $leadsCountValue     = User::where('status', 'prospecto')->count();
            $clientesCountValue  = \App\Models\Client::count();
            $nuevosLeads         = User::where('status', 'prospecto')->where('role', 'empresa')->latest()->limit(5)->get();

            // Configuración del Sistema
            $settings = \App\Models\SystemSetting::pluck('value', 'key')->toArray();
            $ownerEmail = $settings['owner_email'] ?? 'admin@multipos.com';
            $ownerWp    = $settings['owner_whatsapp'] ?? '5493510000000';

            // Actividad Reciente
            $channels = ['facebook', 'instagram', 'whatsapp', 'recomendado', 'publicidad'];
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

            $ultimosTickets = SupportTicket::with('empresa')->orderByDesc('created_at')->limit(5)->get();
            $ultimosPagos   = SuscripcionPago::with('empresa')->orderByDesc('created_at')->limit(5)->get();

            $globalActivities = \App\Models\ActivityLog::with(['user', 'empresa'])
                ->latest()
                ->limit(10)
                ->get();

            $data = [
                'facturacionMes'    => number_format($facturacionMesNum, 0, ',', '.'),
                'gastosGlobal'      => number_format($gastosGlobal, 0, ',', '.'),
                'mrr'               => number_format($mrrNum, 0, ',', '.'),
                'dbSize'            => $dbSizeMB . ' MB',
                'consumoGB'         => $consumoGB . ' GB',
                'costoStorage'      => '$' . number_format($costoStorage, 2),
                'leadsCount'        => number_format($leadsCountValue, 0, ',', '.'),
                'clientesCount'     => number_format($clientesCountValue, 0, ',', '.'),
                'nuevosLeads'       => $nuevosLeads,
                'archivosSubidos'   => number_format($imagenesCountValue + $videosCountValue),
                'imagenesSubidas'   => number_format($imagenesCountValue),
                'streamingMensual'  => ($videosCountValue * 1.5) . ' hs', 
                'saludVentas'       => $saludVentas ?: 1, 
                'saludGastos'       => $saludGastos ?: 1,
                'saludServer'       => $saludServer,
                'saludGlobal'       => 95,
                'ultimasEmpresas'   => Empresa::with('plan')->orderByDesc('created_at')->limit(5)->get(),
                'ultimosTickets'    => $ultimosTickets,
                'ultimosPagos'      => $ultimosPagos,
                'globalActivities'  => $globalActivities,
                'ownerEmail'        => $ownerEmail,
                'ownerWp'           => $ownerWp,
                'crmActivities'     => $agent_data,
                'settings'          => $settings,
            ];

            return view('owner.dashboard', $data);

        } catch (\Throwable $e) {
            return "❌ ERROR DETECTADO EN DASHBOARD: " . $e->getMessage() . " en el archivo " . $e->getFile() . " línea " . $e->getLine();
        }
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
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Error al actualizar ajustes: ' . $e->getMessage());
        }
    }
}
