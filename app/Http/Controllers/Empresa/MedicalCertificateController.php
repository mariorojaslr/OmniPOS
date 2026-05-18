<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\MedicalCertificate;
use App\Models\B2bConvention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalCertificateController extends Controller
{
    /**
     * Listado de certificados emitidos.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $empresaId = $user->empresa_id;
        $search = $request->input('search');

        if ($user->role === 'owner' && !$empresaId) {
            // El Owner tiene visión omnisciente si entra directamente sin mimetizar
            $query = MedicalCertificate::with(['patient', 'doctor', 'empresa']);
        } else {
            $query = MedicalCertificate::where('empresa_id', $empresaId)
                ->with(['patient', 'doctor']);
        }

        if ($search) {
            $query->where(function($mainQ) use ($search) {
                $mainQ->whereHas('patient', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('document', 'like', "%{$search}%");
                })->orWhere('codigo_validacion', 'like', "%{$search}%");
            });
        }

        $certificates = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('empresa.certificados.index', compact('certificates', 'search'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        $user = Auth::user();
        $empresaId = $user->empresa_id;
        
        if ($user->role === 'owner' && !$empresaId) {
            $patients = Client::orderBy('name')->get();
            $doctors = User::orderBy('name')->get();
            $conventions = B2bConvention::where('activo', true)->orderBy('nombre')->get();
        } else {
            $patients = Client::where('empresa_id', $empresaId)->orderBy('name')->get();
            $doctors = User::where('empresa_id', $empresaId)->orderBy('name')->get();
            $conventions = B2bConvention::where('empresa_id', $empresaId)->where('activo', true)->orderBy('nombre')->get();
        }

        return view('empresa.certificados.create', compact('patients', 'doctors', 'conventions'));
    }

    /**
     * Almacenar un nuevo certificado.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'user_id' => 'required|exists:users,id',
            'b2b_convention_id' => 'nullable|exists:b2b_conventions,id',
            'tipo' => 'required|string|max:191',
            'contenido' => 'required|string',
            'vigencia_hasta' => 'nullable|date',
        ]);

        $user = Auth::user();
        $empresaId = $user->empresa_id;
        
        if ($user->role === 'owner' && !$empresaId) {
            // Si el Owner crea directamente, asocia a la empresa del paciente seleccionado
            $patient = Client::findOrFail($request->client_id);
            $empresaId = $patient->empresa_id;
        }

        // Generación de código único seguro de validación MedPlus
        $codigoValidacion = 'MP-CERT-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

        MedicalCertificate::create([
            'empresa_id' => $empresaId,
            'client_id' => $request->client_id,
            'user_id' => $request->user_id,
            'b2b_convention_id' => $request->b2b_convention_id,
            'codigo_validacion' => $codigoValidacion,
            'tipo' => $request->tipo,
            'contenido' => $request->contenido,
            'vigencia_hasta' => $request->vigencia_hasta,
        ]);

        return redirect()->route('empresa.certificados.index')->with('success', 'Certificado Oficial emitido correctamente y firmado digitalmente con código QR.');
    }

    /**
     * Impresión física del certificado con código QR.
     */
    public function print($id)
    {
        $user = Auth::user();
        $empresaId = $user->empresa_id;
        
        $query = MedicalCertificate::with(['patient', 'doctor.profesionalConfig', 'empresa']);
        
        if ($user->role !== 'owner' || $empresaId) {
            $query->where('empresa_id', $empresaId);
        }

        $certificate = $query->findOrFail($id);

        // Generar URL de validación pública de forma dinámica para que funcione con cualquier IP o dominio local/remoto
        $validationUrl = request()->getSchemeAndHttpHost() . '/v/certificado/' . $certificate->codigo_validacion;

        return view('empresa.certificados.print', compact('certificate', 'validationUrl'));
    }

    /**
     * Public validation
     */
    public function validatePublic($codigo)
    {
        $certificate = MedicalCertificate::where('codigo_validacion', $codigo)
            ->with(['patient', 'doctor.profesionalConfig', 'empresa'])
            ->firstOrFail();

        return view('empresa.certificados.public_validate', compact('certificate'));
    }
}
