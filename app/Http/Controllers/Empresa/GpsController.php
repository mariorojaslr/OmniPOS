<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Supplier;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;

class GpsController extends Controller
{
    public function index()
    {
        return view('empresa.gps.index');
    }

    public function rutas()
    {
        return view('empresa.gps.rutas');
    }

    /**
     * Busca clientes o proveedores para agregar a la ruta
     */
    public function searchEntities(Request $request)
    {
        $q = $request->get('q');
        $empresa_id = auth()->user()->empresa_id;

        $clients = Client::where('empresa_id', $empresa_id)
            ->where(function($query) use ($q) {
                $query->where('name', 'LIKE', "%$q%")
                      ->orWhere('plus_code', 'LIKE', "%$q%");
            })
            ->select('id', 'name', 'lat', 'lng', 'plus_code', DB::raw("'cliente' as type"))
            ->limit(5)
            ->get();

        $suppliers = Supplier::where('empresa_id', $empresa_id)
            ->where(function($query) use ($q) {
                $query->where('name', 'LIKE', "%$q%")
                      ->orWhere('plus_code', 'LIKE', "%$q%");
            })
            ->select('id', 'name', 'lat', 'lng', 'plus_code', DB::raw("'proveedor' as type"))
            ->limit(5)
            ->get();

        return response()->json($clients->concat($suppliers));
    }

    /**
     * Vista de Zonas Calientes (Heatmap)
     */
    public function zonasCalientes()
    {
        return view('empresa.gps.zonas_calientes');
    }

    /**
     * Datos para el Heatmap de Ventas
     */
    public function getHeatmapData()
    {
        $empresa_id = auth()->user()->empresa_id;

        // Agrupamos ventas por cliente y obtenemos sus coordenadas
        $data = Venta::where('ventas.empresa_id', $empresa_id)
            ->join('clients', 'ventas.client_id', '=', 'clients.id')
            ->whereNotNull('clients.lat')
            ->whereNotNull('clients.lng')
            ->select('clients.lat', 'clients.lng', DB::raw('SUM(ventas.total_con_iva) as total'))
            ->groupBy('clients.lat', 'clients.lng')
            ->get();

        return response()->json($data);
    }

    /**
     * Vista de Retiros Inteligentes
     */
    public function retirosInteligentes()
    {
        return view('empresa.gps.retiros_inteligentes');
    }

    /**
     * Obtiene ubicaciones de proveedores para el mapa de retiros
     */
    public function getSupplierLocations()
    {
        $empresa_id = auth()->user()->empresa_id;

        $suppliers = Supplier::where('empresa_id', $empresa_id)
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->select('id', 'name', 'lat', 'lng', 'direccion', 'plus_code')
            ->get();

        return response()->json($suppliers);
    }

    /**
     * Obtiene pedidos que están listos para ser entregados (armados)
     */
    public function getPendingDeliveries()
    {
        $empresa_id = auth()->user()->empresa_id;
        \Log::debug("GPS DEBUG: Buscando pedidos para Empresa ID: " . $empresa_id);

        $orders = \App\Models\Order::where('empresa_id', $empresa_id)
            ->whereIn('estado', ['pedido_armado', 'pendiente', 'en_proceso'])
            ->with(['client', 'items'])
            ->get();
        
        \Log::debug("GPS DEBUG: Pedidos encontrados en DB: " . $orders->count());

        $orders = $orders->filter(function($order) {
                return $order->client && $order->client->lat && $order->client->lng;
            })
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'client_name' => $order->nombre_cliente ?: $order->client->name,
                    'lat' => (float)$order->client->lat,
                    'lng' => (float)$order->client->lng,
                    'address' => $order->direccion ?: $order->client->address,
                    'total' => (float)$order->total,
                    'items_count' => $order->items->count(),
                    'items_list' => $order->items->count() > 0 
                        ? $order->items->map(fn($i) => $i->cantidad . 'x ' . ($i->product->name ?? $i->nombre_producto))->implode(', ')
                        : 'Pedido de Catálogo',
                    'type' => 'pedido'
                ];
            });

        return response()->json($orders->values());
    }
}
