<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rubro;

class RubroController extends Controller
{
    public function index()
    {
        $rubros = Rubro::orderBy('nombre')->get();
        return view('empresa.rubros.index', compact('rubros'));
    }

    public function create()
    {
        return view('empresa.rubros.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        Rubro::create([
            'empresa_id' => auth()->user()->empresa_id,
            'nombre' => $request->nombre,
            'activo' => true
        ]);

        return redirect()->route('empresa.rubros.index')->with('success', 'Rubro creado correctamente.');
    }

    public function edit(Rubro $rubro)
    {
        return view('empresa.rubros.edit', compact('rubro'));
    }

    public function update(Request $request, Rubro $rubro)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $rubro->update($request->all());

        return redirect()->route('empresa.rubros.index')->with('success', 'Rubro actualizado correctamente.');
    }

    public function destroy(Rubro $rubro)
    {
        if ($rubro->products()->exists()) {
            return back()->with('error', 'No se puede eliminar un rubro que tiene productos asociados.');
        }

        $rubro->delete();
        return redirect()->route('empresa.rubros.index')->with('success', 'Rubro eliminado correctamente.');
    }
}
