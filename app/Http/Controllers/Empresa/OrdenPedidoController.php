<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OrdenPedido;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OrdenPedidoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        $ordenes = $empresa->ordenesPedido()
            ->with('proveedor', 'user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total'      => $empresa->ordenesPedido()->count(),
            'borradores' => $empresa->ordenesPedido()->where('estado', 'borrador')->count(),
            'enviados'   => $empresa->ordenesPedido()->where('estado', 'enviado')->count(),
            'convertidos' => $empresa->ordenesPedido()->where('estado', 'convertido')->count(),
        ];

        return view('empresa.ordenes_pedido.index', compact('empresa', 'ordenes', 'stats'));
    }

    public function create()
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        $proveedores = $empresa->suppliers()->where('active', true)->orderBy('name')->get();
        $productos = $empresa->products()->where('active', true)->orderBy('name')->get();

        $config = $empresa->config;
        return view('empresa.ordenes_pedido.create', compact('empresa', 'proveedores', 'productos', 'config'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:suppliers,id',
            'fecha'        => 'required|date',
            'items'        => 'required|array|min:1',
            'items.*.descripcion' => 'required|string',
            'items.*.qty'         => 'required|numeric|min:0.01',
            'items.*.price'       => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $empresa = $user->empresa;

        // Generar número correlativo OP-00000001
        $lastOrder = $empresa->ordenesPedido()->orderBy('id', 'desc')->first();
        $ultimoNro = 0;
        if ($lastOrder && preg_match('/OP-(\d+)/', $lastOrder->numero, $matches)) {
            $ultimoNro = intval($matches[1]);
        }
        $numero = 'OP-' . str_pad($ultimoNro + 1, 8, '0', STR_PAD_LEFT);

        try {
            \DB::beginTransaction();

            $orden = $empresa->ordenesPedido()->create([
                'user_id'         => $user->id,
                'proveedor_id'    => $request->proveedor_id,
                'numero'          => $numero,
                'fecha'           => $request->fecha,
                'total'           => $request->total_final,
                'notas_generales' => $request->notas_generales,
                'estado'          => 'borrador',
                'token'           => Str::random(40)
            ]);

            foreach ($request->items as $item) {
                $productId = $item['product_id'] ?? null;
                $isManual = false;

                // Si es un producto manual y el usuario quiere guardarlo
                if (empty($productId) && !empty($item['save_as_product'])) {
                    $newProduct = $empresa->products()->create([
                        'name'        => $item['descripcion'],
                        'price'       => 0, // Precio de venta inicial en 0
                        'is_sellable' => false, // Privado / Uso interno
                        'usage_type'  => 'consume',
                        'active'      => true
                    ]);
                    $productId = $newProduct->id;
                }

                if (empty($productId)) {
                    $isManual = true;
                }

                $orden->items()->create([
                    'product_id'      => $productId,
                    'variant_id'      => $item['variant_id'] ?? null,
                    'descripcion'     => $item['descripcion'],
                    'cantidad'        => $item['qty'],
                    'precio_unitario' => $item['price'],
                    'precio_anterior' => $item['precio_anterior'] ?? null,
                    'instrucciones'   => $item['instrucciones'] ?? null,
                    'subtotal'        => $item['qty'] * $item['price'],
                    'is_manual'       => $isManual
                ]);
            }

            \DB::commit();

            \App\Models\ActivityLog::log("Creó la Orden de Pedido {$numero} para " . $orden->proveedor->name, $orden);

            return redirect()->route('empresa.ordenes-pedido.index')->with('success', "Orden de Pedido {$numero} generada con éxito.");

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Error al generar la orden: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $empresa = $user->empresa;
        $orden = $empresa->ordenesPedido()->with('proveedor', 'items.product', 'user')->findOrFail($id);

        return view('empresa.ordenes_pedido.show', compact('empresa', 'orden'));
    }

    public function convertToPurchase($id)
    {
        $user = Auth::user();
        $empresa = $user->empresa;
        $orden = $empresa->ordenesPedido()->with('items')->findOrFail($id);

        if ($orden->estado === 'convertido') {
            return back()->with('error', 'Esta orden ya fue convertida a compra.');
        }

        // Pre-cargar datos para el creador de compras
        session([
            'prefill_compra' => [
                'orden_pedido_id' => $orden->id,
                'supplier_id'    => $orden->proveedor_id,
                'items'           => $orden->items->map(function($i) {
                    return [
                        'product_id'    => $i->product_id,
                        'descripcion'   => $i->descripcion,
                        'qty'           => (float)$i->cantidad,
                        'price_con_iva' => (float)$i->precio_unitario,
                        'instrucciones' => $i->instrucciones
                    ];
                })->toArray(),
            ]
        ]);

        return redirect()->route('empresa.compras.create')
            ->with('info', "Iniciando conversión de Orden {$orden->numero}. Los datos han sido precargados.");
    }
    
    /**
     * API para obtener el último precio de compra de un producto con un proveedor específico
     */
    public function getLastPrice(Request $request)
    {
        $user = Auth::user();
        $empresa = $user->empresa;
        $productId = $request->product_id;
        $proveedorId = $request->proveedor_id;

        if (!$productId || !$proveedorId) return response()->json(['price' => 0]);

        // Buscar en compras reales primero
        $lastPurchaseItem = \DB::table('purchase_items')
            ->join('purchases', 'purchases.id', '=', 'purchase_items.purchase_id')
            ->where('purchases.empresa_id', $empresa->id)
            ->where('purchases.supplier_id', $proveedorId)
            ->where('purchase_items.product_id', $productId)
            ->orderBy('purchases.purchase_date', 'desc')
            ->select('purchase_items.cost as precio_unitario')
            ->first();

        if ($lastPurchaseItem) {
            return response()->json(['price' => (float)$lastPurchaseItem->precio_unitario]);
        }

        // Si no hay compras, buscar en órdenes de pedido aceptadas/convertidas
        $lastOrderItem = \DB::table('orden_pedido_items')
            ->join('ordenes_pedido', 'ordenes_pedido.id', '=', 'orden_pedido_items.orden_pedido_id')
            ->where('ordenes_pedido.empresa_id', $empresa->id)
            ->where('ordenes_pedido.proveedor_id', $proveedorId)
            ->where('orden_pedido_items.product_id', $productId)
            ->orderBy('ordenes_pedido.fecha', 'desc')
            ->select('orden_pedido_items.precio_unitario')
            ->first();

        return response()->json(['price' => $lastOrderItem ? (float)$lastOrderItem->precio_unitario : 0]);
    }
}
