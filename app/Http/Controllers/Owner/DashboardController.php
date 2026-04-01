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
        
        // 📉 SALUD DEL SISTEMA (Basado en KPI vs Target mensual sugerido)
        $saludVentas = min(round(($facturacionMesNum / 1000000) * 100), 100);
        $gastosGlobal = \App\Models\Expense::whereMonth('created_at', now()->month)->sum('amount');
        $saludGastos = $facturacionMesNum > 0 ? round(($gastosGlobal / $facturacionMesNum) * 100) : 0;

        return view('owner.dashboard', [
            'empresasCount'    => $empresasCount,
            'empresasActivas'  => $empresasActivas,
            'usuariosCount'    => $usuariosCount,
            'consumoStorage'    => ($consumoGB ?: '0.0') . ' GB',
            'consumoTrafico'    => ($consumoGB * 2.5 ?: '0.0') . ' GB',
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
