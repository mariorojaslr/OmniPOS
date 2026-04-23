<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\CajaCierre;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CajaAuditoriaController extends Controller
{
    /**
     * 📋 Listado histórico de cierres de caja para auditoría
     */
    public function index(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;

        $query = CajaCierre::with(['user', 'asistencia'])
            ->where('empresa_id', $empresaId)
            ->orderByDesc('fecha_apertura');

        // Filtros (Opcionales)
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('desde')) {
            $query->whereDate('fecha_apertura', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->whereDate('fecha_apertura', '<=', $request->hasta);
        }

        $cierres = $query->paginate(15);

        return view('empresa.cajas.index', compact('cierres'));
    }

    /**
     * 👁️ Ver detalle profundo de un cierre específico
     */
    public function show(CajaCierre $cierre)
    {
        if ($cierre->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }

        // Cargamos el responsable y los gastos ocurridos durante ese turno
        $cierre->load(['user', 'asistencia']);
        
        $gastos = [];
        if ($cierre->asistencia_id) {
            $gastos = Expense::where('asistencia_id', $cierre->asistencia_id)->with('category')->get();
        }

        // --- DETALLE FINANCIERO (MOVIMIENTOS DE CAJA/BANCO) ---
        // Filtramos movimientos por el usuario y el rango de tiempo del arqueo
        $movimientos = \App\Models\FinanzaMovimiento::with(['cuenta', 'reference'])
            ->where('empresa_id', $cierre->empresa_id)
            ->where('user_id', $cierre->user_id)
            ->where('created_at', '>=', $cierre->fecha_apertura)
            ->when($cierre->fecha_cierre, function($q) use ($cierre) {
                return $q->where('created_at', '<=', $cierre->fecha_cierre);
            })
            ->get();

        // Agrupamos por tipo de cuenta ( breakdown )
        $breakdown = [
            'Efectivo' => 0,
            'Transferencias' => 0,
            'Cheques' => 0,
            'Otros' => 0
        ];

        foreach($movimientos as $m) {
            $tipo = $m->cuenta->tipo; // 'efectivo', 'banco', 'billetera', 'chequera'
            $monto = ($m->tipo == 'ingreso') ? $m->monto : -$m->monto;

            if($tipo == 'efectivo') $breakdown['Efectivo'] += $monto;
            elseif($tipo == 'banco' || $tipo == 'billetera') $breakdown['Transferencias'] += $monto;
            elseif($tipo == 'chequera') $breakdown['Cheques'] += $monto;
            else $breakdown['Otros'] += $monto;
        }

        // Detalle por cuenta específica
        $resumenPorCuenta = $movimientos->groupBy('cuenta_id')->map(function($movs) {
            $cuenta = $movs->first()->cuenta;
            return [
                'nombre' => $cuenta->nombre,
                'tipo' => $cuenta->tipo,
                'balance' => $movs->sum(fn($m) => ($m->tipo == 'ingreso' ? $m->monto : -$m->monto))
            ];
        });

        return view('empresa.cajas.show', compact('cierre', 'gastos', 'movimientos', 'breakdown', 'resumenPorCuenta'));
    }
}
