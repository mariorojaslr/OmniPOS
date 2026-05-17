<?php

namespace App\Http\Controllers\Revendedor;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmpresaController extends Controller
{
    public function create()
    {
        $planes = Plan::where('activo', true)->get();
        return view('revendedor.empresas.create', compact('planes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_comercial' => 'required|string|max:255',
            'email_admin' => 'required|email|unique:users,email',
            'plan_id' => 'required|exists:planes,id',
            'custom_price' => 'required|numeric|min:0',
        ]);

        $plan = Plan::findOrFail($request->plan_id);

        if ($request->custom_price < $plan->price) {
            return back()->withErrors(['custom_price' => 'El precio acordado no puede ser menor al precio mínimo del plan ($' . number_format($plan->price) . ')'])
                         ->withInput();
        }

        // 1. Crear la Empresa vinculada al revendedor
        $empresa = Empresa::create([
            'nombre_comercial' => $request->nombre_comercial,
            'slug' => Str::slug($request->nombre_comercial),
            'reseller_id' => auth()->id(),
            'plan_id' => $request->plan_id,
            'custom_price' => $request->custom_price,
            'activo' => true,
            'status' => 'activo'
        ]);

        // 2. Crear el Usuario Administrador de esa empresa
        $password = Str::random(10);
        User::create([
            'name' => 'Admin ' . $request->nombre_comercial,
            'email' => $request->email_admin,
            'password' => Hash::make($password),
            'role' => 'empresa',
            'empresa_id' => $empresa->id,
            'activo' => true,
            'status' => 'activo'
        ]);

        return redirect()->route('revendedor.dashboard')->with('success', "Empresa creada. Contraseña temporal enviada a {$request->email_admin}: $password");
    }
}
