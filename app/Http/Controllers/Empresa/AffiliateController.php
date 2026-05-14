<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\AffiliateFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliateController extends Controller
{
    public function index()
    {
        $empresaId = Auth::user()->empresa_id;
        $affiliates = Client::where('empresa_id', $empresaId)
            ->where('is_affiliate', true)
            ->paginate(15);

        return view('empresa.affiliates.index', compact('affiliates'));
    }

    public function generateFees(Request $request)
    {
        $request->validate([
            'period' => 'required|string', // Ej: 2026-06
            'due_date' => 'required|date',
        ]);

        $empresaId = Auth::user()->empresa_id;
        $affiliates = Client::where('empresa_id', $empresaId)
            ->where('is_affiliate', true)
            ->where('affiliate_status', 'active')
            ->get();

        $count = 0;
        foreach ($affiliates as $aff) {
            // Evitar duplicados para el mismo periodo
            $exists = AffiliateFee::where('client_id', $aff->id)
                ->where('period', $request->period)
                ->exists();

            if (!$exists) {
                AffiliateFee::create([
                    'empresa_id' => $empresaId,
                    'client_id' => $aff->id,
                    'period' => $request->period,
                    'amount' => $aff->monthly_fee,
                    'due_date' => $request->due_date,
                    'status' => 'pending',
                ]);
                $count++;
            }
        }

        return redirect()->back()->with('success', "Se generaron $count cuotas para el periodo {$request->period}.");
    }

    public function accountStatement(Client $client)
    {
        $fees = AffiliateFee::where('client_id', $client->id)
            ->where('empresa_id', Auth::user()->empresa_id)
            ->orderByDesc('period')
            ->get();

        return view('empresa.affiliates.account_statement', compact('client', 'fees'));
    }
}
