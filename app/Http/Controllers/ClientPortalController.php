<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientPortalToken;
use App\Models\Client;
use App\Models\Venta;
use App\Models\ClientLedger;
use Illuminate\Support\Facades\DB;

class ClientPortalController extends Controller
{
    /**
     * Vista pública del Portal del Cliente
     */
    public function index($token)
    {
        $portalToken = ClientPortalToken::where('token', $token)->first();

        if (!$portalToken) {
            abort(404, 'Portal no encontrado o enlace caducado.');
        }

        $client = $portalToken->client;
        $empresa = $client->empresa;
        $empresa->load('config');

        // Movimientos de cuenta corriente
        $movimientos = ClientLedger::where('client_id', $client->id)
            ->with(['reference', 'imputaciones.recibo'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Deudas pendientes para el botón de "Pagar" o resumen
        $deudas = ClientLedger::where('client_id', $client->id)
            ->where('type', 'debit')
            ->where('paid', 0)
            ->get();

        $saldo = $client->saldo();

        return view('client_portal.index', compact('client', 'empresa', 'movimientos', 'deudas', 'saldo'));
    }
}
