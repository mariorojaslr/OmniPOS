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
            'empresas' => Empresa::with('plan')->orderBy('nombre_comercial')->get(),
        ]);
    }

    public function create()
    {
        $planes = \App\Models\Plan::where('is_active', true)->get();
        return view('owner.empresas.create', compact('planes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_comercial'   => 'required|string|max:255',
            'email'              => 'required|email|unique:users,email',
            'telefono'           => 'nullable|string|max:50',
            'fecha_vencimiento'  => 'nullable|date',
            'password'           => 'nullable|string|min:6', // 👈 OPCIONAL
            'plan_id'            => 'nullable|exists:plans,id',
        ]);

        /*
        |----------------------------------------------------------
        | CREAR EMPRESA
        |----------------------------------------------------------
        */
        $empresa = Empresa::create([
            'nombre_comercial'  => $data['nombre_comercial'],
            'email'             => $data['email'],
            'telefono'          => $data['telefono'] ?? null,
            'fecha_vencimiento' => $data['fecha_vencimiento'] ?? null,
            'plan_id'           => $data['plan_id'] ?? null,
            'status'            => 'activa',
            'activo'            => true,
        ]);

        /*
        |----------------------------------------------------------
        | PASSWORD AUTOMÁTICA SEGURA
        |----------------------------------------------------------
        */
        $passwordPlano = $data['password'] ?? Str::random(10);

        /*
        |----------------------------------------------------------
        | CREAR USUARIO PRINCIPAL (ROL EMPRESA)
        |----------------------------------------------------------
        */
        User::create([
            'name'                   => $empresa->nombre_comercial,
            'email'                  => $empresa->email,
            'password'               => Hash::make($passwordPlano),
            'role'                   => 'empresa',
            'empresa_id'             => $empresa->id,
            'activo'                 => 1,
            'must_change_password'   => 1, // 👈 OBLIGAR CAMBIO EN PRIMER LOGIN
            'email_verified_at'      => now(),
        ]);

        /*
        |----------------------------------------------------------
        | MENSAJE CON PASSWORD GENERADA (SOLO SI FUE AUTOMÁTICA)
        |----------------------------------------------------------
        */
        $msg = 'Empresa creada correctamente.';

        if (!isset($data['password'])) {
            $msg .= ' Password inicial generada: ' . $passwordPlano;
        }

        return redirect()
            ->route('owner.empresas.index')
            ->with('success', $msg);
    }

    public function edit(Empresa $empresa)
    {
        $planes = \App\Models\Plan::where('is_active', true)->get();
        return view('owner.empresas.edit', compact('empresa', 'planes'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $data = $request->validate([
            'nombre_comercial'  => 'required|string|max:255',
            'email'             => 'nullable|email',
            'telefono'          => 'nullable|string|max:50',
            'fecha_vencimiento' => 'nullable|date',
            'plan_id'           => 'nullable|exists:plans,id',
            'status'            => 'required|string|in:activa,suspendida,mora',
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
