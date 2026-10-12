<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        
        return view('owner.empresas.index', [
            'empresas' => Empresa::with('plan')
                ->withCount(['products', 'clients', 'ventas', 'productImages'])
                ->orderBy('nombre_comercial')
                ->paginate($perPage)
                ->appends(['per_page' => $perPage]), // Mantener el filtro al navegar
            'perPage' => $perPage
        ]);
    }

    public function create()
    {
        $planes = \App\Models\Plan::where('is_active', true)->get();
        return view('owner.empresas.create', compact('planes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_comercial'   => 'required|string|max:255',
            'email'              => 'required|email|unique:users,email',
            'telefono'           => 'nullable|string|max:50',
            'fecha_vencimiento'  => 'nullable|date',
            'password'           => 'nullable|string|min:6', // 👈 OPCIONAL
            'plan_id'            => 'nullable|exists:plans,id',
        ]);

        /*
        |----------------------------------------------------------
        | CREAR EMPRESA
        |----------------------------------------------------------
        */
        $empresa = Empresa::create([
            'nombre_comercial'  => $data['nombre_comercial'],
            'email'             => $data['email'],
            'telefono'          => $data['telefono'] ?? null,
            'fecha_vencimiento' => $data['fecha_vencimiento'] ?? null,
            'plan_id'           => $data['plan_id'] ?? null,
            'status'            => 'activa',
            'activo'            => true,
        ]);

        /*
        |----------------------------------------------------------
        | PASSWORD AUTOMÁTICA SEGURA
        |----------------------------------------------------------
        */
        $passwordPlano = $data['password'] ?? Str::random(10);

        /*
        |----------------------------------------------------------
        | CREAR USUARIO PRINCIPAL (ROL EMPRESA)
        |----------------------------------------------------------
        */
        User::create([
            'name'                   => $empresa->nombre_comercial,
            'email'                  => $empresa->email,
            'password'               => Hash::make($passwordPlano),
            'role'                   => 'empresa',
            'empresa_id'             => $empresa->id,
            'activo'                 => 1,
            'must_change_password'   => 1, // 👈 OBLIGAR CAMBIO EN PRIMER LOGIN
            'email_verified_at'      => now(),
        ]);

        /*
        |----------------------------------------------------------
        | CONFIGURACIÓN CLÍNICA INICIAL
        |----------------------------------------------------------
        */
        $tipoClinica = $request->input('tipo_clinica', 'clinica_general');
        $isOdonto = ($tipoClinica === 'clinica_odontologica') || $request->has('mod_odontograma');
        
        $empresa->config()->create([
            'color_primary'        => '#1f6feb',
            'color_secondary'      => '#0d1117',
            'theme'                => 'light',
            'tipo_clinica'         => $tipoClinica,
            'mod_odontograma'      => $isOdonto,
            'mod_internaciones'    => $isOdonto ? false : $request->has('mod_internaciones'),
            'mod_turnos'           => true,
            'mod_hce'              => true,
            'terminologia_espacio' => $isOdonto ? 'consultorio' : 'consultorio',
        ]);

        /*
        |----------------------------------------------------------
        | MENSAJE CON PASSWORD GENERADA (SOLO SI FUE AUTOMÁTICA)
        |----------------------------------------------------------
        */
        $msg = 'Empresa creada correctamente.';

        if (!isset($data['password'])) {
            $msg .= ' Password inicial generada: ' . $passwordPlano;
        }

        return redirect()
            ->route('owner.empresas.index')
            ->with('success', $msg);
    }

    /**
     * Editar empresa - Usa $empresaId (entero) para evitar conflicto con slug binding
     */
    public function edit($empresaId)
    {
        $empresa = Empresa::with('config')->findOrFail($empresaId);
        $planes = \App\Models\Plan::where('is_active', true)->get();
        return view('owner.empresas.edit', compact('empresa', 'planes'));
    }

    public function update(Request $request, $empresaId)
    {
        $empresa = Empresa::findOrFail($empresaId);

        $data = $request->validate([
            'nombre_comercial'  => 'required|string|max:255',
            'email'             => 'nullable|email',
            'telefono'          => 'nullable|string|max:50',
            'fecha_vencimiento' => 'nullable|date',
            'plan_id'           => 'nullable|exists:plans,id',
            'status'            => 'required|string|in:activa,suspendida,mora',
            'custom_price'        => 'nullable|numeric',
            'custom_max_products' => 'nullable|integer',
            'grace_period_until'  => 'nullable|date',
            'is_bonificated'      => 'nullable',
            
            // Configuración clínica
            'tipo_clinica'        => 'nullable|string|in:clinica_general,clinica_odontologica,unidad_coronaria,centro_rehabilitacion',
            'terminologia_espacio'=> 'nullable|string|in:consultorio,sillon,box,sala',
        ]);

        $data['is_bonificated'] = $request->has('is_bonificated');

        $empresa->update($data);

        // Actualizar o crear EmpresaConfig con las llaves de salud
        $tipoClinica = $request->input('tipo_clinica', 'clinica_general');
        $isOdonto = ($tipoClinica === 'clinica_odontologica') || $request->has('mod_odontograma');

        $configData = [
            'tipo_clinica'         => $tipoClinica,
            'mod_odontograma'      => $isOdonto,
            'mod_turnos'           => $request->has('mod_turnos'),
            'mod_hce'              => $request->has('mod_hce'),
            'terminologia_espacio' => $request->input('terminologia_espacio', $isOdonto ? 'consultorio' : 'consultorio'),
        ];

        // Si es odontología y no se forzó internación, apagar internación automáticamente
        if ($isOdonto && !$request->has('mod_internaciones')) {
            $configData['mod_internaciones'] = false;
        } else {
            $configData['mod_internaciones'] = $request->has('mod_internaciones');
        }

        $empresa->config()->updateOrCreate(
            ['empresa_id' => $empresa->id],
            $configData
        );

        return redirect()
            ->route('owner.empresas.index')
            ->with('success', 'Empresa y configuración clínica actualizadas correctamente');
    }

    /**
     * ACTIVAR / DESACTIVAR EMPRESA
     */
    public function toggleStatus($empresaId): RedirectResponse
    {
        $empresa = Empresa::findOrFail($empresaId);
        $empresa->update([
            'activo' => ! $empresa->activo,
        ]);

        return back()->with('success', 'Estado de la empresa actualizado');
    }

    /**
     * RENOVAR EMPRESA
     */
    public function renovar($empresaId): RedirectResponse
    {
        $empresa = Empresa::findOrFail($empresaId);
        $empresa->renovar(30);

        return back()->with('success', 'Empresa renovada por 30 días');
    }

    /**
     * MIMETIZACIÓN (ENTRAR COMO USUARIO)
     */
    public function impersonate($empresaId, User $user): RedirectResponse
    {
        $empresa = Empresa::findOrFail($empresaId);

        // Verificar que el usuario pertenezca a la empresa
        if ($user->empresa_id !== $empresa->id) {
            return back()->with('error', 'El usuario no pertenece a esta empresa.');
        }

        // Guardar el ID del Owner original para poder volver
        session(['impersonator_id' => auth()->id()]);
        
        // Loguearse como el usuario de la empresa
        \Illuminate\Support\Facades\Auth::login($user);
        
        return redirect()->route('empresa.dashboard')->with('info', 'Modo Mimetización: Estás viendo la plataforma como ' . $user->name);
    }

    /**
     * SANADOR DE BASE DE DATOS (EMERGENCIA)
     */
    public function healDatabase()
    {
        try {
            \Illuminate\Support\Facades\Schema::table('empresas', function (\Illuminate\Database\Schema\Blueprint $table) {
                if (!\Illuminate\Support\Facades\Schema::hasColumn('empresas', 'custom_price')) {
                    $table->decimal('custom_price', 10, 2)->nullable()->after('plan_id');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('empresas', 'custom_max_products')) {
                    $table->integer('custom_max_products')->nullable()->after('custom_price');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('empresas', 'custom_max_users')) {
                    $table->integer('custom_max_users')->nullable()->after('custom_max_products');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('empresas', 'custom_max_storage_mb')) {
                    $table->decimal('custom_max_storage_mb', 10, 2)->nullable()->after('custom_max_users');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('empresas', 'is_bonificated')) {
                    $table->boolean('is_bonificated')->default(false)->after('custom_max_storage_mb');
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('empresas', 'grace_period_until')) {
                    $table->date('grace_period_until')->nullable()->after('is_bonificated');
                }
            });

            // LOG DE ACTIVIDAD
            \App\Models\ActivityLog::log("Healer: Base de Datos de Empresas sanada manualmente vía navegador.");

            return "✅ Base de Datos de Empresas sanada con éxito. Ya podés editar la empresa.";
        } catch (\Exception $e) {
            return "❌ Error al sanar: " . $e->getMessage();
        }
    }
}
