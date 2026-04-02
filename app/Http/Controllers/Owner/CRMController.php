<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CRMController extends Controller
{
    /**
     * Mostrar la pizarra de prospectos y ventas
     */
    public function index()
    {
        // Segmentamos a los usuarios segun su estado comercial
        $prospectos = User::where('status', 'prospecto')->where('role', 'empresa')->latest()->get();
        $pendientes = User::where('status', 'pendiente_pago')->where('role', 'empresa')->latest()->get();
        $activos = User::where('status', 'activo')->where('role', 'empresa')->latest()->limit(20)->get();

        return view('owner.crm.index', compact('prospectos', 'pendientes', 'activos'));
    }

    /**
     * Activar un prospecto que ya pago
     */
    public function activate(User $user)
    {
        $user->update([
            'status' => 'activo',
            'activo' => 1,
            'crm_notes' => ($user->crm_notes . "\n-- ACTIVADO POR EL OWNER EL " . now()->format('d/m/Y H:i'))
        ]);

        return redirect()->back()->with('success', '¡Excelente! ' . $user->name . ' ha sido activado y ya puede configurar su empresa.');
    }

    /**
     * Guardar notas internas del lead
     */
    public function updateNotes(Request $request, User $user)
    {
        $user->update(['crm_notes' => $request->notes]);
        return redirect()->back()->with('success', 'Notas actualizadas.');
    }
}
