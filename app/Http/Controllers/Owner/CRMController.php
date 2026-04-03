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
        // Segmentamos a los usuarios segun su estado comercial con paginacion independiente
        $prospectos = User::where('status', 'prospecto')->where('role', 'empresa')->latest()->paginate(15, ['*'], 'prospectos');
        $pendientes = User::where('status', 'pendiente_pago')->where('role', 'empresa')->latest()->paginate(15, ['*'], 'pendientes');
        $activos = User::where('status', 'activo')->where('role', 'empresa')->latest()->paginate(25, ['*'], 'activos');

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

    /**
     * Mover un usuario entre columnas de estado (Drag & Drop AJAX)
     */
    public function move(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $oldStatus = $user->status;
        $newStatus = $request->status;

        // Reglas de negocio segun el movimiento
        $updateData = ['status' => $newStatus];

        if ($newStatus === 'activo') {
            $updateData['activo'] = 1;
            $updateData['crm_notes'] = ($user->crm_notes . "\n-- ACTIVADO VIA CRM DRAG & DROP EL " . now()->format('d/m/Y H:i'));
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => "¡Movimiento exitoso! " . ($user->name ?? 'Usuario') . " ahora está en " . $newStatus
        ]);
    }
    /**
     * Olvidar (Archivar) un lead para limpiar la pizarra sin perder datos
     */
    public function archive(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->update(['status' => 'archivado']);

        return response()->json([
            'success' => true,
            'message' => "¡Lead Olvidado! " . ($user->name ?? 'Usuario') . " ahora está en el archivo histórico."
        ]);
    }

    /**
     * Borrar (Eliminar) definitivamente un lead (para pruebas y basura)
     */
    public function delete(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $name = $user->name;
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => "¡Lead Borrado! " . $name . " ha sido eliminado permanentemente."
        ]);
    }
}
