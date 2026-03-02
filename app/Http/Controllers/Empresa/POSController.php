<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Client;
use App\Services\VentaService;

class POSController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PANTALLA PRINCIPAL POS
    |--------------------------------------------------------------------------
    | • Carga productos activos
    | • Carga clientes activos
    | • Devuelve datos listos para JS
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $user = Auth::user();
        $empresaId = $user->empresa_id;

        /*
        |--------------------------------------------------------------------------
        | PRODUCTOS ACTIVOS
        |--------------------------------------------------------------------------
        */
        $products = Product::with('images')
            ->where('empresa_id', $empresaId)
            ->where(function ($q) {
                $q->where('active', 1)
                  ->orWhereNull('active');
            })
            ->orderBy('name')
            ->get();

        $productsData = $products->map(function ($p) {

            $imgPath = optional($p->images->first())->path;

            return [
                'id'    => $p->id,
                'name'  => $p->name,
                'price' => (float) $p->price,
                'stock' => (float) $p->stock, // agregado informativo (no afecta lógica)
                'img'   => $imgPath
                    ? asset('storage/' . $imgPath)
                    : asset('images/no-image.png'),
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | CLIENTES ACTIVOS
        |--------------------------------------------------------------------------
        | Se cargan completos para búsqueda avanzada
        |--------------------------------------------------------------------------
        */
        $clientes = Client::where('empresa_id', $empresaId)
            ->where('active', 1)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'phone',
                'document',       // actualmente usado como CUIT
                'tax_condition',
                'email',
                'credit_limit'
            ]);

        $clientesData = $clientes->map(function ($c) {

            return [
                'id'            => $c->id,
                'name'          => $c->name ?? '',
                'phone'         => $c->phone ?? '',
                'document'      => $c->document ?? '', // CUIT real
                'tax_condition' => $c->tax_condition ?? '',
                'email'         => $c->email ?? '',
                'credit_limit'  => $c->credit_limit ?? 0,
                'saldo'         => method_exists($c, 'saldo') ? $c->saldo() : 0
            ];
        });

        return view('empresa.pos.index', compact('productsData', 'clientesData'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE - GUARDAR VENTA DESDE POS
    |--------------------------------------------------------------------------
    | • Valida items
    | • Convierte formato POS → VentaService
    | • Permite cliente opcional
    | • Permite contado o cuenta corriente
    |--------------------------------------------------------------------------
    */
    public function store(Request $request, VentaService $ventaService)
    {
        try {

            $user = Auth::user();

            /*
            |--------------------------------------------------------------------------
            | VALIDACIÓN BÁSICA
            |--------------------------------------------------------------------------
            */
            $itemsPOS = $request->items ?? [];

            if (!is_array($itemsPOS) || empty($itemsPOS)) {
                return response()->json([
                    'ok'    => false,
                    'error' => 'No hay productos en la venta'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | CONVERTIR FORMATO POS → VentaService
            |--------------------------------------------------------------------------
            */
            $items = [];

            foreach ($itemsPOS as $item) {

                if (!isset($item['product_id'], $item['cantidad'])) {
                    continue;
                }

                $items[] = [
                    'id'       => (int) $item['product_id'],
                    'quantity' => (float) $item['cantidad'],
                ];
            }

            if (empty($items)) {
                return response()->json([
                    'ok'    => false,
                    'error' => 'Items inválidos'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | DATOS CLIENTE
            |--------------------------------------------------------------------------
            */
            $clienteId        = $request->cliente_id ?? null;
            $tipoVentaCliente = $request->tipo_venta_cliente ?? 'contado';

            /*
            |--------------------------------------------------------------------------
            | REGISTRAR VENTA
            |--------------------------------------------------------------------------
            */
            $venta = $ventaService->registrarVenta(
                $user,
                $items,
                $clienteId,
                $tipoVentaCliente
            );

            return response()->json([
                'ok'        => true,
                'venta_id'  => $venta->id,
                'total'     => $venta->total_con_iva,
                'clienteId' => $clienteId
            ]);

        } catch (\Throwable $e) {

            Log::error('Error POS STORE', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile()
            ]);

            return response()->json([
                'ok'    => false,
                'error' => 'Error interno al guardar venta'
            ]);
        }
    }
}
