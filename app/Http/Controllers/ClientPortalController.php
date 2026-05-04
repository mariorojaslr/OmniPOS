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

        // Listado principal: Solo Facturas (debits)
        $movimientos = ClientLedger::where('client_id', $client->id)
            ->where('type', 'debit')
            ->with(['reference', 'imputaciones.recibo'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // El saldo total sigue siendo el mismo (deuda real)
        $saldo = $client->saldo();

        return view('client_portal.index', compact('client', 'empresa', 'movimientos', 'saldo'));
    }

    /**
     * Descargar PDF de Factura desde el Portal
     */
    public function downloadInvoice($token, $id)
    {
        $portalToken = ClientPortalToken::where('token', $token)->firstOrFail();
        $venta = Venta::where('id', $id)->where('client_id', $portalToken->client_id)->firstOrFail();

        // Usamos el controlador de ventas para generar el PDF (reutilización de lógica)
        return app(\App\Http\Controllers\Empresa\VentaController::class)->pdf($venta->id);
    }

    /**
     * Descargar PDF de Recibo desde el Portal
     */
    public function downloadReceipt($token, $id)
    {
        $portalToken = ClientPortalToken::where('token', $token)->firstOrFail();
        $recibo = \App\Models\Recibo::where('id', $id)->where('client_id', $portalToken->client_id)->firstOrFail();

        return app(\App\Http\Controllers\Empresa\ReciboController::class)->print($recibo->id);
    }
}
