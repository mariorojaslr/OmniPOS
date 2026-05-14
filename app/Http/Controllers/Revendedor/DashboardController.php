<?php

namespace App\Http\Controllers\Revendedor;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;
use App\Models\SuscripcionPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Clientes asignados a este revendedor
        $empresas = Empresa::where('reseller_id', $user->id)->with('plan')->get();
        
        $totalEmpresas = $empresas->count();
        $activas = $empresas->where('activo', true)->count();
        
        // Cálculo de MRR (Suscripciones de sus clientes)
        $mrr = 0;
        foreach($empresas as $emp) {
            if($emp->activo) {
                $mrr += $emp->custom_price ?? ($emp->plan->price ?? 0);
            }
        }

        // Supongamos una comisión del 10% para el revendedor
        $comisionEstimada = $mrr * 0.10;

        // Últimos pagos de sus clientes
        $ultimosPagos = SuscripcionPago::whereIn('empresa_id', $empresas->pluck('id'))
            ->with('empresa')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('revendedor.dashboard', [
            'totalEmpresas' => $totalEmpresas,
            'activas' => $activas,
            'mrr' => $mrr,
            'comisionEstimada' => $comisionEstimada,
            'empresas' => $empresas,
            'ultimosPagos' => $ultimosPagos,
        ]);
    }

    public function editConfig(Empresa $empresa)
    {
        // Seguridad: solo el revendedor de esta empresa puede configurarla
        if ($empresa->reseller_id !== Auth::id()) {
            abort(403, 'No tienes permiso para configurar esta empresa.');
        }

        $config = $empresa->config;

        return view('revendedor.empresas.config', compact('empresa', 'config'));
    }

    public function updateConfig(Request $request, Empresa $empresa)
    {
        if ($empresa->reseller_id !== Auth::id()) {
            abort(403);
        }

        $config = $empresa->config;
        
        $request->validate([
            'mod_afiliados' => 'boolean',
            'mod_hce' => 'boolean',
            'mod_turnos' => 'boolean',
            'mod_afip' => 'boolean',
            'mod_pagos' => 'boolean',
            'mod_backups' => 'boolean',
        ]);

        // Actualizamos los toggles
        $config->update([
            'mod_ventas' => $request->has('mod_ventas'),
            'mod_tesoreria' => $request->has('mod_tesoreria'),
            'mod_logistica' => $request->has('mod_logistica'),
            'mod_compras' => $request->has('mod_compras'),
            'mod_afiliados' => $request->has('mod_afiliados'),
            'mod_hce' => $request->has('mod_hce'),
            'mod_turnos' => $request->has('mod_turnos'),
            'mod_afip' => $request->has('mod_afip'),
            'mod_pagos' => $request->has('mod_pagos'),
            'mod_backups' => $request->has('mod_backups'),
            'mod_unidades_medida' => $request->has('mod_unidades_medida'),
        ]);

        return redirect()->route('revendedor.dashboard')->with('success', "Configuración de {$empresa->nombre_comercial} actualizada.");
    }
}
