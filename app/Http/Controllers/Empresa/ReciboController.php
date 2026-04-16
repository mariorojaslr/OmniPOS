<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recibo;
use App\Models\Client;
use App\Models\FinanzaCuenta; // Modelo correcto para saldos operativos
use App\Services\ClientAccountService;
use Illuminate\Support\Facades\Auth;

class ReciboController extends Controller
{
    protected $accountService;

    public function __construct(ClientAccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Display a listing of the payments (Recibos).
     */
    public function index()
    {
        $empresaId = Auth::user()->empresa_id;
        $recibos = Recibo::with(['client', 'pagos', 'user'])
            ->where('empresa_id', $empresaId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('empresa.pagos.index', compact('recibos'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create()
    {
        $empresaId = Auth::user()->empresa_id;
        $clientes = Client::where('empresa_id', $empresaId)
            ->where('document', '!=', 'CF') 
            ->orderBy('name')
            ->get();

        $cuentas = FinanzaCuenta::where('empresa_id', $empresaId)
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('empresa.pagos.create', compact('clientes', 'cuentas'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id'           => 'required|exists:clients,id',
            'monto'               => 'required|numeric|min:0',
            'fecha'               => 'required|date',
            'pagos_diferenciados' => 'required|array|min:1',
        ]);

        try {
            $recibo = $this->accountService->registrarCobro(
                $request->client_id,
                $request->monto,
                'Múltiple', 
                null, 
                $request->fecha,
                [], // facturasEspecificas
                $request->pagos_diferenciados, // diferenciados
                false // $autoImputar (false = pago solito flotante)
            );

            return redirect()->route('empresa.pagos.index')->with('success', "Pago/Recibo #{$recibo->numero_recibo} registrado exitosamente.");
        } catch (\Exception $e) {
            return back()->with('error', "Error al registrar el pago: " . $e->getMessage())->withInput();
        }
    }

    /**
     * View a specific payment/receipt.
     */
    public function show($id)
    {
        $empresaId = Auth::user()->empresa_id;
        $recibo = Recibo::with(['client', 'pagos', 'user'])
            ->where('empresa_id', $empresaId)
            ->findOrFail($id);

        return view('empresa.pagos.show', compact('recibo'));
    }

    /**
     * Print a specific payment/receipt.
     */
    public function print($id)
    {
        $empresaId = Auth::user()->empresa_id;
        $recibo = Recibo::with(['client', 'pagos', 'user', 'empresa'])
            ->where('empresa_id', $empresaId)
            ->findOrFail($id);

        return view('empresa.pagos.print', compact('recibo'));
    }

    /**
     * Update references and bank data for payment composition lines.
     */
    public function updateReferences(Request $request, $id)
    {
        $empresaId = Auth::user()->empresa_id;
        $recibo = Recibo::where('empresa_id', $empresaId)->findOrFail($id);

        if ($request->has('pagos') && is_array($request->pagos)) {
            foreach ($request->pagos as $pagoId => $data) {
                // Asegurarse de que el pago exista y pertenezca a este recibo
                $pago = \App\Models\ReciboPago::where('recibo_id', $recibo->id)->find($pagoId);
                if ($pago) {
                    $pago->update([
                        'referencia'         => $data['referencia'] ?? $pago->referencia,
                        'banco'              => $data['banco'] ?? $pago->banco,
                        'fecha_emision'      => $data['fecha_emision'] ?? $pago->fecha_emision,
                        'fecha_acreditacion' => $data['fecha_acreditacion'] ?? $pago->fecha_acreditacion,
                    ]);
                }
            }
        }

        return redirect()->route('empresa.pagos.show', $recibo->id)
            ->with('success', 'Las referencias y datos bancarios fueron actualizados correctamente.');
    }
}
