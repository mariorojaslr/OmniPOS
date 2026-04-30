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
        try {
            $empresa_id = auth()->user()->empresa_id;

            // Agrupamos ventas por cliente y obtenemos sus coordenadas
            // Usamos withoutGlobalScope para evitar la ambigüedad de 'empresa_id' al hacer el JOIN
            $data = Venta::withoutGlobalScope('empresa')
                ->where('ventas.empresa_id', $empresa_id)
                ->join('clients', 'ventas.client_id', '=', 'clients.id')
                ->whereNotNull('clients.lat')
                ->whereNotNull('clients.lng')
                ->select('clients.lat', 'clients.lng', DB::raw('SUM(ventas.total_con_iva) as total'))
                ->groupBy('clients.lat', 'clients.lng')
                ->get();

            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error("GPS ERROR (Heatmap): " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
        try {
            $empresa_id = auth()->user()->empresa_id;

            // Usamos withoutGlobalScope para evitar ambigüedad en el join si fuera necesario, 
            // aunque aquí no hay JOIN, lo hacemos por consistencia y seguridad.
            $suppliers = Supplier::withoutGlobalScope('empresa')
                ->where('suppliers.empresa_id', $empresa_id)
                ->whereNotNull('lat')
                ->whereNotNull('lng')
                ->select('id', 'name', 'lat', 'lng', 'direccion', 'plus_code')
                ->get();

            return response()->json($suppliers);
        } catch (\Exception $e) {
            \Log::error("GPS ERROR (Suppliers): " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtiene pedidos que están listos para ser entregados (armados)
     */
    public function getPendingDeliveries()
    {
        // DATOS DE PRUEBA: SINTONÍA FINA EN LA RIOJA CAPITAL
        $orders = collect([
            [
                'id' => 101,
                'client_name' => 'Kiosco El Profe (Plaza 25)',
                'lat' => -29.4131,
                'lng' => -66.8558,
                'address' => 'San Nicolás de Bari (O) 450',
                'total' => 1500.50,
                'items_count' => 3,
                'items_list' => '2x Gaseosa, 1x Galletas',
                'type' => 'pedido'
            ],
            [
                'id' => 102,
                'client_name' => 'Almacén de la Esquina (Av. Quiroga)',
                'lat' => -29.4180,
                'lng' => -66.8620,
                'address' => 'Av. Facundo Quiroga 1200',
                'total' => 3200.00,
                'items_count' => 5,
                'items_list' => '5x Pan, 1x Leche',
                'type' => 'pedido'
            ],
            [
                'id' => 103,
                'client_name' => 'Maxi Kiosco 24hs (Centro)',
                'lat' => -29.4110,
                'lng' => -66.8520,
                'address' => 'Rivadavia 300',
                'total' => 850.25,
                'items_count' => 2,
                'items_list' => '2x Alfajor',
                'type' => 'pedido'
            ],
            [
                'id' => 104,
                'client_name' => 'Parada de Prueba (Parque de la Ciudad)',
                'lat' => -29.4350,
                'lng' => -66.8850,
                'address' => 'Circunvalación Sur s/n',
                'total' => 5400.00,
                'items_count' => 10,
                'items_list' => '10x Bebidas Varias',
                'type' => 'pedido'
            ]
        ]);

        return response()->json($orders);
    }
}
