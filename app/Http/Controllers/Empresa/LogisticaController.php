<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\VentaItem;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LogisticaController extends Controller
{
    /**
     * 📊 Tablero Global de Compromisos (Stock en Guarda)
     */
    public function index()
    {
        $empresaId = Auth::user()->empresa_id;

        // Agrupar ítems de venta con saldo pendiente por producto
        $registros = VentaItem::whereHas('venta', function($q) use ($empresaId) {
                $q->where('empresa_id', $empresaId);
            })
            ->with(['product', 'variant'])
            ->select(
                'product_id', 
                'variant_id',
                DB::raw('SUM(cantidad) as total_vendido'),
                DB::raw('SUM(cantidad_entregada) as total_entregado'),
                DB::raw('SUM(cantidad - cantidad_entregada) as compromiso_total')
            )
            ->groupBy('product_id', 'variant_id')
            ->having('compromiso_total', '>', 0)
            ->get();

        // Top de Clientes con mercadería en guarda
        $clientesDeudores = Client::where('empresa_id', $empresaId)
            ->whereHas('ventas.items', function($q) {
                $q->whereRaw('cantidad > cantidad_entregada');
            })
            ->with(['ventas.items' => function($q) {
                $q->whereRaw('cantidad > cantidad_entregada');
            }])
            ->get()
            ->map(function($cliente) {
                $cliente->saldo_guarda = $cliente->ventas->flatMap->items->sum(fn($i) => $i->cantidad - $i->cantidad_entregada);
                return $cliente;
            })
            ->sortByDesc('saldo_guarda')
            ->take(10);

        return view('empresa.logistica.reporte', compact('registros', 'clientesDeudores'));
    }
}

