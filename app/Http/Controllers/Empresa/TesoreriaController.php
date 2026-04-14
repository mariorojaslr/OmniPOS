<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\FinanzaCuenta;
use App\Models\FinanzaMovimiento;
use App\Services\TesoreriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TesoreriaController extends Controller
{
    protected $tesoreriaService;

    public function __construct(TesoreriaService $tesoreriaService)
    {
        $this->tesoreriaService = $tesoreriaService;
    }

    /**
     * Dashboard de Tesorería: Cuentas y Movimientos recientes
     */
    public function index(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;

        // Query base de cuentas activas
        $cuentasQuery = FinanzaCuenta::where('empresa_id', $empresaId)->where('activo', true);

        // Filtrado opcional desde el sidebar (Bancos o Billeteras)
        if ($request->filter === 'banco') {
            $cuentasQuery->where('tipo', 'banco');
        } elseif ($request->filter === 'billetera') {
            $cuentasQuery->where('tipo', 'billetera_digital');
        }

        $cuentas = $cuentasQuery->withCount(['movimientos'])->get();

        // Movimientos globales para la "Sábana de Control"
        $movimientos = FinanzaMovimiento::where('empresa_id', $empresaId)
            ->with('cuenta')
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->limit(15)
            ->get();

        // MÉTRICAS CONSOLIDADAS (Dashboard Superior)
        $metricas = [
            'total_general'    => FinanzaCuenta::where('empresa_id', $empresaId)->where('activo', true)->sum('saldo_actual'),
            'total_bancos'     => FinanzaCuenta::where('empresa_id', $empresaId)->where('activo', true)->where('tipo', 'banco')->sum('saldo_actual'),
            'total_billeteras' => FinanzaCuenta::where('empresa_id', $empresaId)->where('activo', true)->where('tipo', 'billetera_digital')->sum('saldo_actual'),
            'total_efectivo'   => FinanzaCuenta::where('empresa_id', $empresaId)->where('activo', true)->where('tipo', 'caja')->sum('saldo_actual'),
        ];

        return view('empresa.tesoreria.index', compact('cuentas', 'movimientos', 'metricas'));
    }

    /**
     * Crear una nueva cuenta financiera
     */
    public function storeCuenta(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo'   => 'required|in:caja,banco,billetera_digital,tarjeta_credito',
            'saldo_inicial' => 'nullable|numeric|min:0',
        ]);

        $empresaId = Auth::user()->empresa_id;

        $cuenta = FinanzaCuenta::create([
            'empresa_id'    => $empresaId,
            'nombre'        => $request->nombre,
            'tipo'          => $request->tipo,
            'numero_cuenta' => $request->numero_cuenta,
            'cbu_cvu'       => $request->cbu_cvu,
            'saldo_inicial' => $request->saldo_inicial ?? 0,
            'saldo_actual'  => $request->saldo_inicial ?? 0,
        ]);

        // Si hay saldo inicial, registrar movimiento de apertura
        if ($cuenta->saldo_inicial > 0) {
            $this->tesoreriaService->registrarMovimiento($cuenta->id, 'ingreso', $cuenta->saldo_inicial, "Saldo inicial de cuenta", [
                'categoria' => 'Apertura',
                'conciliado' => true
            ]);
        }

        return back()->with('success', "Cuenta '{$cuenta->nombre}' creada con éxito.");
    }

    /**
     * Guardar cuenta bancaria de un tercero (Cliente o Proveedor)
     */
    public function storeBankAccount(Request $request)
    {
        $request->validate([
            'holder_type' => 'required|string',
            'holder_id'   => 'required|integer',
            'bank_name'   => 'required|string|max:255',
            'cbu_cvu'     => 'nullable|string|max:22',
        ]);

        $empresaId = Auth::user()->empresa_id;

        $bankAccount = \App\Models\BankAccount::create([
            'empresa_id'     => $empresaId,
            'holder_type'    => $request->holder_type, // Ej: App\Models\Client
            'holder_id'      => $request->holder_id,
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
            'cbu_cvu'        => $request->cbu_cvu,
            'alias'          => $request->alias,
            'account_type'   => $request->account_type,
        ]);

        return back()->with('success', "Cuenta bancaria registrada con éxito.");
    }

    /**
     * Reporte de Flujo de Fondos (Cash Flow Proyectado)
     */
    public function proyeccion()
    {
        $empresaId = Auth::user()->empresa_id;
        $hoy = now();
        $proyeccionDias = 30; // Proyectar a 30 días

        // 1. Saldo inicial (Hoy)
        $saldoInicial = FinanzaCuenta::where('empresa_id', $empresaId)->sum('saldo_actual');

        // 2. Ingresos proyectados (Cheques de terceros en cartera)
        $ingresosCheques = \App\Models\Cheque::where('empresa_id', $empresaId)
            ->where('tipo', 'tercero')
            ->where('estado', 'en_cartera')
            ->whereDate('fecha_pago', '<=', $hoy->copy()->addDays($proyeccionDias))
            ->orderBy('fecha_pago')
            ->get();

        // 3. Egresos proyectados (Cheques propios entregados)
        $egresosCheques = \App\Models\Cheque::where('empresa_id', $empresaId)
            ->where('tipo', 'propio')
            ->where('estado', 'entregado')
            ->whereDate('fecha_pago', '<=', $hoy->copy()->addDays($proyeccionDias))
            ->orderBy('fecha_pago')
            ->get();

        // 4. Armar resumen diario
        $diario = [];
        for ($i = 0; $i <= $proyeccionDias; $i++) {
            $fecha = $hoy->copy()->addDays($i)->format('Y-m-d');
            $diario[$fecha] = [
                'pago'      => $hoy->copy()->addDays($i),
                'ingresos'  => $ingresosCheques->where('fecha_pago', $hoy->copy()->addDays($i)->startOfDay())->sum('monto'),
                'egresos'   => $egresosCheques->where('fecha_pago', $hoy->copy()->addDays($i)->startOfDay())->sum('monto'),
            ];
        }

        return view('empresa.tesoreria.proyeccion', compact('saldoInicial', 'diario', 'proyeccionDias'));
    }
}
