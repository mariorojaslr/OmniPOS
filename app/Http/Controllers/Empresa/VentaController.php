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
    public function pdf(Venta $venta, Request $request)
    {
        $empresa = auth()->user()->empresa;

        if ($venta->empresa_id !== $empresa->id) {
            abort(403);
        }

        $venta->load(['items.product', 'items.variant', 'user', 'cliente']);
        $empresa->load('config');

        // Determinar formato (A4 o Ticket 80mm)
        $formato = $request->get('format', 'a4');
        
        // Si el tipo de comprobante es ticket, forzamos formato ticket a menos que pidan A4
        if ($venta->tipo_comprobante === 'ticket' && !$request->has('format')) {
            $formato = 'ticket';
        }

        // Lógica de Logo en Base64 para evitar errores de renderizado en servidor
        $logoBase64 = null;
        $logoPath   = public_path('images/logo_multipos.png');

        if ($empresa->config && $empresa->config->logo_url) {
            // Intentar cargar logo personalizado si existe
            $logoPath = public_path('storage/' . $empresa->config->logo_url);
        }

        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        if ($formato === 'ticket') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.ticket_80mm', compact('venta', 'empresa', 'logoBase64'))
                ->setPaper([0, 0, 226.77, 600], 'portrait'); // 226.77pt = 80mm
        } else {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.comprobante_venta', compact('venta', 'empresa', 'logoBase64'))
                ->setPaper('a4', 'portrait');
        }

        $filename = ($venta->numero_comprobante ?: 'Venta_'.$venta->id) . '.pdf';
        return $pdf->stream($filename);
    }
}
