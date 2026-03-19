<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;

class DemoController extends Controller
{
    /**
     * Acceso directo al modo Demo/Prueba
     */
    public function enter()
    {
        // Encontrar la empresa de prueba
        $empresa = Empresa::where('nombre_comercial', 'LIKE', '%Prueba%')->first();
        
        if (!$empresa) {
            return back()->with('error', 'La empresa de prueba no está configurada aún.');
        }

        // Priorizar el usuario con rol 'empresa' (administrador) para mostrar el dashboard completo
        $user = User::where('empresa_id', $empresa->id)
                    ->orderByRaw("FIELD(role, 'empresa', 'usuario')")
                    ->first();

        if (!$user) {
            return back()->with('error', 'No hay usuarios disponibles para la demo.');
        }

        // AUTO-ACTIVACIÓN: Asegurar que el usuario de prueba siempre esté activo para evitar errores 403
        if (!$user->activo) {
            $user->activo = true;
            $user->save();
        }

        // Loguear al usuario
        Auth::login($user);

        return redirect()->route('empresa.dashboard')->with('success', 'Bienvenido al modo Demo de MultiPOS 🚀✨ Disfrute de la experiencia completa.');
    }
}
