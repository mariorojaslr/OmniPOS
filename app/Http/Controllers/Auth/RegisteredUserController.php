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
            'activo'            => 0, // Inactiva hasta validar pago/empresa
            'status'            => 'prospecto',
            'email_verified_at' => now(),
            'lead_source'       => session('lead_source', 'organic'),
            'country'           => 'AR', // Simplificado para este bloque, luego lo automatizamos
        ]);

        if ($request->plan_id) {
            session(['selected_plan' => $request->plan_id]);
            $user->update(['crm_notes' => 'Plan elegido al registro: ' . $request->plan_id]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('register.pay');
    }

    // 1. VISTA DE PAGO MANUAL (Instrucciones de Transferencia)
    public function paymentPage()
    {
        $user = auth()->user();
        if ($user->status === 'activo') return redirect()->route('empresa.dashboard');
        
        $planId = session('selected_plan', 1);
        $plan = \App\Models\Plan::find($planId);
        
        return view('auth.onboarding.payment', compact('plan', 'user'));
    }

    // 2. PROCESAR COMPROBANTE DE PAGO
    public function processPayment(Request $request)
    {
        $request->validate([
            'voucher' => 'required|image|max:5120', // Foto de transferencia
        ]);

        $user = auth()->user();
        
        if($request->hasFile('voucher')){
            $path = $request->file('voucher')->store('vouchers', 'public');
            $user->update([
                'status' => 'pendiente_pago',
                'payment_voucher' => $path
            ]);
        }

        return redirect()->route('register.pay')->with('success', '¡Recibido! Estamos validando tu pago. En breve recibirás un correo de activación.');
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

        // CREACIÓN DE RUBROS OPERATIVOS POR DEFECTO (Arquitectura Senior)
        $defaultRubros = [
            'Materia Prima',
            'Insumos de Proceso',
            'Artículos de Limpieza',
            'Packaging y Envases',
            'Papelería y Oficina'
        ];

        foreach ($defaultRubros as $nombre) {
            \App\Models\Rubro::create([
                'empresa_id' => $empresa->id,
                'nombre'     => $nombre,
                'activo'     => true
            ]);
        }

        // Limpiamos sesión
        session()->forget(['selected_plan', 'payment_verified']);

        return redirect()->route('empresa.dashboard')->with('success', '¡Bienvenido a MultiPOS! Tu empresa ha sido activada y configurada con rubros operativos.');
    }
}
