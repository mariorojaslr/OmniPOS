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

        // Encontrar un usuario de esa empresa (normalmente el administrador)
        $user = User::where('empresa_id', $empresa->id)->first();

        if (!$user) {
            // Si no hay usuario, creamos uno temporal o devolvemos error
            return back()->with('error', 'No hay usuarios disponibles para la demo.');
        }

        // Loguear al usuario
        Auth::login($user);

        return redirect()->route('empresa.dashboard')->with('success', 'Bienvenido al modo Demo de MultiPOS 🚀✨ Disfrute de la experiencia completa.');
    }
}
