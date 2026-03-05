<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

use App\Models\Product;
use App\Models\Client;
use App\Services\VentaService;

class POSController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | PANTALLA PRINCIPAL POS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = Auth::user();
        $empresaId = $user->empresa_id;

        /*
        |--------------------------------------------------------------------------
        | PRODUCTOS CON CACHE
        |--------------------------------------------------------------------------
        */

        $products = Cache::remember(
            'pos_products_'.$empresaId,
            60,
            function () use ($empresaId) {

                return Product::with('images')
                    ->where('empresa_id', $empresaId)
                    ->where(function ($q) {
                        $q->where('active', 1)
                          ->orWhereNull('active');
                    })
                    ->orderBy('name')
                    ->get();

            }
        );

        /*
        |--------------------------------------------------------------------------
        | FORMATEAR PRODUCTOS PARA JS
        |--------------------------------------------------------------------------
        */

        $productsData = $products->map(function ($p) {

            $imgPath = optional($p->images->first())->path;

            return [
                'id'    => $p->id,
                'name'  => $p->name,
                'price' => (float) $p->price,

                'stock' => (float) $p->stock,

                'img'   => $imgPath
                    ? asset('storage/'.$imgPath)
                    : asset('images/no-image.png'),
            ];
        });


        /*
        |--------------------------------------------------------------------------
        | CLIENTES
        |--------------------------------------------------------------------------
        */

        $clientes = Client::where('empresa_id', $empresaId)
            ->where('active', 1)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'phone',
                'document',
                'tax_condition',
                'email',
                'credit_limit'
            ]);


        /*
        |--------------------------------------------------------------------------
        | FORMATEAR CLIENTES PARA POS
        |--------------------------------------------------------------------------
        */

        $clientesData = $clientes->map(function ($c) {

            return [
                'id'            => $c->id,
                'name'          => $c->name ?? '',
                'phone'         => $c->phone ?? '',
                'document'      => $c->document ?? '',
                'tax_condition' => $c->tax_condition ?? '',
                'email'         => $c->email ?? '',
                'credit_limit'  => $c->credit_limit ?? 0,

                'saldo' => method_exists($c, 'saldo')
                    ? $c->saldo()
                    : 0
            ];
        });


        return view('empresa.pos.index', compact(
            'productsData',
            'clientesData'
        ));
    }



    /*
    |--------------------------------------------------------------------------
    | STORE — REGISTRAR VENTA
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, VentaService $ventaService)
    {
        try {

            $user = Auth::user();

            /*
            |--------------------------------------------------------------------------
            | LEER ITEMS DESDE JSON (CORRECCIÓN)
            |--------------------------------------------------------------------------
            */

            $itemsPOS = $request->input('items', []);

            if (!is_array($itemsPOS) || empty($itemsPOS)) {

                return response()->json([
                    'ok'    => false,
                    'error' => 'No hay productos en la venta'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | NORMALIZAR ITEMS
            |--------------------------------------------------------------------------
            */

            $items = [];

            foreach ($itemsPOS as $item) {

                if (!isset($item['product_id'], $item['cantidad'])) {
                    continue;
                }

                $productId = (int) $item['product_id'];
                $cantidad  = (float) $item['cantidad'];

                if ($productId <= 0 || $cantidad <= 0) {
                    continue;
                }

                $items[] = [
                    'id'       => $productId,
                    'quantity' => $cantidad
                ];
            }

            if (empty($items)) {

                return response()->json([
                    'ok'    => false,
                    'error' => 'Items inválidos'
                ]);
            }


            $clienteId        = $request->input('cliente_id');
            $tipoVentaCliente = $request->input('tipo_venta_cliente', 'contado');


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
