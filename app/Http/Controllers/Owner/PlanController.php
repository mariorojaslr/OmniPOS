<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $planes = Plan::withCount('empresas')->get();
        return view('owner.planes.index', compact('planes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('owner.planes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'numeric|min:0',
            'max_users' => 'integer|min:0',
            'max_products' => 'integer|min:0',
            'max_storage_mb' => 'numeric|min:0',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Plan::create($validated);

        return redirect()->route('owner.planes.index')->with('success', 'Plan creado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan)
    {
        return view('owner.planes.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'numeric|min:0',
            'max_users' => 'integer|min:0',
            'max_products' => 'integer|min:0',
            'max_storage_mb' => 'numeric|min:0',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $plan->update($validated);

        return redirect()->route('owner.planes.index')->with('success', 'Plan actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        if ($plan->empresas()->count() > 0) {
            return redirect()->route('owner.planes.index')->with('error', 'No puedes eliminar un plan que tiene empresas asignadas.');
        }

        $plan->delete();
        return redirect()->route('owner.planes.index')->with('success', 'Plan eliminado correctamente.');
    }
}
