<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPortalToken;
use App\Models\SupplierLedger;

class SupplierPortalController extends Controller
{
    public function index($token)
    {
        $portalToken = SupplierPortalToken::where('token', $token)->first();

        if (!$portalToken) {
            abort(404, 'Portal no encontrado.');
        }

        $supplier = $portalToken->supplier;
        $empresa = $supplier->empresa;
        $empresa->load('config');

        $movimientos = SupplierLedger::where('supplier_id', $supplier->id)
            ->where('type', 'debit')
            ->with(['imputaciones.ordenPago'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $saldo = $supplier->saldo();

        return view('supplier_portal.index', compact('supplier', 'empresa', 'movimientos', 'saldo'));
    }

    /**
     * Descargar PDF de Compra (si existe)
     */
    public function downloadInvoice($token, $id)
    {
        $portalToken = SupplierPortalToken::where('token', $token)->firstOrFail();
        $purchase = \App\Models\Purchase::with(['items.product', 'supplier', 'empresa.config'])
            ->where('id', $id)
            ->where('supplier_id', $portalToken->supplier_id)
            ->firstOrFail();

        // Generamos el PDF usando la plantilla de compras
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('empresa.purchases.show_pdf', [
            'purchase' => $purchase,
            'empresa' => $purchase->empresa,
            'items' => $purchase->items
        ]);

        return $pdf->download("Compra_{$purchase->id}.pdf");
    }

    /**
     * Descargar PDF de Orden de Pago
     */
    public function downloadPayment($token, $id)
    {
        $portalToken = SupplierPortalToken::where('token', $token)->firstOrFail();
        $orden = \App\Models\OrdenPago::with(['supplier', 'empresa.config', 'detalles'])
            ->where('id', $id)
            ->where('supplier_id', $portalToken->supplier_id)
            ->firstOrFail();

        $empresa = $orden->empresa;

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

        // Usamos la vista de impresión original (asumo que existe o se usa la de pagos genérica)
        // Nota: Si no existe una vista específica de PDF para OP, usamos la de pagos
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('empresa.pagos.print_op', compact('orden', 'empresa', 'logoBase64'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("OrdenPago_{$orden->numero_orden}.pdf");
    }
}
}
