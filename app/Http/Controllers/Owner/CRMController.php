<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CrmActivity;
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

        // Canales que queremos trackear en el dashboard
        $channels = ['LinkedIn', 'Instagram', 'Facebook', 'WhatsApp', 'Telegram', 'System Mail'];

        // 1. Hunted Leads: Conteos Reales por Fuente (Desde el modelo User)
        $hunted_counts = User::where('status', 'prospecto')
            ->selectRaw('lead_source, count(*) as total')
            ->groupBy('lead_source')
            ->pluck('total', 'lead_source')
            ->toArray();

        // 2. Scans e Interests (Hits): Conteos Reales de Actividad (Desde el modelo CrmActivity)
        $scanned_counts = CrmActivity::selectRaw('channel, count(*) as total')
            ->groupBy('channel')
            ->pluck('total', 'channel')
            ->toArray();

        $interest_counts = CrmActivity::where('status', 'interesado')
            ->selectRaw('channel, count(*) as total')
            ->groupBy('channel')
            ->pluck('total', 'channel')
            ->toArray();

        // Armando el array final para el hub
        $agent_data = [];
        foreach($channels as $ch) {
            $agent_data[$ch] = [
                'name'    => $ch,
                'scanned' => $scanned_counts[$ch] ?? 0,
                'hits'    => $interest_counts[$ch] ?? 0,
                'hunted'  => $hunted_counts[$ch] ?? 0,
                'color'   => $this->getChannelColor($ch)
            ];
        }

        return view('owner.crm.index', compact('prospectos', 'pendientes', 'activos', 'agent_data'));
    }

    /**
     * Helper para colores del Hub
     */
    private function getChannelColor($channel)
    {
        return match($channel) {
            'LinkedIn'    => 'sky',
            'Instagram'   => 'amber',
            'Facebook'    => 'primary',
            'WhatsApp'    => 'emerald',
            'Telegram'    => 'info',
            'System Mail' => 'secondary',
            default      => 'zinc-500'
        };
    }

    /**
     * Obtener bitácora real de un agente
     */
    public function agentReport(Request $request)
    {
        $channel = $request->channel;
        $logs = CrmActivity::where('channel', $channel)
            ->latest()
            ->limit(10)
            ->get()
            ->map(function($act) {
                return [
                    't' => $act->created_at->format('H:i'),
                    'm' => "Target: {$act->target_name} ({$act->target_origin}) - " . ($act->details ?? 'Sin detalles')
                ];
            });

        return response()->json($logs);
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

        // Registro en Bitácora Real
        $this->logActivity(
            $user->lead_source ?? 'LinkedIn',
            $user->name,
            $user->country ?? 'Argentina',
            "¡MISIÓN CUMPLIDA! El prospecto ha sido ACTIVADO como empresa oficial en el SaaS.",
            'activo'
        );

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

        // Registro en Bitácora Real
        $this->logActivity(
            $user->lead_source ?? 'LinkedIn',
            $user->name,
            $user->country ?? 'Argentina',
            "Movimiento de fase: El lead se movió de {$oldStatus} a {$newStatus}.",
            ($newStatus == 'activo' ? 'activo' : 'interesado')
        );

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

        // Registro en Bitácora Real
        $this->logActivity(
            $user->lead_source ?? 'LinkedIn',
            $user->name,
            $user->country ?? 'Argentina',
            "Limpieza de pizarra: Lead archivado para optimizar focus comercial.",
            'archivado'
        );

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
        $source = $user->lead_source ?? 'LinkedIn';
        
        // Registro en Bitácora Real antes de borrar
        $this->logActivity(
            $source,
            $name,
            'Argentina',
            "ELIMINACION TOTAL: User borrado permanentemente del sistema por el Owner.",
            'eliminado'
        );

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => "¡Lead Borrado! " . $name . " ha sido eliminado permanentemente."
        ]);
    }

    /**
     * MÉTODO PRIVADO PARA BITÁCORA REAL
     */
    private function logActivity($channel, $targetName, $origin, $details, $status = 'interesado')
    {
        CrmActivity::create([
            'channel'       => $channel,
            'target_name'   => $targetName,
            'target_origin' => $origin,
            'details'       => $details,
            'status'        => $status
        ]);
    }
}
