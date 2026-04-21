<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EmpresaUserController extends Controller
{
    public function index($empresaId)
    {
        $empresa = Empresa::findOrFail($empresaId);
        
        return view('owner.empresas.users.index', [
            'empresa' => $empresa,
            'users'   => $empresa->users()->orderBy('name')->get(),
        ]);
    }

    public function create($empresaId)
    {
        $empresa = Empresa::findOrFail($empresaId);
        return view('owner.empresas.users.create', compact('empresa'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREAR USUARIO (password manual o automático)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request, $empresaId)
    {
        $empresa = Empresa::findOrFail($empresaId);

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
            ->route('owner.empresas.users.index', $empresa->id)
            ->with('success', "Usuario creado correctamente. Password: {$password}");
    }

    /*
    |--------------------------------------------------------------------------
    | Activar / Desactivar usuario
    |--------------------------------------------------------------------------
    */
    public function toggle($empresaId, User $usuario)
    {
        $empresa = Empresa::findOrFail($empresaId);
        abort_if($usuario->empresa_id !== $empresa->id, 403);

        $usuario->update([
            'activo' => ! $usuario->activo,
        ]);

        return back()->with('success', 'Estado del usuario actualizado');
    }

    /*
    |--------------------------------------------------------------------------
    | Resetear password (siempre automático)
    |--------------------------------------------------------------------------
    */
    public function resetPassword($empresaId, User $usuario)
    {
        $empresa = Empresa::findOrFail($empresaId);
        abort_if($usuario->empresa_id !== $empresa->id, 403);

        $password = Str::random(8);

        $usuario->forceFill([
            'password' => Hash::make($password),
        ])->save();

        return back()->with('success', "Nuevo password: {$password}");
    }

    /**
     * ENTRAR COMO USUARIO (Mimetización)
     */
    public function impersonate($empresaId, User $usuario)
    {
        try {
            $empresa = Empresa::findOrFail($empresaId);
            
            // Verificar que el usuario pertenezca a la empresa por seguridad
            abort_if($usuario->empresa_id !== $empresa->id, 403);

            $ownerId = auth()->id();

            // Guardar el ID del owner ANTES del login para asegurar persistencia
            session(['impersonator_id' => $ownerId]);

            // Iniciar sesión silenciosamente
            Auth::login($usuario);

            return redirect()->route('empresa.dashboard')
                ->with('info', "Mimetización activa: Estás viendo la plataforma como {$usuario->name}");

        } catch (\Exception $e) {
            return back()->with('error', 'Error al intentar entrar como usuario: ' . $e->getMessage());
        }
    }
}
