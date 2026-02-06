<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmpresaUserController extends Controller
{
    public function index(Empresa $empresa)
    {
        return view('owner.empresas.users.index', [
            'empresa' => $empresa,
            'users'   => $empresa->users()->orderBy('name')->get(),
        ]);
    }

    public function create(Empresa $empresa)
    {
        return view('owner.empresas.users.create', compact('empresa'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREAR USUARIO (password manual o automático)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request, Empresa $empresa)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:6',
        ]);

        // ✔ Si el usuario escribió password → usar ese
        // ✔ Si dejó vacío → generar automático
        $password = $data['password'] ?? Str::random(8);

        User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'password'          => Hash::make($password),
            'empresa_id'        => $empresa->id,
            'role'              => 'usuario',
            'activo'            => 1,
            'email_verified_at' => now(),
        ]);

        return redirect()
            ->route('owner.empresas.users.index', $empresa)
            ->with('success', "Usuario creado correctamente. Password: {$password}");
    }

    /*
    |--------------------------------------------------------------------------
    | Activar / Desactivar usuario
    |--------------------------------------------------------------------------
    */
    public function toggle(Empresa $empresa, User $user)
    {
        abort_if($user->empresa_id !== $empresa->id, 403);

        $user->update([
            'activo' => ! $user->activo,
        ]);

        return back()->with('success', 'Estado del usuario actualizado');
    }

    /*
    |--------------------------------------------------------------------------
    | Resetear password (siempre automático)
    |--------------------------------------------------------------------------
    */
    public function resetPassword(Empresa $empresa, User $user)
    {
        abort_if($user->empresa_id !== $empresa->id, 403);

        $password = Str::random(8);

        $user->forceFill([
            'password' => Hash::make($password),
        ])->save();

        return back()->with('success', "Nuevo password: {$password}");
    }
}
