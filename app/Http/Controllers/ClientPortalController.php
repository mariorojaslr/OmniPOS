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
        $portalToken = ClientPortalToken::with(['client.empresa.config'])->where('token', $token)->first();

        if (!$portalToken) {
            abort(404, 'Portal no encontrado.');
        }

        $client = $portalToken->client;
        $empresa = $client->empresa;

        $movimientos = ClientLedger::where('client_id', $client->id)
            ->where('type', 'debit')
            ->with(['reference', 'imputaciones.recibo'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $saldo = $client->saldo();

        return view('client_portal.index', compact('client', 'empresa', 'movimientos', 'saldo'));
    }

    /**
     * Descargar PDF de Factura desde el Portal
     */
    public function downloadInvoice($token, $id)
    {
        $portalToken = ClientPortalToken::where('token', $token)->firstOrFail();
        $venta = Venta::with(['items.product', 'items.variant', 'cliente', 'empresa.config', 'user'])
            ->where('id', $id)
            ->where('client_id', $portalToken->client_id)
            ->firstOrFail();

        $empresa = $venta->empresa;

        // Lógica de Logo idéntica a VentaController
        $logoBase64 = null;
        if ($empresa->config && $empresa->config->logo) {
            try {
                $path = storage_path('app/public/' . $empresa->config->logo);
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            } catch (\Exception $e) {}
        }

        // Logo de ARCA (AFIP) Genérico Profesional
        $arcaLogoBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKAAAABACAMAAAC9G97XAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAnUExURQAAAD8/Pz8/Pz8/Pz8/Pz8/Pz8/Pz8/Pz8/Pz8/Pz8/Pz8/Pz8/P8fofp8AAAAMdFJOUwBAgMDBwYGBw8PDxG6mXgAAAAlwSFlzAAAWJQAAFiUBSVIk8AAAAO5JREFUaN7t2DsSgzAMRNE3YDBYIAnv/9YSUuYFfI9RVsirG9m/vE6O67ou77H+3Pee96Z5Zrqv5/7G8+z5/uO7++Y6f2yZp/z6+f3j+v7o/P9i/n8x/7+Y/1/M/y/m/xfz/4v5/8X8/2L+fzH/v5j/X8z/L+b/F/P/i/n/xfz/Yv5/Mf+/mP9fzP8v5v8X8/2L+f/F/P9i/n8x/7+Y/1/M/y/m/xfz/4v5/8X8/2L+fzH/v5j/X8z/L+b/F/P/i/n/xfz/Yv5/Mf+/mP9fzP8v5v8X8/2L+f/F/P9i/n8x/7+Y/1/M/y/m/xfz/4v5/8X8/2L+fzH/v5j93N/9AL/Dclv4I/fBAAAAAElFTkSuQmCC'; 

        $qrUrl = null;
        if ($venta->cae) {
            $qrData = [
                "ver" => 1, "fecha" => $venta->created_at->format('Y-m-d'),
                "cuit" => (int) str_replace('-', '', $empresa->arca_cuit ?? $empresa->cuit),
                "ptoVta" => (int) ($empresa->arca_punto_venta ?? 1),
                "tipoCbte" => (int) (($empresa->condicion_iva === 'Monotributista') ? 11 : (($venta->tipo_comprobante === 'A') ? 1 : 6)),
                "nroCbte" => (int) substr($venta->numero_comprobante, -8),
                "importe" => (float) $venta->total_con_iva, "moneda" => "PES", "ctz" => 1,
                "tipoDocRec" => (int) ($venta->cliente && strlen(str_replace('-', '', $venta->cliente->document)) > 8 ? 80 : 96),
                "nroDocRec" => (int) str_replace('-', '', $venta->cliente->document ?? 0),
                "tipoCodAut" => "E", "codAut" => (float) $venta->cae
            ];
            $qrUrl = "https://www.afip.gob.ar/fe/qr/?p=" . base64_encode(json_encode($qrData));
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.comprobante_venta', compact('venta', 'empresa', 'logoBase64', 'arcaLogoBase64', 'qrUrl'))
            ->setPaper('a4', 'portrait');

        return $pdf->download(($venta->numero_comprobante ?: 'Venta_'.$venta->id) . '.pdf');
    }

    /**
     * Descargar PDF de Recibo desde el Portal
     */
    public function downloadReceipt($token, $id)
    {
        $portalToken = ClientPortalToken::where('token', $token)->firstOrFail();
        $recibo = \App\Models\Recibo::with(['client', 'pagos', 'user', 'empresa.config'])
            ->where('id', $id)
            ->where('client_id', $portalToken->client_id)
            ->firstOrFail();

        $empresa = $recibo->empresa;

        // Lógica de Logo idéntica
        $logoBase64 = null;
        if ($empresa->config && $empresa->config->logo) {
            try {
                $path = storage_path('app/public/' . $empresa->config->logo);
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            } catch (\Exception $e) {}
        }

        // Usamos la vista de impresión original
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('empresa.pagos.print', compact('recibo', 'empresa', 'logoBase64'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("Recibo_{$recibo->numero_recibo}.pdf");
    }

    /**
     * Procesar el inicio del pago (Simulador o Pasarela)
     */
    public function payInvoice($token, $id)
    {
        $portalToken = ClientPortalToken::where('token', $token)->firstOrFail();
        $venta = Venta::where('id', $id)
            ->where('client_id', $portalToken->client_id)
            ->firstOrFail();

        if ($venta->paid) {
            return back()->with('info', 'Esta factura ya se encuentra pagada.');
        }

        $empresa = $venta->empresa;
        $client = $venta->cliente;

        // Aquí es donde iría la lógica de Mercado Pago o Stripe
        // Por ahora, devolvemos una vista de simulador para verificar que todo está ok
        return view('client_portal.pay_simulator', compact('venta', 'empresa', 'client', 'token'));
    }
}
