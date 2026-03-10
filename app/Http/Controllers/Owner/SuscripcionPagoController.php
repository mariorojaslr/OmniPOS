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
        $pagos = SuscripcionPago::with(['empresa', 'plan'])->orderByDesc('fecha_pago')->get();
        return view('owner.facturacion.index', compact('pagos'));
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
            'empresa_id' => $empresa->id,
            'plan_id' => $empresa->plan_id,
            'monto' => $request->monto,
            'fecha_pago' => $request->fecha_pago,
            'metodo' => $request->metodo,
            'estado' => $request->estado,
            'nro_comprobante' => $request->nro_comprobante,
            'notas' => $request->notas,
        ]);

        // Si el pago es aprobado, actualizar la fecha de vencimiento de la empresa.
        if ($request->estado === 'aprobado' && $pago->monto > 0) {
            $fechaActual = $empresa->fecha_vencimiento && Carbon::parse($empresa->fecha_vencimiento)->isFuture() 
                ? Carbon::parse($empresa->fecha_vencimiento) 
                : now();
                
            $empresa->update([
                'fecha_vencimiento' => $fechaActual->addDays(30),
                'status' => 'activa',
                'ultima_fecha_pago' => $request->fecha_pago,
            ]);
        }

        return redirect()->route('owner.facturacion.index')->with('success', 'Pago registrado correctamente.');
    }
}
