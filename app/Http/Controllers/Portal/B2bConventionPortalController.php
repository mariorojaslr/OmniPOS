<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\B2bConvention;
use App\Models\MedicalCertificate;
use Illuminate\Http\Request;

/**
 * Clase B2bConventionPortalController
 * Controla el acceso de las municipalidades y agencias a la búsqueda segura de certificados por DNI.
 */
class B2bConventionPortalController extends Controller
{
    /**
     * Mostrar panel / dashboard del portal B2B para la agencia externa específica.
     */
    public function showPortal($token)
    {
        // Encontrar la agencia asociada al convenio que esté activo
        $convention = B2bConvention::where('token_acceso', $token)
            ->where('activo', true)
            ->firstOrFail();

        // Obtener la clínica/empresa dueña del inquilino
        $empresa = $convention->empresa;

        return view('portales.b2b_convention.dashboard', compact('convention', 'empresa'));
    }

    /**
     * Búsqueda en tiempo real (vía fetch AJAX) de certificados del paciente por su DNI.
     */
    public function searchCertificates(Request $request, $token)
    {
        // Validar que la agencia esté activa
        $convention = B2bConvention::where('token_acceso', $token)
            ->where('activo', true)
            ->firstOrFail();

        $dni = trim($request->input('document'));

        if (empty($dni)) {
            return response()->json([
                'success' => false,
                'message' => 'Debe ingresar un número de documento válido para consultar.'
            ]);
        }

        // Consultar los certificados emitidos en este inquilino para el DNI del paciente
        $certificates = MedicalCertificate::where('empresa_id', $convention->empresa_id)
            ->whereHas('patient', function ($query) use ($dni) {
                $query->where('document', $dni);
            })
            ->with(['patient', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($certificates->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No se encontraron certificados médicos emitidos para el DNI ingresado.',
                'certificates' => []
            ]);
        }

        // Mapear los certificados para la respuesta segura
        $data = $certificates->map(function ($cert) {
            $esVigente = true;
            if ($cert->vigencia_hasta) {
                $esVigente = $cert->vigencia_hasta->isFuture() || $cert->vigencia_hasta->isToday();
            }

            return [
                'id' => $cert->id,
                'codigo' => $cert->codigo_validacion,
                'tipo' => $cert->tipo,
                'fecha_emision' => $cert->created_at->format('d/m/Y H:i'),
                'vigencia' => $cert->vigencia_hasta ? $cert->vigencia_hasta->format('d/m/Y') : 'Permanente / Permanente',
                'es_vigente' => $esVigente,
                'contenido' => strip_tags($cert->contenido),
                'paciente_nombre' => $cert->patient?->name ?? 'Paciente Registrado',
                'paciente_documento' => $cert->patient?->document ?? 'Sin Documento',
                'doctor_nombre' => $cert->doctor?->name ?? 'Médico Firmante',
                'doctor_matricula' => $cert->doctor?->matricula ?? 'M.P. No Informada',
                'doctor_especialidad' => $cert->doctor?->especialidad ?? 'Especialista',
                'download_url' => route('empresa.certificados.print', $cert->id)
            ];
        });

        return response()->json([
            'success' => true,
            'certificates' => $data
        ]);
    }
}
