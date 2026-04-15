<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientLedger;
use App\Models\Recibo;
use App\Services\ClientAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientAccountController extends Controller
{
    protected $accountService;

    public function __construct(ClientAccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Muestra el resumen de cuenta corriente mejorado (Mirror de Proveedores)
     */
    public function show(Request $request, Client $client)
    {
        $empresaId = Auth::user()->empresa_id;

        if ($client->empresa_id !== $empresaId) {
            abort(403);
        }

        // Si hay una migración pendiente (data healing), inicializamos pending_amount
        $this->healLedgerData($client);

        $perPage = $request->get('perPage', 25);
        
        // Movimientos
        $q = ClientLedger::where('client_id', $client->id)
            ->where('empresa_id', $empresaId)
            ->when($request->desde, fn($query) => $query->whereDate('created_at', '>=', $request->desde))
            ->when($request->hasta, fn($query) => $query->whereDate('created_at', '<=', $request->hasta))
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');

        $movimientos = $q->paginate($perPage)->withQueryString();

        // ── KPIs DE RESUMEN ──
        $resumenGlobal = $this->accountService->getAccountSummary($client->id);
        $saldo = $resumenGlobal['saldo_global'];
        
        $totalVentas = ClientLedger::where('client_id', $client->id)->where('type', 'debit')->sum('amount');
        $totalCobrado = ClientLedger::where('client_id', $client->id)->where('type', 'credit')->sum('amount');

        // Deudas impagas
        $deudas = ClientLedger::where('client_id', $client->id)
            ->where('type', 'debit')
            ->where('paid', false)
            ->where('pending_amount', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        // Saldo a favor (Créditos sin imputar)
        $recibosHuerfanos = ClientLedger::where('client_id', $client->id)
            ->where('type', 'credit')
            ->where('paid', false)
            ->where('pending_amount', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        $saldoAFavorDisponible = $recibosHuerfanos->sum('pending_amount');

        // ── ANÁLISIS DE AGING (ANTIGÜEDAD) ──
        $aging = [
            '0_30'  => 0,
            '31_60' => 0,
            '61_plus' => 0
        ];

        foreach ($deudas as $d) {
            $dias = $d->created_at->diffInDays(now());
            if ($dias <= 30) {
                $aging['0_30'] += (float) $d->pending_amount;
            } elseif ($dias <= 60) {
                $aging['31_60'] += (float) $d->pending_amount;
            } else {
                $aging['61_plus'] += (float) $d->pending_amount;
            }
        }

        // Ultimos recibos
        $ultimosRecibos = Recibo::where('client_id', $client->id)
            ->orderBy('fecha', 'desc')
            ->limit(5)
            ->get();

        return view('empresa.clientes.cta_cte', compact(
            'client', 'movimientos', 'saldo', 'deudas', 'saldoAFavorDisponible',
            'aging', 'totalVentas', 'totalCobrado', 'ultimosRecibos', 'recibosHuerfanos'
        ));
    }

    /**
     * Registra un nuevo Recibo de Cobro
     */
    public function storeReceipt(Request $request, Client $client)
    {
        $request->validate([
            'monto'               => 'required|numeric|min:0',
            'metodo_pago'         => 'required|string',
            'fecha'               => 'required|date',
            'referencia'          => 'nullable|string|max:255',
            'pagos_diferenciados' => 'nullable|array',
        ]);

        try {
            $recibo = $this->accountService->registrarCobro(
                $client->id,
                $request->monto,
                $request->metodo_pago,
                $request->referencia,
                $request->fecha,
                $request->input('facturas', []), // Selección al motor contable
                $request->input('pagos_diferenciados', []) // Líneas de pagos por separado
            );

            return back()->with('success', "Recibo #{$recibo->numero_recibo} registrado con éxito.");
        } catch (\Exception $e) {
            return back()->with('error', "Error al registrar pago: " . $e->getMessage());
        }
    }

    /**
     * Aplica el saldo a favor existente a las facturas seleccionadas
     */
    public function aplicarSaldoAFavor(Request $request, Client $client)
    {
        $request->validate([
            'facturas_aplicar' => 'required|array|min:1',
            'facturas_aplicar.*' => 'exists:client_ledgers,id',
            'creditos_aplicar' => 'nullable|array',
            'creditos_aplicar.*' => 'exists:client_ledgers,id'
        ]);
 
        try {
            $this->accountService->aplicarSaldosAFavorEspecificos(
                $client->id, 
                $request->facturas_aplicar, 
                $request->input('creditos_aplicar', [])
            );
            return back()->with('success', "Compensación de documentos realizada con éxito.");
        } catch (\Exception $e) {
            return back()->with('error', "No se pudo realizar la aplicación: " . $e->getMessage());
        }
    }

    /**
     * Ajuste de datos para asegurar que registros viejos tengan pending_amount
     */
    private function healLedgerData($client)
    {
        // En una app real, esto se haría en un comando o migración. 
        // Aquí lo hacemos al entrar para asegurar correctitud operativa inmediata.
        ClientLedger::where('client_id', $client->id)
            ->where('type', 'debit')
            ->where('pending_amount', 0)
            ->where('paid', false)
            ->update(['pending_amount' => \DB::raw('amount')]);
    }

    /**
     * Devuelve las facturas impagas de un cliente por API
     */
    public function apiGetDeudas(Client $client)
    {
        if ($client->empresa_id !== auth()->user()->empresa_id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $deudas = ClientLedger::where('client_id', $client->id)
            ->where('type', 'debit')
            ->where('paid', false)
            ->where('pending_amount', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get(['id', 'description', 'pending_amount', 'created_at']);

        $resumen = $this->accountService->getAccountSummary($client->id);

        return response()->json([
            'deudas' => $deudas->map(function($d) {
                return [
                    'id' => $d->id,
                    'descripcion' => $d->description,
                    'pendiente' => $d->pending_amount,
                    'fecha' => $d->created_at->format('d/m/Y')
                ];
            }),
            'deuda_total' => $resumen['total_debit'],
            'saldo_a_favor' => $resumen['total_credit'], // Pendiente a favor
            'saldo_global' => $resumen['saldo_global']
        ]);
    }
}
