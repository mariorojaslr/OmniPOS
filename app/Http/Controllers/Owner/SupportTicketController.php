<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;

class SupportTicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with(['empresa', 'user'])->orderByRaw("FIELD(status, 'abierto', 'en_proceso', 'cerrado')")->orderByDesc('created_at')->get();
        return view('owner.tickets.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = SupportTicket::with(['empresa', 'user'])->findOrFail($id);
        return view('owner.tickets.show', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:abierto,en_proceso,cerrado',
            'priority' => 'nullable|in:baja,media,alta',
            'respuesta_owner' => 'nullable|string',
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $ticket->update([
            'status' => $request->status,
            'priority' => $request->priority ?? $ticket->priority,
            'respuesta_owner' => $request->respuesta_owner,
        ]);

        if ($request->has('redirect_back')) {
            return back()->with('success', 'Ticket actualizado con éxito.');
        }

        return redirect()->route('owner.soporte.index')->with('success', 'Ticket actualizado.');
    }

    public function uploadMedia(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Guardar en storage local (public disk)
            $path = $file->storeAs('tickets', $filename, 'public');
            
            // Usar la ruta local.media para máxima compatibilidad en Hostinger
            $url = route('local.media', ['path' => 'tickets/' . $filename]);
            
            return response()->json(['url' => $url]);
        }
        
        return response()->json(['error' => 'No se recibió ninguna imagen'], 400);
    }
}
