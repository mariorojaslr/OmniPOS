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
            ->with(['imputaciones.ordenPago'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $saldo = $supplier->saldo();

        return view('supplier_portal.index', compact('supplier', 'empresa', 'movimientos', 'saldo'));
    }
}
