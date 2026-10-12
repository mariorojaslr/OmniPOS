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
        
        if (!$client) {
            abort(404, 'Cliente no encontrado.');
        }

        $empresa = $client->empresa;

        $movimientos = ClientLedger::where('client_id', $client->id)
            ->where('type', 'debit')
            ->with(['imputaciones.recibo'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $saldo = $client->saldo();

        return view('client_portal.index', compact('client', 'empresa', 'movimientos', 'saldo', 'token'));
    }

    /**
     * Portal Clínico, de Turnos y Obligaciones del Paciente
     */
    public function healthPortal($token)
    {
        $portalToken = ClientPortalToken::with(['client.empresa.config'])->where('token', $token)->first();

        if (!$portalToken) {
            abort(404, 'Portal de turnos no encontrado.');
        }

        $client = $portalToken->client;
        if (!$client) {
            abort(404, 'Paciente no encontrado.');
        }

        $empresa = $client->empresa;
        $config = $empresa->config;

        // Próximos turnos y turnos pasados
        $proximosTurnos = \App\Models\Turno::where('client_id', $client->id)
            ->where('empresa_id', $empresa->id)
            ->whereDate('fecha', '>=', now()->toDateString())
            ->with(['servicio', 'user'])
            ->orderBy('fecha', 'asc')
            ->orderBy('hora_inicio', 'asc')
            ->get();

        $historialTurnos = \App\Models\Turno::where('client_id', $client->id)
            ->where('empresa_id', $empresa->id)
            ->whereDate('fecha', '<', now()->toDateString())
            ->with(['servicio', 'user'])
            ->orderBy('fecha', 'desc')
            ->limit(10)
            ->get();

        // Obligaciones / Cuotas / Pagos pendientes
        $saldoPendiente = $client->saldo();
        $comprobantesPendientes = ClientLedger::where('client_id', $client->id)
            ->where('type', 'debit')
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function($mov) {
                return $mov->saldo_pendiente > 0.01;
            });

        // Enlace de WhatsApp directo con la clínica para asistencia
        $clinicPhone = preg_replace('/[^0-9]/', '', $empresa->telefono ?? '');
        if (strlen($clinicPhone) === 10) $clinicPhone = '549' . $clinicPhone;
        $whatsappUrl = !empty($clinicPhone) ? 'https://api.whatsapp.com/send?phone=' . $clinicPhone . '&text=' . urlencode("Hola {$empresa->nombre_comercial}, soy {$client->name} (DNI {$client->document}) y tengo una consulta sobre mis turnos / estado de cuenta.") : null;

        return view('client_portal.health_portal', compact(
            'client',
            'empresa',
            'config',
            'token',
            'proximosTurnos',
            'historialTurnos',
            'saldoPendiente',
            'comprobantesPendientes',
            'whatsappUrl'
        ));
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
