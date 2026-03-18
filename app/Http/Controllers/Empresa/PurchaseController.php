<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\KardexMovimiento;

class PurchaseController extends Controller
{

    /* =========================================================
       UTILIDAD INTERNA — PARSE NUMÉRICO SEGURO
       Convierte formato B: 12,500.55 a float
    ========================================================= */
    private function parseNumero($valor)
    {
        if ($valor === null || $valor === '') {
            return 0;
        }

        $valor = str_replace(',', '', $valor);

        return (float) $valor;
    }


    /* =========================================================
       LISTADO DE COMPRAS + KPIs
    ========================================================= */
    public function index(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;

        $today  = Carbon::today('America/Argentina/Buenos_Aires');
        $week   = Carbon::now('America/Argentina/Buenos_Aires')->startOfWeek(Carbon::MONDAY);
        $month  = Carbon::now('America/Argentina/Buenos_Aires')->startOfMonth();

        $query = Purchase::where('empresa_id', $empresaId)
            ->whereNotNull('purchase_date')
            ->with('supplier');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', "%{$request->search}%")
                  ->orWhereDate('purchase_date', $request->search);
            });
        }

        if ($request->from) {
            $query->whereDate('purchase_date', '>=', $request->from);
        }

        if ($request->to) {
            $query->whereDate('purchase_date', '<=', $request->to);
        }

        if ($request->payment && $request->payment !== 'todos') {
            $query->where('payment_type', $request->payment);
        }

        $perPage = $request->per_page ?? 15;

        $purchases = $query
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        $kpiToday = Purchase::where('empresa_id', $empresaId)
            ->whereDate('purchase_date', $today)
            ->sum('total');

        $kpiWeek = Purchase::where('empresa_id', $empresaId)
            ->whereDate('purchase_date', '>=', $week)
            ->sum('total');

        $kpiMonth = Purchase::where('empresa_id', $empresaId)
            ->whereDate('purchase_date', '>=', $month)
            ->sum('total');

        $kpiCredito = Purchase::where('empresa_id', $empresaId)
            ->where('payment_type', 'credito')
            ->sum('total');

        $kpiContado = Purchase::where('empresa_id', $empresaId)
            ->where('payment_type', 'contado')
            ->sum('total');

        return view('empresa.purchases.index', compact(
            'purchases',
            'kpiToday',
            'kpiWeek',
            'kpiMonth',
            'kpiCredito',
            'kpiContado'
        ));
    }


    /* =========================================================
       FORMULARIO NUEVA COMPRA
    ========================================================= */
    public function create()
    {
        $empresaId = auth()->user()->empresa_id;

        $suppliers = Supplier::where('empresa_id', $empresaId)->get();
        $products  = Product::where('empresa_id', $empresaId)->with('variants')->get();

        return view('empresa.purchases.create', compact('suppliers','products'));
    }


    /* =========================================================
       GUARDAR COMPRA
       MOTOR PRINCIPAL DE INVENTARIO
    ========================================================= */
    public function store(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $userId    = auth()->id();

        if (empty($request->items)) {
            return back()->with('error', 'Debe agregar productos');
        }

        DB::beginTransaction();

        try {

            $total = 0;

            foreach ($request->items as $item) {

                $qty   = (float) ($item['quantity'] ?? 0);
                $final = $this->parseNumero($item['price_con_iva'] ?? 0);

                if ($qty > 0 && $final > 0) {
                    $total += $qty * $final;
                }
            }

            if ($total <= 0) {
                throw new \Exception("La compra no tiene valores válidos");
            }

            $tipoPago = $request->accion === 'guardar_pagar'
                ? 'contado'
                : 'credito';

            $purchase = Purchase::create([
                'empresa_id'     => $empresaId,
                'supplier_id'    => $request->supplier_id,
                'purchase_date'  => $request->purchase_date ?: Carbon::today(),
                'invoice_type'   => $request->invoice_type,
                'invoice_number' => $request->invoice_number,
                'total'          => $total,
                'payment_type'   => $tipoPago,
                'status'         => 'confirmado'
            ]);

            foreach ($request->items as $item) {

                $qty = (float) ($item['quantity'] ?? 0);
                if ($qty <= 0) continue;

                $base  = $this->parseNumero($item['price_sin_iva'] ?? 0);
                $iva   = (float) ($item['iva'] ?? 0);
                $final = $this->parseNumero($item['price_con_iva'] ?? 0);

                $productId = $item['product_id'] ?? null;
                $variantId = $item['variant_id'] ?? null;

                if (!$productId) {
                    throw new \Exception("Producto inválido en la compra");
                }

                PurchaseItem::create([
                    'empresa_id'  => $empresaId,
                    'purchase_id' => $purchase->id,
                    'product_id'  => $productId,
                    'variant_id'  => $variantId,
                    'quantity'    => $qty,
                    'cost'        => $base,
                    'iva'         => $iva,
                    'subtotal'    => $qty * $final
                ]);

                $product = Product::where('empresa_id',$empresaId)
                    ->where('id', $productId)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw new \Exception("Producto no encontrado ID: ".$productId);
                }

                /*
                |--------------------------------------------------------------------------
                | MOVIMIENTO DE STOCK (Invertido si es NC)
                |--------------------------------------------------------------------------
                */

                $esNC = ($request->invoice_type === 'NC' || $request->invoice_type === 'nota_credito');

                if ($variantId) {
                    $variant = \App\Models\ProductVariant::where('product_id', $productId)
                        ->where('id', $variantId)
                        ->lockForUpdate()
                        ->first();
                    
                    if ($variant) {
                        if ($esNC) {
                            $variant->descontarStock($qty, 'Nota de Crédito Proveedor #' . $purchase->id);
                        } else {
                            $variant->aumentarStock($qty, 'COMPRA #' . $purchase->id);
                        }
                        // Sincronizar stock padre
                        $product->stock = \App\Models\ProductVariant::where('product_id', $productId)->sum('stock');
                        $product->save();
                    }
                } else {
                    if ($esNC) {
                        $product->descontarStock($qty, 'Nota de Crédito Proveedor #' . $purchase->id);
                    } else {
                        $product->aumentarStock($qty, 'COMPRA #' . $purchase->id);
                    }
                }
            }

            // REGISTRAR EN CUENTA CORRIENTE DEL PROVEEDOR
            $supplier = Supplier::find($request->supplier_id);
            if ($supplier) {
                if ($esNC) {
                    // NC de proveedor -> Menos deuda para nosotros (Haber/Credit)
                    $supplier->registrarPago($total, "NC #{$purchase->invoice_number} - Devolución/Crédito");
                } else {
                    if ($tipoPago === 'credito') {
                        // Aumentar deuda
                        $supplier->registrarCompra($total, "Compra #{$purchase->id} - {$purchase->invoice_type} {$purchase->invoice_number}");
                    } else {
                        // Si es contado, registramos la compra (deuda) y el pago inmediatamente (haber)
                        $supplier->registrarCompra($total, "Compra #{$purchase->id} - Pago Contado");
                        $supplier->registrarPago($total, "Pago de Compra #{$purchase->id}");
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('empresa.compras.index')
                ->with('success','Compra registrada correctamente');

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }



    /* =========================================================
       BORRAR COMPRA
       REVERSA DE INVENTARIO
    ========================================================= */
    public function destroy($id)
    {
        $empresaId = auth()->user()->empresa_id;

        DB::beginTransaction();

        try {

            $purchase = Purchase::where('empresa_id', $empresaId)
                ->with('items')
                ->findOrFail($id);

            foreach ($purchase->items as $item) {

                $product = Product::where('empresa_id', $empresaId)
                    ->where('id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if ($product) {

                    $stockActual = $product->stock ?? 0;
                    $nuevoStock  = $stockActual - $item->quantity;

                    $product->update([
                        'stock' => $nuevoStock
                    ]);
                }

                KardexMovimiento::where('empresa_id', $empresaId)
                    ->where('product_id', $item->product_id)
                    ->where('origen', 'COMPRA #' . $purchase->id)
                    ->delete();
            }

            if (DB::getSchemaBuilder()->hasTable('pagos_proveedores')) {

                DB::table('pagos_proveedores')
                    ->where('purchase_id', $purchase->id)
                    ->delete();
            }

            $purchase->delete();

            DB::commit();

            return redirect()
                ->route('empresa.compras.index')
                ->with('success','Compra eliminada correctamente');

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }



    /* =========================================================
       VER DETALLE COMPRA
    ========================================================= */
    public function show($id)
    {
        $empresaId = auth()->user()->empresa_id;

        $purchase = Purchase::where('empresa_id', $empresaId)
            ->with(['supplier','items.product'])
            ->findOrFail($id);

        return view('empresa.purchases.show', compact('purchase'));
    }

    /* =========================================================
       EDITAR DATOS CABECERA 
    ========================================================= */
    public function edit($id)
    {
        $empresaId = auth()->user()->empresa_id;

        $purchase = Purchase::where('empresa_id', $empresaId)->findOrFail($id);
        $suppliers = Supplier::where('empresa_id', $empresaId)->get();

        return view('empresa.purchases.edit', compact('purchase','suppliers'));
    }

    public function update(Request $request, $id)
    {
        $empresaId = auth()->user()->empresa_id;
        $purchase  = Purchase::where('empresa_id', $empresaId)->findOrFail($id);

        $request->validate([
            'supplier_id' => 'required',
            'purchase_date' => 'required|date',
        ]);

        // Actualizamos los datos básicos (según pedido del usuario)
        $purchase->update([
            'supplier_id'    => $request->supplier_id,
            'purchase_date'  => $request->purchase_date,
            'invoice_type'   => $request->invoice_type,
            'invoice_number' => $request->invoice_number,
        ]);

        return redirect()
            ->route('empresa.compras.index')
            ->with('success', 'Comprobante actualizado correctamente. Los totales y stock permanecen inalterados.');
    }

    /* =========================================================
       API — OBTENER ÚLTIMO PRECIO DE COMPRA
       Para contraste en Nueva Compra
    ========================================================= */
    public function getLastPrice($productId, $variantId = null)
    {
        $empresaId = auth()->user()->empresa_id;

        $lastItem = PurchaseItem::where('empresa_id', $empresaId)
            ->where('product_id', $productId);

        if ($variantId && $variantId !== 'null') {
            $lastItem->where('variant_id', $variantId);
        } else {
            $lastItem->whereNull('variant_id');
        }

        $lastItem = $lastItem->orderByDesc('id')->first();

        return response()->json([
            'cost' => $lastItem ? (float) $lastItem->cost : 0
        ]);
    }
}
