<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        return view('owner.empresas.index', [
            'empresas' => Empresa::orderBy('nombre_comercial')->get(),
        ]);
    }

    public function create()
    {
        return view('owner.empresas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_comercial' => 'required|string|max:255',
            'email' => 'nullable|email',
            'telefono' => 'nullable|string|max:50',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        Empresa::create($data + ['activo' => true]);

        return redirect()
            ->route('owner.empresas.index')
            ->with('success', 'Empresa creada correctamente');
    }

    public function edit(Empresa $empresa)
    {
        return view('owner.empresas.edit', compact('empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $data = $request->validate([
            'nombre_comercial' => 'required|string|max:255',
            'email' => 'nullable|email',
            'telefono' => 'nullable|string|max:50',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        $empresa->update($data);

        return redirect()
            ->route('owner.empresas.index')
            ->with('success', 'Empresa actualizada');
    }

    /**
     * 🔁 ACTIVAR / DESACTIVAR EMPRESA (ESTO ES LO QUE NO FUNCIONABA)
     */
    public function toggleStatus(Empresa $empresa): RedirectResponse
    {
        $empresa->update([
            'activo' => ! $empresa->activo,
        ]);

        return back()->with('success', 'Estado de la empresa actualizado');
    }

    /**
     * 🔄 RENOVAR EMPRESA
     */
    public function renovar(Empresa $empresa): RedirectResponse
    {
        $empresa->renovar(30);

        return back()->with('success', 'Empresa renovada por 30 días');
    }
}
