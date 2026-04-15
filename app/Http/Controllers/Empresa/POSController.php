<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

use App\Models\Product;
use App\Models\ProductVariant;
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

                return Product::paraVenta()
                    ->with('images')
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

                'has_variants' => (bool) $p->has_variants,
                'variants'     => $p->variants,
                'img'          => $p->images->first()
                    ? $p->images->first()->url
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


        $posMode = true;
        $empresa = $user->empresa;
        $logo    = $empresa->config->logo_url ?? asset('images/logo-placeholder.png');

        return view('empresa.pos.index', compact(
            'productsData',
            'clientesData',
            'posMode',
            'empresa',
            'logo'
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
            $tipoComprobante  = $request->input('tipo_comprobante', 'ticket');
            $hacerRemito      = $request->boolean('hacer_remito', false);
            $itemsEntregar    = $request->input('items_entregar'); // Array de {id, variant_id, quantity_delivery}


            $metodoPago       = $request->input('metodo_pago', 'efectivo');

            /*
            |--------------------------------------------------------------------------
            | REGISTRAR VENTA
            |--------------------------------------------------------------------------
            */

            $venta = $ventaService->registrarVenta(
                $user,
                $items,
                $clienteId,
                $tipoVentaCliente,
                $tipoComprobante,
                $hacerRemito,
                $itemsEntregar,
                $metodoPago
            );


            return response()->json([
                'ok'               => true,
                'venta_id'         => $venta->id,
                'remito_id'        => $venta->remito_principal?->id, // Devolver ID si se creó remito
                'total'            => $venta->total_con_iva,
                'clienteId'        => $clienteId,
                'tipo_comprobante' => $venta->tipo_comprobante,
                'es_afip'          => !empty($venta->cae),
                'cae'              => $venta->cae
            ]);

        } catch (\Throwable $e) {

            Log::error('Error POS STORE', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile()
            ]);

            return response()->json([
                'ok'    => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | BUSCAR POR CÓDIGO DE BARRAS
    |--------------------------------------------------------------------------
    */

    public function buscarPorBarcode(Request $request)
    {
        $barcode   = trim($request->input('barcode', ''));
        $empresaId = Auth::user()->empresa_id;

        if (empty($barcode)) {
            return response()->json(['ok' => false, 'error' => 'Código vacío']);
        }

        // 1️⃣ Buscar en variantes primero (solo productos vendibles)
        $variant = ProductVariant::whereHas('product', fn($q) => $q->where('empresa_id', $empresaId)->paraVenta())
            ->where(function($query) use ($barcode) {
                $query->where('barcode', $barcode)
                      ->orWhere('sku', $barcode);
            })
            ->with('product.images')
            ->first();

        if ($variant) {
            $p = $variant->product;
            return response()->json([
                'ok'         => true,
                'type'       => 'variant',
                'product_id' => $p->id,
                'variant_id' => $variant->id,
                'name'       => "{$p->name} ({$variant->size} {$variant->color})",
                'price'      => (float)($variant->price ?? $p->price),
                'stock'      => (float)$p->stock,
                'img'        => $p->images->first()?->url ?? asset('images/no-image.png'),
            ]);
        }

        // 2️⃣ Buscar en producto principal (solo productos vendibles)
        $product = Product::paraVenta()
            ->where('empresa_id', $empresaId)
            ->where(function($query) use ($barcode) {
                $query->where('barcode', $barcode)
                      ->orWhere('sku', $barcode);
            })
            ->with('images')
            ->first();

        if ($product) {
            return response()->json([
                'ok'         => true,
                'type'       => 'product',
                'product_id' => $product->id,
                'variant_id' => null,
                'name'       => $product->name,
                'price'      => (float)$product->price,
                'stock'      => (float)$product->stock,
                'img'        => $product->images->first()?->url ?? asset('images/no-image.png'),
            ]);
        }

        return response()->json(['ok' => false, 'error' => 'Producto no encontrado']);
    }
}
