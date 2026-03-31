<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;

class AsistenciaQrController extends Controller
{
    /**
     * 🖥️ Pantalla para que el administrador imprima el QR de la empresa
     */
    public function showQr()
    {
        $empresa = Auth::user()->empresa;
        
        // URL que los empleados deben escanear (asumiendo que están logueados en su móvil)
        $urlResitro = route('empresa.personal.asistencia.qr-registro', ['slug' => $empresa->slug]);

        return view('empresa.personal.qr_management', compact('empresa', 'urlResitro'));
    }

    /**
     * 📲 Ruta que se abre al escanear el QR
     */
    public function qrRegistro(Request $request, $slug)
    {
        $empresa = Empresa::where('slug', $slug)->firstOrFail();
        $user = Auth::user();

        // Validar que el usuario pertenezca a esta empresa si está logueado
        if ($user && $user->empresa_id !== $empresa->id) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Tu usuario no pertenece a esta empresa lograda vía QR.');
        }

        // Si no está logueado, mandarlo a loguear pero guardando el retorno
        if (!$user) {
            return redirect()->route('login')->with('info', 'Por favor, inicia sesión para registrar tu asistencia en ' . $empresa->name);
        }

        // Verificar si tiene una jornada activa
        $asistenciaActiva = Asistencia::where('user_id', $user->id)
            ->where('empresa_id', $empresa->id)
            ->whereNull('salida')
            ->first();

        return view('empresa.personal.qr_scan_action', compact('empresa', 'user', 'asistenciaActiva'));
    }
}
