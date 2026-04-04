<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OwnerPayment;

class SuscripcionController extends Controller
{
    /**
     * Muestra el resumen de la suscripción y el historial de pagos.
     */
    public function index()
    {
        $empresa = auth()->user()->empresa;
        $pagos = OwnerPayment::where('empresa_id', $empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('empresa.suscripcion.index', compact('empresa', 'pagos'));
    }

    /**
     * Reportar un nuevo pago (Solo generará un ticket de venta en el owner o similar, por ahora mockeamos)
     * Si necesitas que el admin suba el comprobante, puedes guardar el archivo aquí y marcar el estado "Pendiente".
     * Por ahora, solo es una confirmación genérica o se los envía por WhatsApp.
     */
    public function reportPayment(Request $request)
    {
        return redirect()->back()->with('success', 'Pago reportado exitosamente. En breve será imputado a tu cuenta.');
    }
}
