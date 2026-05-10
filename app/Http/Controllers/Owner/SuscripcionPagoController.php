<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuscripcionPago;
use App\Models\Empresa;
use Carbon\Carbon;

class SuscripcionPagoController extends Controller
{
    public function index()
    {
        $empresas = Empresa::with(['plan', 'pagos' => function($q) {
            $q->orderByDesc('fecha_pago');
        }])->get();
        
        foreach($empresas as $empresa) {
            if ($empresa->fecha_vencimiento) {
                $vencimiento = Carbon::parse($empresa->fecha_vencimiento);
                $diff = now()->diff($vencimiento);
                
                // Días totales para lógica de badges (entero)
                $empresa->dias_para_vencer = (int) now()->diffInDays($vencimiento, false);
                
                // Texto amigable
                $parts = [];
                if ($diff->days > 0) $parts[] = $diff->days . " día" . ($diff->days > 1 ? "s" : "");
                if ($diff->h > 0) $parts[] = $diff->h . " hora" . ($diff->h > 1 ? "s" : "");
                
                $empresa->vencimiento_human = count($parts) > 0 ? implode(' y ', $parts) : 'menos de 1 hora';
            } else {
                $empresa->dias_para_vencer = -999;
                $empresa->vencimiento_human = 'Sin fecha';
            }
            
            $empresa->monto_total_pagado = $empresa->pagos->sum('monto');
        }

        return view('owner.facturacion.index', compact('empresas'));
    }

    public function create()
    {
        $empresas = Empresa::with('plan')->get();
        return view('owner.facturacion.create', compact('empresas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'monto' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'metodo' => 'required|string',
            'estado' => 'required|string',
            'nro_comprobante' => 'nullable|string',
            'notas' => 'nullable|string',
        ]);

        $empresa = Empresa::findOrFail($request->empresa_id);

        $pago = SuscripcionPago::create([
            'empresa_id'      => $empresa->id,
            'plan_id'         => $request->plan_id ?: $empresa->plan_id, // Tomamos el plan que se está pagando
            'monto'           => $request->monto,
            'fecha_pago'      => $request->fecha_pago,
            'metodo'          => $request->metodo,
            'estado'          => $request->estado,
            'nro_comprobante' => $request->nro_comprobante,
            'notas'           => $request->notas,
        ]);

        // Si el pago es aprobado, actualizar la fecha de vencimiento y estatus de la empresa.
        if ($request->estado === 'aprobado') {
            // El estatus debe ser exactamente 'activa' para consistencia en el sistema
            $empresa->update([
                'status'            => 'activa',
                'activo'            => true,
                'fecha_vencimiento' => now()->addDays(30),
                'ultima_fecha_pago' => $request->fecha_pago,
            ]);
        }

        return redirect()->route('owner.facturacion.index')->with('success', 'Pago registrado correctamente.');
    }
}
