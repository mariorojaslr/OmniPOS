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
       Formato B: 12,500.55
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
       LISTADO + KPIs
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
        $products  = Product::where('empresa_id', $empresaId)->get();

        return view('empresa.purchases.create', compact('suppliers','products'));
    }


    /* =========================================================
       GUARDAR COMPRA
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

                PurchaseItem::create([
                    'empresa_id'  => $empresaId,
                    'purchase_id' => $purchase->id,
                    'product_id'  => $item['product_id'],
                    'quantity'    => $qty,
                    'cost'        => $base,
                    'iva'         => $iva,
                    'subtotal'    => $qty * $final
                ]);

                $product = Product::where('empresa_id',$empresaId)
                    ->where('id', $item['product_id'])
                    ->lockForUpdate()
                    ->first();

                if ($product) {

                    $stockDespues = $product->stock_actual + $qty;

                    $product->update([
                        'stock_actual' => $stockDespues
                    ]);

                    KardexMovimiento::create([
                        'empresa_id'        => $empresaId,
                        'product_id'        => $product->id,
                        'user_id'           => $userId,
                        'tipo'              => 'entrada',
                        'cantidad'          => $qty,
                        'stock_resultante'  => $stockDespues,
                        'origen'            => 'COMPRA #' . $purchase->id
                    ]);
                }
            }

            if ($tipoPago === 'contado'
                && DB::getSchemaBuilder()->hasTable('pagos_proveedores')) {

                DB::table('pagos_proveedores')
                    ->where('purchase_id', $purchase->id)
                    ->delete();

                DB::table('pagos_proveedores')->insert([
                    'empresa_id'  => $empresaId,
                    'purchase_id' => $purchase->id,
                    'monto'       => $total,
                    'forma_pago'  => 'efectivo',
                    'created_at'  => now(),
                    'updated_at'  => now()
                ]);
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
       BORRAR COMPRA (BORRADO REAL)
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
                    $nuevoStock = $product->stock_actual - $item->quantity;

                    $product->update([
                        'stock_actual' => $nuevoStock
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

            // 🔥 BORRADO REAL
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
       VER DETALLE
    ========================================================= */
    public function show($id)
    {
        $empresaId = auth()->user()->empresa_id;

        $purchase = Purchase::where('empresa_id', $empresaId)
            ->with(['supplier','items.product'])
            ->findOrFail($id);

        return view('empresa.purchases.show', compact('purchase'));
    }
}
