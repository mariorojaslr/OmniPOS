<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\ActivityLog;

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
            ->where('id', '!=', auth()->id()); // No mostrarse a sí mismo para evitar auto-edición

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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($passwordPlano),
            'empresa_id' => auth()->user()->empresa_id,
            'role' => $request->role ?? 'usuario', // Ahora acepta 'empresa' o 'usuario'
            'sub_role' => $request->sub_role ?? 'cajero',
            'can_register_expenses' => $request->has('can_register_expenses'), 
            'activo' => 1,
            'status' => 'activo', // 👈 Crucial para que no lo mande a pagar
        ]);

        // REGISTRAR ACTIVIDAD
        ActivityLog::log("Creó al usuario: {$user->name} ({$user->email})", $user);

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

        // REGISTRAR ACTIVIDAD
        $accion = $usuario->activo ? "Activó" : "Desactivó";
        ActivityLog::log("{$accion} el acceso del usuario: {$usuario->name}", $usuario);

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

        // REGISTRAR ACTIVIDAD
        ActivityLog::log("Reseteó la contraseña del usuario: {$usuario->name}", $usuario);

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

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR USUARIO (NOMBRE, MAIL, ROLES Y FACULTADES)
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, User $usuario)
    {
        if ($usuario->empresa_id != auth()->user()->empresa_id) {
            abort(403);
        }

        $request->validate([
            'name'     => 'required|string|max:120',
            'email'    => 'required|email|unique:users,email,' . $usuario->id,
            'role'     => 'required|in:empresa,usuario',
            'sub_role' => 'nullable|in:cajero,operativo,empleado',
        ]);

        $usuario->name     = $request->name;
        $usuario->email    = $request->email;
        $usuario->role     = $request->role;
        $usuario->sub_role = $request->sub_role ?? 'cajero';

        // Facultades (Switches)
        $usuario->can_register_expenses = $request->has('can_register_expenses');
        $usuario->can_manage_purchases  = $request->has('can_manage_purchases');
        $usuario->can_sell              = $request->has('can_sell');
        
        $usuario->save();

        // REGISTRAR ACTIVIDAD
        ActivityLog::log("Actualizó el perfil y facultades del usuario: {$usuario->name}", $usuario);

        return back()->with('ok', 'Usuario actualizado correctamente.');
    }
}
