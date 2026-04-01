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

        return view('empresa.cajas.show', compact('cierre', 'gastos'));
    }
}
