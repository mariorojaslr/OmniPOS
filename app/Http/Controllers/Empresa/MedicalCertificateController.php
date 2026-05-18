<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\MedicalCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalCertificateController extends Controller
{
    /**
     * Listado de certificados emitidos.
     */
    public function index(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        $search = $request->input('search');

        $query = MedicalCertificate::where('empresa_id', $empresaId)
            ->with(['patient', 'doctor']);

        if ($search) {
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('document', 'like', "%{$search}%");
            })->orWhere('codigo_validacion', 'like', "%{$search}%");
        }

        $certificates = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('empresa.certificados.index', compact('certificates', 'search'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        $empresaId = Auth::user()->empresa_id;
        
        $patients = Client::where('empresa_id', $empresaId)->orderBy('name')->get();
        
        // Cargar usuarios de la empresa que actúan como médicos (pueden ser empresa o usuario)
        $doctors = User::where('empresa_id', $empresaId)
            ->orderBy('name')
            ->get();

        return view('empresa.certificados.create', compact('patients', 'doctors'));
    }

    /**
     * Almacenar un nuevo certificado.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'user_id' => 'required|exists:users,id',
            'tipo' => 'required|string|max:191',
            'contenido' => 'required|string',
            'vigencia_hasta' => 'nullable|date',
        ]);

        $empresaId = Auth::user()->empresa_id;
        
        // Generación de código único seguro de validación MedPlus
        $codigoValidacion = 'MP-CERT-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

        MedicalCertificate::create([
            'empresa_id' => $empresaId,
            'client_id' => $request->client_id,
            'user_id' => $request->user_id,
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
        $empresaId = Auth::user()->empresa_id;
        $certificate = MedicalCertificate::where('empresa_id', $empresaId)
            ->with(['patient', 'doctor.profesionalConfig', 'empresa'])
            ->findOrFail($id);

        // Generar URL de validación pública
        $validationUrl = route('certificados.public-validate', $certificate->codigo_validacion);

        return view('empresa.certificados.print', compact('certificate', 'validationUrl'));
    }

    /**
     * Validación pública y no autenticada para autoridades que escanean el QR.
     */
    public function validatePublic($codigo)
    {
        $certificate = MedicalCertificate::where('codigo_validacion', $codigo)
            ->with(['patient', 'doctor.profesionalConfig', 'empresa'])
            ->firstOrFail();

        return view('empresa.certificados.public_validate', compact('certificate'));
    }
}
