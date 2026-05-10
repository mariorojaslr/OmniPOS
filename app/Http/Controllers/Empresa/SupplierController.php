<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\SupplierLedger;

class SupplierController extends Controller
{

    /**
     * =========================================================
     * LISTADO DE PROVEEDORES
     * =========================================================
     */
    public function index(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;

        $perPage = $request->get('perPage', 15);
        $q       = $request->get('q');

        $query = Supplier::where('empresa_id', $empresaId);

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%")
                    ->orWhere('document', 'like', "%$q%");
            });
        }

        $suppliers = $query
            ->orderBy('name')
            ->paginate($perPage)
            ->appends($request->query());

        return view('empresa.proveedores.index', compact('suppliers'));
    }


    /**
     * =========================================================
     * FORM CREAR
     * =========================================================
     */
    public function create()
    {
        return view('empresa.proveedores.create');
    }


    /**
     * =========================================================
     * GUARDAR PROVEEDOR
     * =========================================================
     */
    public function store(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;

        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'document' => 'nullable|string|max:20',
        ]);

        $supplier = Supplier::create([
            'empresa_id' => $empresaId,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'document' => $request->document,
            'direccion' => $request->direccion ?? null,
            'lat' => $request->lat ?? null,
            'lng' => $request->lng ?? null,
            'plus_code' => $request->plus_code ?? null,
            'active' => 1
        ]);

        \App\Models\ActivityLog::log("Creó el proveedor: {$supplier->name}");

        return redirect()
            ->route('empresa.proveedores.index')
            ->with('success', 'Proveedor creado correctamente.');
    }


    /**
     * =========================================================
     * CUENTA CORRIENTE PROVEEDOR (MOTOR SQL REAL)
     * =========================================================
     */
    public function show(Request $request, string $id)
    {
        $empresaId = auth()->user()->empresa_id;

        $supplier = Supplier::where('empresa_id', $empresaId)
            ->findOrFail($id);

        $perPage = $request->get('perPage', 25);
        $fechaCorte = $request->get('corte');

        /**
         * ================================
         * SALDO REAL SQL
         * ================================
         */
        $saldoQuery = SupplierLedger::where('empresa_id', $empresaId)
            ->where('supplier_id', $supplier->id);

        if ($fechaCorte) {
            $saldoQuery->whereDate('created_at', '<=', $fechaCorte);
        }

        $saldo = $saldoQuery
            ->selectRaw("
                COALESCE(SUM(CASE WHEN type='debit' THEN amount END),0)
              - COALESCE(SUM(CASE WHEN type='credit' THEN amount END),0)
              AS saldo
            ")
            ->value('saldo');

        /**
         * ================================
         * MOVIMIENTOS
         * ================================
         */
        $queryBase = SupplierLedger::where('empresa_id', $empresaId)
            ->where('supplier_id', $supplier->id)
            ->when($request->tipo, fn($q) => $q->where('type', $request->tipo))
            ->when($request->desde, fn($q) => $q->whereDate('created_at', '>=', $request->desde))
            ->when($request->hasta, fn($q) => $q->whereDate('created_at', '<=', $request->hasta));

        if ($fechaCorte) {
            $queryBase->whereDate('created_at', '<=', $fechaCorte);
        }

        $movimientos = $queryBase
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        /**
         * ================================
         * SALDO ACUMULADO
         * ================================
         */
        if ($movimientos->count()) {

            $ultimoMovimiento = $movimientos->last();

            $saldoInicial = SupplierLedger::where('empresa_id', $empresaId)
                ->where('supplier_id', $supplier->id)
                ->where(function ($q) use ($ultimoMovimiento) {
                    $q->where('created_at', '<', $ultimoMovimiento->created_at)
                      ->orWhere(function ($q2) use ($ultimoMovimiento) {
                          $q2->where('created_at', $ultimoMovimiento->created_at)
                             ->where('id', '<', $ultimoMovimiento->id);
                      });
                })
                ->selectRaw("
                    COALESCE(SUM(CASE WHEN type='debit' THEN amount END),0)
                  - COALESCE(SUM(CASE WHEN type='credit' THEN amount END),0)
                  AS saldo
                ")
                ->value('saldo');

            $saldoTemp = $saldoInicial;

            foreach ($movimientos->reverse() as $m) {
                $saldoTemp += ($m->type === 'debit') ? $m->amount : -$m->amount;

                $m->saldo_acumulado = $saldoTemp;
                $m->debe  = $m->type === 'debit' ? $m->amount : null;
                $m->haber = $m->type === 'credit' ? $m->amount : null;
            }

            $movimientos->setCollection($movimientos->reverse());
        }

        /**
         * ================================
         * SALDO VENCIDO (30 días)
         * ================================
         */
        $saldoVencido = SupplierLedger::where('empresa_id', $empresaId)
            ->where('supplier_id', $supplier->id)
            ->where('type', 'debit')
            ->where('paid', false)
            ->whereDate('created_at', '<', now()->subDays(30))
            ->sum('amount');

        return view('empresa.proveedores.show', compact(
            'supplier',
            'movimientos',
            'saldo',
            'saldoVencido',
            'fechaCorte'
        ));
    }


    /**
     * =========================================================
     * EDITAR
     * =========================================================
     */
    public function edit(string $id)
    {
        $supplier = Supplier::where('empresa_id', auth()->user()->empresa_id)
            ->findOrFail($id);

        return view('empresa.proveedores.edit', compact('supplier'));
    }


    /**
     * =========================================================
     * ACTUALIZAR
     * =========================================================
     */
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::where('empresa_id', auth()->user()->empresa_id)
            ->findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $supplier->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'document' => $request->document,
            'direccion' => $request->direccion,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'plus_code' => $request->plus_code,
            'active' => $request->active ?? 1,
        ]);

        \App\Models\ActivityLog::log("Actualizó el proveedor: {$supplier->name}");

        return redirect()
            ->route('empresa.proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }


    /**
     * =========================================================
     * ELIMINAR
     * =========================================================
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::where('empresa_id', auth()->user()->empresa_id)
            ->findOrFail($id);

        $nombreProv = $supplier->name;
        $supplier->delete();

        \App\Models\ActivityLog::log("Eliminó el proveedor: {$nombreProv}");

        return redirect()
            ->route('empresa.proveedores.index')
            ->with('success', 'Proveedor eliminado correctamente.');
    }

    /* =========================================================
       REGISTRAR PAGO MANUAL (CUENTA CORRIENTE)
    ========================================================= */
    public function recordPayment(Request $request, $id)
    {
        $empresaId = auth()->user()->empresa_id;
        $supplier = Supplier::where('empresa_id', $empresaId)->findOrFail($id);

        $request->validate([
            'amount'      => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
        ]);

        $supplier->registrarPago($request->amount, $request->description);

        return back()->with('success', 'Pago registrado correctamente en la cuenta corriente.');
    }

    public function getPortalLink(\App\Models\Supplier $supplier)
    {
        try {
            $token = \App\Models\SupplierPortalToken::where('supplier_id', $supplier->id)->first();

            if (!$token) {
                $token = \App\Models\SupplierPortalToken::create([
                    'empresa_id' => $supplier->empresa_id,
                    'supplier_id' => $supplier->id,
                    'token' => \Illuminate\Support\Str::random(40),
                ]);
            }

            return response()->json([
                'url' => route('supplier.portal', ['token' => $token->token])
            ]);
        } catch (\Exception $e) {
            \Log::error("Error generando link de portal para proveedor {$supplier->id}: " . $e->getMessage());
            return response()->json(['error' => 'No se pudo generar el enlace'], 500);
        }
    }

    /**
     * Listado de proveedores para gestión de portales
     */
    public function portalList(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $q = $request->get('q');

        $suppliers = \App\Models\Supplier::where('empresa_id', $empresaId)
            ->when($q, function($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                      ->orWhere('cuit', 'like', "%$q%");
            })
            ->with('portalToken')
            ->orderBy('name')
            ->paginate(50);

        return view('empresa.proveedores.portal_list', compact('suppliers'));
    }
}
