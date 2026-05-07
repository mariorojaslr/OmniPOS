<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Mostrar pantalla de login
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Procesar login y redirigir según rol
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = auth()->user();

        // ================= OWNER =================
        if ($user->role === 'owner') {
            return redirect()->route('owner.dashboard');
        }

        // ================= EMPRESA =================
        if ($user->role === 'empresa') {
            return redirect()->route('empresa.dashboard');
        }

        // ================= USUARIO =================
        if ($user->role === 'usuario') {
            return redirect()->route('empresa.usuario.dashboard');
        }

        // Rol inválido → cierre por seguridad
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        abort(403, 'Rol no permitido');
    }

    /**
     * Logout limpio (sin errores de sesión)
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
