<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'nombre_comercial'   => 'required|string|max:255',
            'email'              => 'required|email|unique:users,email',
            'telefono'           => 'nullable|string|max:50',
            'fecha_vencimiento'  => 'nullable|date',
        ]);

        /*
         |----------------------------------------------------------
         | Crear empresa
         |----------------------------------------------------------
         */
        $empresa = Empresa::create([
            'nombre_comercial'  => $data['nombre_comercial'],
            'email'             => $data['email'],
            'telefono'          => $data['telefono'] ?? null,
            'fecha_vencimiento' => $data['fecha_vencimiento'] ?? null,
            'activo'            => true,
        ]);

        /*
         |----------------------------------------------------------
         | Crear usuario principal de la empresa
         |----------------------------------------------------------
         */
        User::create([
            'name'              => $empresa->nombre_comercial,
            'email'             => $empresa->email,
            'password'          => Hash::make('12345678'), // password inicial
            'role'              => 'empresa',              // 👈 CORRECTO
            'empresa_id'        => $empresa->id,
            'activo'            => 1,
            'email_verified_at' => now(),                   // 👈 CLAVE
        ]);

        return redirect()
            ->route('owner.empresas.index')
            ->with('success', 'Empresa y usuario creados correctamente');
    }

    public function edit(Empresa $empresa)
    {
        return view('owner.empresas.edit', compact('empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $data = $request->validate([
            'nombre_comercial'  => 'required|string|max:255',
            'email'             => 'nullable|email',
            'telefono'          => 'nullable|string|max:50',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        $empresa->update($data);

        return redirect()
            ->route('owner.empresas.index')
            ->with('success', 'Empresa actualizada');
    }

    /**
     * ACTIVAR / DESACTIVAR EMPRESA
     */
    public function toggleStatus(Empresa $empresa): RedirectResponse
    {
        $empresa->update([
            'activo' => ! $empresa->activo,
        ]);

        return back()->with('success', 'Estado de la empresa actualizado');
    }

    /**
     * RENOVAR EMPRESA
     */
    public function renovar(Empresa $empresa): RedirectResponse
    {
        $empresa->renovar(30);

        return back()->with('success', 'Empresa renovada por 30 días');
    }
}
