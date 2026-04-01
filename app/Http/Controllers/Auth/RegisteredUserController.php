<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create(Request $request)
    {
        $planId = $request->query('plan');
        $plan = \App\Models\Plan::find($planId);
        return view('auth.register', compact('plan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'role'              => 'empresa',
            'activo'            => 1,
            'email_verified_at' => now(),
        ]);

        // Guardamos el plan elegido en la sesión para el siguiente paso (Pago)
        if ($request->plan_id) {
            session(['selected_plan' => $request->plan_id]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('register.pay');
    }

    // 1. VISTA DE PAGO SEGURO (Simulación)
    public function paymentPage()
    {
        $planId = session('selected_plan');
        $plan = \App\Models\Plan::find($planId);
        return view('auth.onboarding.payment', compact('plan'));
    }

    // 2. PROCESAR PAGO
    public function processPayment(Request $request)
    {
        // Aquí iría la integración con MercadoPago o Stripe. 
        // Por ahora simulamos éxito inmediato.
        session(['payment_verified' => true]);
        return redirect()->route('register.company');
    }

    // 3. VISTA DATOS DE EMPRESA
    public function companyPage()
    {
        if (!session('payment_verified')) return redirect()->route('register.pay');
        return view('auth.onboarding.company');
    }

    // 4. CREACIÓN FINAL DE EMPRESA
    public function storeCompany(Request $request)
    {
        $request->validate([
            'nombre_comercial' => 'required|string|max:150',
            'cuit' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $planId = session('selected_plan', 1); // Default plan 1

        // Creamos la empresa con el "Punto 3" cumplido: 30 días de suscripción
        $empresa = \App\Models\Empresa::create([
            'nombre_comercial' => $request->nombre_comercial,
            'cuit' => $request->cuit,
            'plan_id' => $planId,
            'activo' => true,
            'fecha_vencimiento' => now()->addDays(30), // CICLO DINÁMICO
            'slug' => \Illuminate\Support\Str::slug($request->nombre_comercial),
        ]);

        // Vinculamos usuario a su nueva empresa
        $user->update(['empresa_id' => $empresa->id]);

        // Limpiamos sesión
        session()->forget(['selected_plan', 'payment_verified']);

        return redirect()->route('empresa.dashboard')->with('success', '¡Bienvenido a MultiPOS! Tu empresa ha sido activada.');
    }
}
