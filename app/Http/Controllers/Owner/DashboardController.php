<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;

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
        
        $consumoGB = round(($imagenesCountValue * 0.5) / 1024, 2);
        $costoStorage = $consumoGB * 0.01; // $0.01 por GB
        $costoTrafico = ($consumoGB * 2.5) * 0.01; // $0.01 por GB

        // 📉 SALUD DEL SISTEMA (Basado en KPI vs Target mensual sugerido)
        $saludVentas = min(round(($facturacionMesNum / 1000000) * 100), 100);
        $gastosGlobal = \App\Models\Expense::whereMonth('created_at', now()->month)->sum('amount');
        $saludGastos = $facturacionMesNum > 0 ? round(($gastosGlobal / $facturacionMesNum) * 100) : 0;

        // 🛰️ TRÁFICO Y ADQUISICIÓN (Métricas en Tiempo Real de la BD)
        $today = now()->toDateString();
        $traffic = \App\Models\OwnerSystemTraffic::firstOrCreate(['date' => $today]);

        $landingVisits = $traffic->landing_visits;
        $demoEntries = $traffic->demo_clicks;
        $botReferrals = $traffic->bot_referrals;
        
        $conversionRate = $landingVisits > 0 ? round(($demoEntries / $landingVisits) * 100, 1) : 0;

        return view('owner.dashboard', [
            'empresasCount'    => $empresasCount,
            'empresasActivas'  => $empresasActivas,
            'usuariosCount'    => $usuariosCount,
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
            'saludVentas'       => $saludVentas ?: 1, 
            'saludGastos'       => $saludGastos ?: 0,
            'saludGlobal'       => 95, 
            'ultimasEmpresas'   => Empresa::with('plan')->orderByDesc('created_at')->limit(5)->get(),
        ]);
    }
}
