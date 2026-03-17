<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;

class SupportTicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::where('empresa_id', auth()->user()->empresa_id)
            ->orderByDesc('created_at')
            ->get();
        return view('empresa.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('empresa.tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:baja,media,alta',
        ]);

        SupportTicket::create([
            'empresa_id' => auth()->user()->empresa_id,
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority,
            'status' => 'abierto',
        ]);

        return redirect()->route('empresa.soporte.index')->with('success', 'Ticket de soporte enviado correctamente.');
    }

    public function show($id)
    {
        $ticket = SupportTicket::where('empresa_id', auth()->user()->empresa_id)
            ->findOrFail($id);
        return view('empresa.tickets.show', compact('ticket'));
    }

    public function uploadMedia(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Guardar en storage local
            $path = $file->storeAs('public/tickets', $filename);
            $url = asset('storage/tickets/' . $filename);
            
            return response()->json(['url' => $url]);
        }
        
        return response()->json(['error' => 'No se recibió ninguna imagen'], 400);
    }
}
