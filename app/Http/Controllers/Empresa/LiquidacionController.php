<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Liquidacion;
use App\Models\Turno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LiquidacionController extends Controller
{
    /**
     * Listado de liquidaciones realizadas.
     */
    public function index()
    {
        // El trait BelongsToEmpresa ya filtra por empresa_id automáticamente
        $liquidaciones = Liquidacion::with('profesional')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('empresa.liquidaciones.index', compact('liquidaciones'));
    }

    /**
     * Formulario para crear una nueva liquidación.
     */
    public function create()
    {
        $empresa_id = Auth::user()->empresa_id;
        
        // Obtenemos solo los usuarios que tienen turnos pendientes de liquidar
        $profesionales = User::where('empresa_id', $empresa_id)
            ->whereHas('turnosProfesional', function($query) {
                $query->whereNull('liquidacion_id')
                      ->where('estado', 'finalizado'); // Solo turnos terminados
            })->get();

        return view('empresa.liquidaciones.create', compact('profesionales'));
    }

    /**
     * API para obtener turnos pendientes de un profesional.
     */
    public function getPendientes(Request $request)
    {
        $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where('empresa_id', Auth::user()->empresa_id)
            ],
        ]);

        $empresa_id = Auth::user()->empresa_id;
        
        $turnos = Turno::where('empresa_id', $empresa_id)
            ->where('user_id', $request->user_id)
            ->whereNull('liquidacion_id')
            ->where('estado', 'finalizado')
            ->with('servicio', 'cliente')
            ->orderBy('fecha', 'asc')
            ->get();

        return response()->json([
            'turnos' => $turnos,
            'total_comisiones' => $turnos->sum('comision_monto'),
            'total_servicios' => $turnos->sum('monto')
        ]);
    }

    /**
     * Procesar la liquidación (Motor de Liquidación).
     */
    public function store(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;

        $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where('empresa_id', $empresa_id)
            ],
            'turnos_ids' => 'required|array',
            'turnos_ids.*' => [
                'required',
                Rule::exists('turnos', 'id')->where('empresa_id', $empresa_id)
            ],
            'periodo' => 'nullable|string',
            'notas' => 'nullable|string',
        ]);

        $empresa_id = Auth::user()->empresa_id;

        return DB::transaction(function () use ($request, $empresa_id) {
            
            // 1. Obtener los turnos y validar que pertenezcan a la empresa y no estén liquidados
            $turnos = Turno::where('empresa_id', $empresa_id)
                ->whereIn('id', $request->turnos_ids)
                ->whereNull('liquidacion_id')
                ->get();

            if ($turnos->isEmpty()) {
                return redirect()->back()->with('error', 'No se encontraron turnos válidos para liquidar.');
            }

            $total_comisiones = $turnos->sum('comision_monto');
            $total_servicios = $turnos->sum('monto');

            // 2. Crear el registro de liquidación
            $liquidacion = Liquidacion::create([
                'empresa_id' => $empresa_id,
                'user_id' => $request->user_id,
                'fecha_emision' => now(),
                'periodo_desde' => $turnos->min('fecha'),
                'periodo_hasta' => $turnos->max('fecha'),
                'monto_total' => $total_comisiones, // Lo que se le paga al profesional
                'estado' => 'pagado', // Por defecto pagado en este flujo simplificado
                'notas' => $request->notas ?? "Liquidación generada para el periodo " . ($request->periodo ?? 'actual'),
            ]);

            // 3. Vincular los turnos a la liquidación
            Turno::whereIn('id', $request->turnos_ids)
                ->update(['liquidacion_id' => $liquidacion->id]);

            return redirect()->route('empresa.liquidaciones.show', $liquidacion->id)
                ->with('success', 'Liquidación generada exitosamente.');
        });
    }

    /**
     * Ver detalle de una liquidación.
     */
    public function show($id)
    {
        $empresa_id = Auth::user()->empresa_id;
        $liquidacion = Liquidacion::where('empresa_id', $empresa_id)
            ->with(['profesional', 'turnos.servicio', 'turnos.cliente'])
            ->findOrFail($id);

        return view('empresa.liquidaciones.show', compact('liquidacion'));
    }

    /**
     * Anular una liquidación y liberar los turnos.
     */
    public function destroy($id)
    {
        $empresa_id = Auth::user()->empresa_id;
        $liquidacion = Liquidacion::where('empresa_id', $empresa_id)->findOrFail($id);

        return DB::transaction(function () use ($liquidacion) {
            // 1. Liberar los turnos vinculados
            Turno::where('liquidacion_id', $liquidacion->id)
                ->update(['liquidacion_id' => null]);

            // 2. Eliminar el registro de liquidación
            $liquidacion->delete();

            return redirect()->route('empresa.liquidaciones.index')
                ->with('success', 'Liquidación anulada y turnos liberados correctamente.');
        });
    }
}
