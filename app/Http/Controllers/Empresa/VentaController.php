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
