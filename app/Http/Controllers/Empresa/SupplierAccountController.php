<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierLedger;
use App\Models\OrdenPago;
use App\Models\Cheque;
use App\Services\SupplierAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierAccountController extends Controller
{
    protected $accountService;
    protected $tesoreriaService;

    public function __construct(SupplierAccountService $accountService, \App\Services\TesoreriaService $tesoreriaService)
    {
        $this->accountService = $accountService;
        $this->tesoreriaService = $tesoreriaService;
    }

    /**
     * Muestra el resumen de cuenta corriente de proveedor
     */
    public function show(Request $request, Supplier $supplier)
    {
        $empresaId = Auth::user()->empresa_id;

        if ($supplier->empresa_id !== $empresaId) {
            abort(403);
        }

        $perPage = $request->get('perPage', 25);
        $q = SupplierLedger::where('supplier_id', $supplier->id)
            ->where('empresa_id', $empresaId)
            ->when($request->desde, fn($query) => $query->whereDate('created_at', '>=', $request->desde))
            ->when($request->hasta, fn($query) => $query->whereDate('created_at', '<=', $request->hasta))
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');

        $movimientos = $q->paginate($perPage)->withQueryString();

        // Saldo Actual
        $saldo = $supplier->saldo;

        // Obtener compras/débitos pendientes de pago
        $deudas = SupplierLedger::where('supplier_id', $supplier->id)
            ->where('empresa_id', $empresaId)
            ->where('type', 'debit')
            ->where('paid', false)
            ->where('pending_amount', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        // ── KPIs de Aging (Antigüedad de Deuda) ──
        $hoy = now();
        $aging = [
            '0_30'     => 0, // 0 a 30 días de antigüedad
            '31_60'    => 0, // 31 a 60 días
            '61_plus'  => 0, // 61+ días
        ];
        foreach ($deudas as $d) {
            $dias = $d->created_at->diffInDays($hoy);
            if ($dias > 60) {
                $aging['61_plus'] += $d->pending_amount;
            } elseif ($dias > 30) {
                $aging['31_60'] += $d->pending_amount;
            } else {
                $aging['0_30'] += $d->pending_amount;
            }
        }

        // Créditos disponibles (pagos no totalmente imputados)
        $creditosAFavorDisponible = SupplierLedger::where('supplier_id', $supplier->id)
            ->where('empresa_id', $empresaId)
            ->where('type', 'credit')
            ->where('paid', false)
            ->where('pending_amount', '>', 0)
            ->sum('pending_amount');

        // Últimas ordenes de pago
        $ultimasOP = OrdenPago::where('supplier_id', $supplier->id)
            ->where('empresa_id', $empresaId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Totales históricos
        $totalCompras = SupplierLedger::where('supplier_id', $supplier->id)
            ->where('empresa_id', $empresaId)
            ->where('type', 'debit')
            ->sum('amount');

        $totalPagado = SupplierLedger::where('supplier_id', $supplier->id)
            ->where('empresa_id', $empresaId)
            ->where('type', 'credit')
            ->sum('amount');

        // Cheques en cartera (para pagar como cheques de terceros)
        $cheques = Cheque::where('empresa_id', $empresaId)
            ->where('estado', 'en_cartera')
            ->where('tipo', 'tercero')
            ->get();

        // Chequeras propias activas
        $chequeras = \App\Models\Chequera::where('empresa_id', $empresaId)
            ->where('activo', true)
            ->get();

        // Cuentas de tesorería para pagos
        $cuentas = \App\Models\FinanzaCuenta::where('empresa_id', $empresaId)->where('activo', true)->get();
            
        return view('empresa.proveedores.cta_cte', compact(
            'supplier', 'movimientos', 'saldo', 'deudas', 'aging', 
            'totalCompras', 'totalPagado', 'ultimasOP', 'creditosAFavorDisponible',
            'cheques', 'chequeras', 'cuentas'
        ));
    }

    /**
     * Registra una nueva Orden de Pago
     */
    public function storePayment(Request $request, Supplier $supplier)
    {
        $request->validate([
            'monto'               => 'required|numeric|min:0',
            'fecha'               => 'required|date',
            'pagos_diferenciados' => 'required|array|min:1',
        ]);

        try {
            $ordenPago = $this->accountService->registrarPagoProveedor(
                $supplier->id,
                $request->monto,
                $request->fecha,
                $request->input('pagos_diferenciados', []),
                $request->input('compras', []), // Selección de débitos a imputar
                true // Auto-imputar lo que sobre
            );

            return back()->with('success', "Orden de Pago #{$ordenPago->numero_orden} registrada con éxito.");
        } catch (\Exception $e) {
            return back()->with('error', "Error al registrar el pago: " . $e->getMessage());
        }
    }
    
    /**
     * Listado global de cheques (Cartera de Cheques)
     */
    public function chequesIndex(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        
        $q = Cheque::where('empresa_id', $empresaId)
            ->with(['client', 'supplier', 'chequera'])
            ->when($request->tipo, fn($query) => $query->where('tipo', $request->tipo))
            ->when($request->estado, fn($query) => $query->where('estado', $request->estado))
            ->when($request->desde, fn($query) => $query->whereDate('fecha_pago', '>=', $request->desde))
            ->when($request->hasta, fn($query) => $query->whereDate('fecha_pago', '<=', $request->hasta))
            ->orderBy('fecha_pago', 'asc');

        $cheques = $q->paginate(30)->withQueryString();
        
        // Sumatorias para KPIs
        $stats = [
            'en_cartera_monto' => Cheque::where('empresa_id', $empresaId)->where('estado', 'en_cartera')->sum('monto'),
            'en_cartera_count' => Cheque::where('empresa_id', $empresaId)->where('estado', 'en_cartera')->count(),
            'propios_emitidos' => Cheque::where('empresa_id', $empresaId)->where('tipo', 'propio')->where('estado', 'entregado')->sum('monto'),
            'proximos_vencer'  => Cheque::where('empresa_id', $empresaId)->where('estado', 'en_cartera')->whereDate('fecha_pago', '<=', now()->addDays(7))->sum('monto'),
        ];

        // Cuentas para conciliación
        $cuentas = \App\Models\FinanzaCuenta::where('empresa_id', $empresaId)->where('activo', true)->get();
            
        return view('empresa.tesoreria.cheques.index', compact('cheques', 'stats', 'cuentas'));
    }

    /**
     * Cambiar estado de un cheque (Depositar, Cobrar, Rechazar)
     */
    public function updateChequeStatus(Request $request, Cheque $cheque)
    {
        if ($cheque->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }

        $request->validate([
            'estado' => 'required|in:en_cartera,depositado,entregado,rechazado,anulado,cobrado',
            'cuenta_id' => 'nullable|exists:finanzas_cuentas,id',
        ]);

        $nuevoEstado = $request->estado;
        $viejoEstado = $cheque->estado;

        // Evitar duplicar movimientos si ya estaba cobrado
        if ($nuevoEstado == 'cobrado' && $viejoEstado != 'cobrado' && $request->cuenta_id) {
            if ($cheque->tipo == 'tercero') {
                // Ingreso de dinero (Lo cobré o se acreditó)
                $this->tesoreriaService->registrarMovimiento($request->cuenta_id, 'ingreso', $cheque->monto, "Cobro cheque tercero #{$cheque->numero}", [
                    'reference_type' => get_class($cheque),
                    'reference_id'   => $cheque->id,
                    'categoria'      => 'Cheques de Terceros',
                    'conciliado'     => true
                ]);
            } else {
                // Egreso de dinero (El banco me lo descontó porque el proveedor lo cobró)
                $this->tesoreriaService->registrarMovimiento($request->cuenta_id, 'egreso', $cheque->monto, "Débito cheque propio #{$cheque->numero}", [
                    'reference_type' => get_class($cheque),
                    'reference_id'   => $cheque->id,
                    'categoria'      => 'Cheques Propios',
                    'conciliado'     => true
                ]);
            }
            $cheque->cuenta_id = $request->cuenta_id;
        }

        // ── Lógica de RECHAZADO ──
        if ($nuevoEstado == 'rechazado' && $viejoEstado != 'rechazado') {
            if ($cheque->tipo == 'tercero') {
                // 1. Reabrir deuda del cliente (Generar Debito en Cta Cte)
                \App\Models\ClientLedger::create([
                    'empresa_id'     => $cheque->empresa_id,
                    'client_id'      => $cheque->client_id,
                    'type'           => 'debit',
                    'amount'         => $cheque->monto,
                    'description'    => "RECHAZO DE CHEQUE #{$cheque->numero} ({$cheque->banco})",
                    'reference_type' => get_class($cheque),
                    'reference_id'   => $cheque->id,
                ]);

                // 2. Si ya estaba en una cuenta (cobrado/depositado), sacar la plata de ahí
                if ($cheque->cuenta_id) {
                    $this->tesoreriaService->registrarMovimiento($cheque->cuenta_id, 'egreso', $cheque->monto, "Débito por cheque rechazado #{$cheque->numero}", [
                        'reference_type' => get_class($cheque),
                        'reference_id'   => $cheque->id,
                        'categoria'      => 'Rechazos',
                        'conciliado'     => true
                    ]);
                }
            }
        }

        $cheque->update(['estado' => $nuevoEstado]);

        return back()->with('success', "Estado del cheque #{$cheque->numero} actualizado a " . ucfirst(str_replace('_', ' ', $nuevoEstado)));
    }
}
