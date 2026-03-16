<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Services\VentaService;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    /**
     * 📋 Listado de ventas con KPIs y filtros
     */
    public function index(Request $request)
    {
        $empresa = auth()->user()->empresa;

        $q = Venta::where('empresa_id', $empresa->id)
            ->with(['cliente', 'user', 'items.product', 'items.variant'])
            ->orderByDesc('created_at');

        // Filtros
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($query) use ($s) {
                $query->where('numero_comprobante', 'like', "%$s%")
                      ->orWhereHas('cliente', fn($c) => $c->where('name', 'like', "%$s%"));
            });
        }
        if ($request->filled('from')) {
            $q->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $q->whereDate('created_at', '<=', $request->to);
        }
        if ($request->filled('tipo')) {
            $q->where('tipo_comprobante', $request->tipo);
        }
        if ($request->filled('metodo')) {
            $q->where('metodo_pago', $request->metodo);
        }

        $ventas = $q->paginate($request->input('per_page', 15));

        // KPIs
        $base = Venta::where('empresa_id', $empresa->id);
        $kpiHoy    = (clone $base)->whereDate('created_at', today())->sum('total_con_iva');
        $kpiSemana = (clone $base)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_con_iva');
        $kpiMes    = (clone $base)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total_con_iva');
        $totalVentas = (clone $base)->count();

        return view('empresa.ventas.index', compact('ventas', 'kpiHoy', 'kpiSemana', 'kpiMes', 'totalVentas'));
    }

    /**
     * 💾 Guarda una venta real desde el POS
     */
    public function store(Request $request, VentaService $ventaService)
    {
        // ... (Este método ya existe o se actualizó vía POSController)
    }

    /**
     * 📑 Generar PDF de la venta
     */
    public function pdf(Venta $venta)
    {
        $empresa = auth()->user()->empresa;

        if ($venta->empresa_id !== $empresa->id) {
            abort(403);
        }

        $venta->load([
            'items.product',
            'items.variant',
            'user',
            'cliente'
        ]);

        $empresa->load('config');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.comprobante_venta', compact('venta', 'empresa'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("Comprobante_{$venta->numero_comprobante}.pdf");
    }
}
