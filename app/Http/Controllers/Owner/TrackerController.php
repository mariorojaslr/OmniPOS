<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OwnerSystemTraffic;
use App\Models\OwnerCrmLead;
use Carbon\Carbon;

class TrackerController extends Controller
{
    /**
     * Endpoint para la Landing Page (Cuenta las visitas a la web)
     */
    public function trackVisit(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $traffic = OwnerSystemTraffic::firstOrCreate(['date' => $today]);
        $traffic->increment('landing_visits');

        return response()->json(['status' => 'tracked', 'visits' => $traffic->landing_visits]);
    }

    /**
     * Endpoint cuando hacen clic en "Probar Demo"
     */
    public function trackDemo(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $traffic = OwnerSystemTraffic::firstOrCreate(['date' => $today]);
        $traffic->increment('demo_clicks');

        return response()->json(['status' => 'tracked', 'demo_clicks' => $traffic->demo_clicks]);
    }

    /**
     * Webhook receptor de los Bots (LinkedIn/Meta AI)
     * Cuando el bot detecta un perfil interesado, envía un POST aquí.
     */
    public function receiveBotLead(Request $request)
    {
        // Validación básica
        if (!$request->has('name')) {
            return response()->json(['error' => 'Nombre requerido'], 400);
        }

        // Crear Lead
        $lead = new OwnerCrmLead();
        $lead->name = $request->input('name');
        $lead->company_name = $request->input('company', '');
        $lead->email = $request->input('email', '');
        $lead->phone = $request->input('phone', '');
        $lead->source = $request->input('source', 'bot_linkedin'); // origen
        $lead->status = 'nuevo';
        $lead->notes = $request->input('notes', '');
        $lead->save();

        // Monitoreo de Referidos en Tráfico
        $today = Carbon::today()->toDateString();
        $traffic = OwnerSystemTraffic::firstOrCreate(['date' => $today]);
        $traffic->increment('bot_referrals');

        return response()->json(['status' => 'lead_captured', 'lead_id' => $lead->id]);
    }
}
