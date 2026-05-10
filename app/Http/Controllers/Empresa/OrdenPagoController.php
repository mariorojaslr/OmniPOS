<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\OrdenPago;
use App\Models\Cheque;
use App\Models\FinanzaCuenta;
use App\Models\Chequera;
use App\Services\SupplierAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdenPagoController extends Controller
{
    protected $accountService;

    public function __construct(SupplierAccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Listado de todos los recibos (Órdenes de Pago)
     */
    public function index(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;

        $q = OrdenPago::where('empresa_id', $empresaId)
            ->with(['supplier', 'pagos', 'user'])
            ->when($request->desde, fn($query) => $query->whereDate('created_at', '>=', $request->desde))
            ->when($request->hasta, fn($query) => $query->whereDate('created_at', '<=', $request->hasta))
            ->orderBy('created_at', 'desc');

        $recibos = $q->paginate(25)->withQueryString();

        return view('empresa.proveedores.recibos.index', compact('recibos'));
    }

    /**
     * Pantalla para generar un nuevo recibo
     */
    public function create(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;

        // Si viene un supplier_id pre-seleccionado
        $selectedSupplier = null;
        if ($request->supplier_id) {
            $selectedSupplier = Supplier::find($request->supplier_id);
        }

        $suppliers = Supplier::where('empresa_id', $empresaId)->orderBy('name')->get();
        $cheques = Cheque::where('empresa_id', $empresaId)->where('estado', 'en_cartera')->where('tipo', 'tercero')->get();
        $chequeras = Chequera::where('empresa_id', $empresaId)->where('activo', true)->get();
        $cuentas = FinanzaCuenta::where('empresa_id', $empresaId)->where('activo', true)->get();

        return view('empresa.proveedores.recibos.create', compact('suppliers', 'selectedSupplier', 'cheques', 'chequeras', 'cuentas'));
    }

    /**
     * Guarda el recibo (mismo servicio que antes)
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'         => 'required|exists:suppliers,id',
            'monto'               => 'required|numeric|min:0',
            'fecha'               => 'required|date',
            'pagos_diferenciados' => 'required|array|min:1',
        ]);

        try {
            $ordenPago = $this->accountService->registrarPagoProveedor(
                $request->supplier_id,
                $request->monto,
                $request->fecha,
                $request->input('pagos_diferenciados', []),
                $request->input('compras', []), 
                true 
            );

            return redirect()->route('empresa.recibos-proveedores.index')
                ->with('success', "Recibo #{$ordenPago->numero_orden} generado con éxito.");
        } catch (\Exception $e) {
            return back()->with('error', "Error al generar el recibo: " . $e->getMessage())->withInput();
        }
    }

    /**
     * API: Retorna las deudas pendientes de un proveedor en JSON
     */
    public function getDeudaApi(Supplier $supplier)
    {
        if ($supplier->empresa_id !== Auth::user()->empresa_id) {
            return response()->json([], 403);
        }

        $deudas = \App\Models\SupplierLedger::where('supplier_id', $supplier->id)
            ->where('empresa_id', Auth::user()->empresa_id)
            ->where('type', 'debit')
            ->where('paid', false)
            ->where('pending_amount', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        $data = $deudas->map(function($d) {
            return [
                'id'             => $d->id,
                'description'    => $d->description,
                'amount'         => $d->amount,
                'pending_amount' => $d->pending_amount,
                'date'           => $d->created_at->format('d/m/Y')
            ];
        });

        return response()->json($data);
    }
}
