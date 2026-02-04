<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Services\VentaService;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    /**
     * 📄 Listado de ventas
     */
    public function index()
    {
        $empresa = auth()->user()->empresa;

        $ventas = Venta::where('empresa_id', $empresa->id)
            ->orderByDesc('created_at')
            ->get();

        return view('empresa.ventas.index', compact('ventas'));
    }

    /**
     * 💾 Guarda una venta real desde el POS
     */
    public function store(Request $request, VentaService $ventaService)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.id'         => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        $venta = $ventaService->registrarVenta(
            auth()->user(),
            $request->items
        );

        return response()->json([
            'ok'       => true,
            'venta_id' => $venta->id,
        ]);
    }
}
