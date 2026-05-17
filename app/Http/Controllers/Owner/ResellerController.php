<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResellerController extends Controller
{
    /**
     * Listar todos los revendedores.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        
        $resellers = User::where('role', User::ROLE_REVENDEDOR)
            ->withCount('empresasAsignadas') // asumiendo que agregaremos relación a User si la necesitamos, sino lo calculamos en la vista
            ->orderBy('name')
            ->paginate($perPage);

        return view('owner.resellers.index', compact('resellers', 'perPage'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        return view('owner.resellers.create');
    }

    /**
     * Guardar nuevo revendedor.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:6',
        ]);

        $passwordPlano = $data['password'] ?? Str::random(10);

        $user = User::create([
            'name'                 => $data['name'],
            'email'                => $data['email'],
            'password'             => Hash::make($passwordPlano),
            'role'                 => User::ROLE_REVENDEDOR,
            'activo'               => 1,
            'must_change_password' => 1,
            'email_verified_at'    => now(),
        ]);

        $commission = $request->input('owner_commission_percentage', 10.00);
        $user->resellerConfig()->create([
            'owner_commission_percentage' => $commission
        ]);

        $msg = 'Revendedor creado exitosamente.';
        if (!isset($data['password'])) {
            $msg .= ' Contraseña inicial generada: ' . $passwordPlano;
        }

        return redirect()
            ->route('owner.resellers.index')
            ->with('success', $msg);
    }

    /**
     * Formulario de edición.
     */
    public function edit(User $reseller)
    {
        if (!$reseller->isRevendedor()) {
            abort(404, 'Usuario no es revendedor.');
        }

        return view('owner.resellers.edit', compact('reseller'));
    }

    /**
     * Actualizar revendedor.
     */
    public function update(Request $request, User $reseller)
    {
        if (!$reseller->isRevendedor()) {
            abort(404);
        }

        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $reseller->id,
            'activo' => 'boolean',
        ]);

        // Aseguramos el checkbox 'activo' (que si no se envía es false)
        $data['activo'] = $request->has('activo');

        $reseller->update($data);

        if ($request->has('owner_commission_percentage')) {
            $reseller->resellerConfig()->updateOrCreate(
                ['user_id' => $reseller->id],
                ['owner_commission_percentage' => $request->input('owner_commission_percentage')]
            );
        }

        return redirect()
            ->route('owner.resellers.index')
            ->with('success', 'Revendedor actualizado exitosamente.');
    }

    /**
     * Mimetización: Entrar al panel del Revendedor.
     */
    public function impersonate(User $reseller): RedirectResponse
    {
        if (!$reseller->isRevendedor()) {
            return back()->with('error', 'El usuario seleccionado no es un revendedor.');
        }

        // Guardamos quién era el admin real antes de saltar
        session(['impersonator_id' => Auth::id()]);

        // Nos logueamos como el revendedor
        Auth::login($reseller);

        return redirect()
            ->route('revendedor.dashboard')
            ->with('info', 'Estás navegando la plataforma mimetizado como el revendedor: ' . $reseller->name);
    }
}
