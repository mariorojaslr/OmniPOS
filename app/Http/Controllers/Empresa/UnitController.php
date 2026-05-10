<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;

class UnitController extends Controller
{
    /**
     * Listado de unidades de la empresa
     */
    public function index()
    {
        $units = Unit::where('empresa_id', auth()->user()->empresa_id)
            ->orWhereNull('empresa_id') // Para que vean las globales (estándar) que ya seedeamos
            ->orderBy('name')
            ->get();
            
        return view('empresa.units.index', compact('units'));
    }

    /**
     * Guardar nueva unidad corporativa
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'short_name'        => 'required|regex:/^[a-zA-Z0-9\s.]+$/|max:15',
            'base_unit_id'      => 'nullable|exists:units,id',
            'conversion_factor' => 'nullable|numeric|min:0.0001',
        ]);

        Unit::create([
            'empresa_id'        => auth()->user()->empresa_id,
            'name'              => strtoupper($request->name),
            'short_name'        => strtoupper($request->short_name),
            'base_unit_id'      => $request->base_unit_id,
            'conversion_factor' => $request->conversion_factor ?: 1,
            'active'            => true,
        ]);

        return back()->with('success', 'Unidad de Medida / Equivalencia creada correctamente.');
    }

    /**
     * Eliminar unidad (solo las que pertenecen a la empresa)
     */
    public function destroy(Unit $unit)
    {
        if ($unit->empresa_id !== auth()->user()->empresa_id) {
            return back()->with('error', 'No tiene permiso para eliminar esta unidad estándar.');
        }

        // Verificar si se está usando en productos antes de borrar
        if ($unit->products->count() > 0) {
             return back()->with('error', 'No puede borrar esta unidad porque ya se está usando en productos operativos.');
        }

        $unit->delete();
        return back()->with('success', 'Unidad de medida eliminada.');
    }
}
