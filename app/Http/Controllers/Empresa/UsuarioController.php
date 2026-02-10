<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO USUARIOS DE LA EMPRESA (CON FILTRO)
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        $estado = $request->estado ?? 'activos';

        $query = User::where('empresa_id', $empresaId)
            ->where('role', 'usuario');

        if ($estado === 'activos') {
            $query->where('activo', 1);
        } elseif ($estado === 'inactivos') {
            $query->where('activo', 0);
        }

        $usuarios = $query->orderBy('name')->get();

        return view('empresa.usuarios.index', compact('usuarios', 'estado'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM CREAR USUARIO
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('empresa.usuarios.create');
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR NUEVO USUARIO
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|min:6'
        ]);

        // Si no escribe contraseña → generar automática
        $passwordPlano = $request->password
            ? $request->password
            : Str::random(8);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($passwordPlano),
            'empresa_id' => auth()->user()->empresa_id,
            'role' => 'usuario',
            'activo' => 1,
        ]);

        return redirect()
            ->route('empresa.usuarios.index')
            ->with('ok', 'Usuario creado. Contraseña inicial: ' . $passwordPlano);
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVAR / DESACTIVAR USUARIO
    |--------------------------------------------------------------------------
    */
    public function toggle(User $usuario)
    {
        if ($usuario->empresa_id != auth()->user()->empresa_id) {
            abort(403);
        }

        $usuario->activo = !$usuario->activo;
        $usuario->save();

        return back()->with('ok', 'Estado actualizado');
    }

    /*
    |--------------------------------------------------------------------------
    | RESET PASSWORD (GENERA NUEVA AUTOMÁTICA)
    |--------------------------------------------------------------------------
    */
    public function resetPassword(User $usuario)
    {
        if ($usuario->empresa_id != auth()->user()->empresa_id) {
            abort(403);
        }

        $nueva = Str::random(8);

        $usuario->password = Hash::make($nueva);
        $usuario->save();

        return back()->with('ok', 'Nueva contraseña: ' . $nueva);
    }

    /*
    |--------------------------------------------------------------------------
    | PANTALLA DESEMPEÑO (SOLO VISUAL POR AHORA)
    |--------------------------------------------------------------------------
    */
    public function desempeno(User $usuario)
    {
        if ($usuario->empresa_id != auth()->user()->empresa_id) {
            abort(403);
        }

        return view('empresa.usuarios.desempeno', compact('usuario'));
    }
}
