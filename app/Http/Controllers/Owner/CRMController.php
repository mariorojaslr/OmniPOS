<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CrmActivity;
use App\Services\LeadScannerService;
use Illuminate\Http\Request;

class CRMController extends Controller
{
    /**
     * Mostrar la pizarra de prospectos y ventas
     */
    public function index()
    {
        // Leads puros detectados por el Bot
        $botLeads = \App\Models\OwnerCrmLead::where('status', 'nuevo')->latest()->paginate(15, ['*'], 'botLeads');

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

        return view('owner.crm.index', compact('botLeads', 'prospectos', 'pendientes', 'activos', 'agent_data'));
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
        
        $query = CrmActivity::query();
        
        if (!empty($channel)) {
            $query->where('channel', $channel);
        }
        
        $logs = $query->latest()
            ->limit(20)
            ->get()
            ->map(function($act) {
                return [
                    't' => $act->created_at->format('d/m H:i'),
                    'm' => "[{$act->channel}] Target: {$act->target_name} ({$act->target_origin}) - " . ($act->details ?? 'Sin detalles')
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
        $user = User::with('empresa')->findOrFail($request->user_id);
        $oldStatus = $user->status;
        $newStatus = $request->status;

        // Reglas de negocio segun el movimiento
        $updateData = ['status' => $newStatus];

        if ($newStatus === 'activo') {
            $updateData['activo'] = 1;
            
            $method = $request->payment_method ?? 'efectivo';
            $amount = $request->payment_amount ?? 0;
            
            $noteAdd = "\n-- ACTIVADO VIA CRM (Pago: {$method}) EL " . now()->format('d/m/Y H:i');
            $updateData['crm_notes'] = $user->crm_notes . $noteAdd;
            
            // Renovar mes de servicio en la persistencia Multi-Empresa
            if ($user->empresa) {
                // Removemos alertas de cobro o gracia
                $vencimiento = $user->empresa->fecha_vencimiento && $user->empresa->fecha_vencimiento > now() 
                    ? $user->empresa->fecha_vencimiento->addDays(30) 
                    : now()->addDays(30);
                    
                $user->empresa->update([
                    'fecha_vencimiento' => $vencimiento,
                    'fecha_cierre' => null // cancelamos suspension si tenia
                ]);
                
                // Generar registro real de Ingreso SaaS 
                \App\Models\OwnerPayment::create([
                    'empresa_id' => $user->empresa->id,
                    'user_id'    => $user->id,
                    'amount'     => $amount,
                    'method'     => $method,
                    'notes'      => 'Renovación generada desde CRM Drag & Drop'
                ]);
            }
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
        try {
            $userId = $request->user_id;
            $status = $request->status ?? 'user';
            
            if ($status === 'nuevo_bot') {
                $lead = \App\Models\OwnerCrmLead::findOrFail($userId);
                $name = $lead->name;
                $source = $lead->origin ?? 'Bot Raw';
                $lead->delete();
            } else {
                $user = User::findOrFail($userId);
                $name = $user->name;
                $source = $user->lead_source ?? 'LinkedIn';
                $user->delete();
            }

            // Registro en Bitácora Real
            $this->logActivity(
                $source,
                $name,
                'Argentina',
                "ELIMINACION DEFINITIVA: El registro ha sido borrado permanentemente por el Owner.",
                'eliminado'
            );

            return response()->json([
                'success' => true,
                'message' => "¡Registro Borrado! " . $name . " ha sido eliminado."
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
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

    /**
     * ESCANEO REAL: Dispara el agente de búsqueda para un canal específico.
     * Usa DuckDuckGo (gratis) para encontrar negocios que podrían necesitar POS.
     */
    public function scanChannel(Request $request)
    {
        $channel = $request->channel;
        
        if (!$channel) {
            return response()->json(['error' => 'Canal no especificado'], 400);
        }

        $scanner = new LeadScannerService();
        $result = $scanner->scan($channel);

        return response()->json([
            'success'    => true,
            'channel'    => $channel,
            'found'      => $result['found'],
            'stored'     => $result['stored'],
            'duplicates' => $result['duplicates'],
            'errors'     => $result['errors'],
            'message'    => "Agente {$channel}: {$result['stored']} nuevos leads encontrados de {$result['found']} resultados."
        ]);
    }

    /**
     * ESCANEO TOTAL: Dispara todos los agentes a la vez.
     */
    public function scanAll()
    {
        $scanner = new LeadScannerService();
        $summary = $scanner->scanAll();

        $totalStored = 0;
        foreach ($summary as $ch => $data) {
            $totalStored += $data['stored'];
        }

        return response()->json([
            'success' => true,
            'summary' => $summary,
            'message' => "Misión completa: {$totalStored} leads nuevos en total."
        ]);
    }

    /**
     * PROMOVER MANUALMENTE: Convierte un lead de Fase 00 en un Prospecto Real (Fase 01)
     */
    public function promote(Request $request)
    {
        try {
            $leadId = $request->lead_id;
            $lead = \App\Models\OwnerCrmLead::findOrFail($leadId);

            // Crear el usuario prospecto
            $user = User::create([
                'name'        => $lead->name,
                'email'       => $lead->email ?? (str_replace(' ', '-', strtolower($lead->name)) . '@detectado.multipos.system'),
                'password'    => \Illuminate\Support\Facades\Hash::make('prospecto123'),
                'role'        => 'empresa',
                'status'      => 'prospecto',
                'lead_source' => $lead->origin ?? 'LinkedIn',
                'country'     => 'Argentina',
                'activo'      => false
            ]);

            // Borrar de la lista de leads "crudos" para que no se duplique
            $lead->delete();

            return response()->json([
                'success' => true,
                'message' => "¡Lead promovido! " . $user->name . " ahora es un prospecto registrado."
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
