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
            'respuesta_owner' => 'nullable|string',
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $ticket->update([
            'status' => $request->status,
            'respuesta_owner' => $request->respuesta_owner,
        ]);

        return redirect()->route('owner.soporte.index')->with('success', 'Ticket actualizado.');
    }
}
