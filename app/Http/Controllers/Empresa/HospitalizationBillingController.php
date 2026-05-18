<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Hospitalization;
use App\Models\HospitalizationAdministration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HospitalizationBillingController extends Controller
{
    /**
     * Listado de facturación de internación.
     */
    public function index(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        $search = $request->input('search');

        $query = Hospitalization::where('empresa_id', $empresaId)
            ->with(['patient', 'assignments.bed.room', 'administrations']);

        if ($search) {
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('document', 'like', "%{$search}%");
            });
        }

        $hospitalizations = $query->orderBy('created_at', 'desc')->paginate(15);

        // Pre-calcular totales para cada una
        foreach ($hospitalizations as $h) {
            $h->calculated_total = $this->calculateTotalInvoiced($h);
        }

        return view('empresa.hospitalization.billing_index', compact('hospitalizations', 'search'));
    }

    /**
     * Mostrar liquidación renglón por renglón.
     */
    public function showReport($id)
    {
        $empresaId = Auth::user()->empresa_id;
        $hospitalization = Hospitalization::where('empresa_id', $empresaId)
            ->with(['patient.obraSocial', 'assignments.bed.room', 'administrations.user'])
            ->findOrFail($id);

        $lineasFacturacion = $this->buildBillingLines($hospitalization);
        $totalGeneral = collect($lineasFacturacion)->sum('subtotal');

        return view('empresa.hospitalization.billing_report', compact('hospitalization', 'lineasFacturacion', 'totalGeneral'));
    }

    /**
     * Exportar a Excel (CSV).
     */
    public function exportExcel($id)
    {
        $empresaId = Auth::user()->empresa_id;
        $hospitalization = Hospitalization::where('empresa_id', $empresaId)
            ->with(['patient', 'assignments.bed.room', 'administrations'])
            ->findOrFail($id);

        $lineasFacturacion = $this->buildBillingLines($hospitalization);
        $totalGeneral = collect($lineasFacturacion)->sum('subtotal');

        $filename = "facturacion_internacion_" . ($hospitalization->patient->document ?? $id) . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Fecha', 'Concepto', 'Tipo', 'Cantidad', 'Precio Unitario ($)', 'Subtotal ($)'];

        $callback = function() use($lineasFacturacion, $columns, $totalGeneral, $hospitalization) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Encabezado de la clínica
            fputcsv($file, ['REPORTE DE FACTURACION DE INTERNACION - MEDPLUS']);
            fputcsv($file, ['Paciente:', $hospitalization->patient->name]);
            fputcsv($file, ['DNI:', $hospitalization->patient->document]);
            fputcsv($file, ['Fecha Ingreso:', $hospitalization->fecha_ingreso]);
            fputcsv($file, ['Fecha Egreso:', $hospitalization->fecha_egreso ?? 'Activo']);
            fputcsv($file, []);

            // Columnas principales
            fputcsv($file, $columns);

            foreach ($lineasFacturacion as $linea) {
                fputcsv($file, [
                    $linea['fecha'],
                    $linea['concepto'],
                    $linea['tipo'],
                    $linea['cantidad'],
                    number_format($linea['precio_unitario'], 2, ',', ''),
                    number_format($linea['subtotal'], 2, ',', '')
                ]);
            }

            fputcsv($file, []);
            fputcsv($file, ['', '', '', '', 'TOTAL GENERAL:', number_format($totalGeneral, 2, ',', '')]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Construir las líneas de liquidación para el paciente.
     */
    private function buildBillingLines(Hospitalization $h)
    {
        $lineas = [];

        // 1. Días de Internación en Cama
        $ingreso = Carbon::parse($h->fecha_ingreso);
        $egreso = $h->fecha_egreso ? Carbon::parse($h->fecha_egreso) : now();
        $dias = $ingreso->diffInDays($egreso);
        if ($dias == 0) {
            $dias = 1; // Mínimo un día
        }

        $camaVal = 18500.00; // Valor diario base de internación clínica general
        $lineas[] = [
            'fecha' => $ingreso->format('Y-m-d'),
            'concepto' => "Días de Estancia Hospitalaria (Cama: " . ($h->currentBedAssignment?->bed?->nombre ?? 'Habitación') . ")",
            'tipo' => 'Estancia',
            'cantidad' => $dias,
            'precio_unitario' => $camaVal,
            'subtotal' => $dias * $camaVal
        ];

        // 2. Administraciones (Medicamentos, Descartables, Controles)
        foreach ($h->administrations as $admin) {
            if ($admin->fecha_registro) {
                $precio = 0.00;
                
                // Valores de liquidación por tipo si no tiene precio asignado
                if ($admin->precio_unitario > 0) {
                    $precio = $admin->precio_unitario;
                } else {
                    if ($admin->tipo === 'medicamento') {
                        $precio = 1250.00; // Fallback dipirona/analgésicos amp
                    } elseif ($admin->tipo === 'descartable') {
                        $precio = 480.00;  // Jeringas/gasas
                    } elseif ($admin->tipo === 'signos') {
                        $precio = 950.00;  // Valor módulo rutina de control de enfermería
                    } else {
                        $precio = 600.00;
                    }
                }

                $lineas[] = [
                    'fecha' => Carbon::parse($admin->fecha_registro)->format('Y-m-d'),
                    'concepto' => $admin->nombre . ($admin->dosis ? " (" . $admin->dosis . ")" : ""),
                    'tipo' => ucfirst($admin->tipo),
                    'cantidad' => $admin->cantidad ?? 1,
                    'precio_unitario' => $precio,
                    'subtotal' => ($admin->cantidad ?? 1) * $precio
                ];
            }
        }

        return $lineas;
    }

    /**
     * Calcular el total rápidamente.
     */
    private function calculateTotalInvoiced(Hospitalization $h)
    {
        return collect($this->buildBillingLines($h))->sum('subtotal');
    }
}
