<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;
use Exception;

class BackupController extends Controller
{
    /**
     * Muestra la Bóveda de Resguardo de la Empresa.
     */
    public function index()
    {
        $empresa = Auth::user()->empresa;
        return view('empresa.backup.index', compact('empresa'));
    }

    /**
     * Genera y descarga un resguardo de datos en formato CSV.
     */
    public function download(Request $request)
    {
        $empresa = Auth::user()->empresa;
        $type = $request->query('type', 'sql');

        // Tablas que contienen información sensible de la empresa
        $tables = [
            'products'          => 'Artículos y Stock',
            'product_variants'  => 'Variantes de Productos',
            'ventas'            => 'Historial de Ventas',
            'venta_items'       => 'Detalle de Ventas',
            'expenses'          => 'Gastos y Egresos',
            'clients'           => 'Cartera de Clientes',
            'suppliers'         => 'Proveedores',
            'finanzas_cuentas'  => 'Cuentas Bancarias/Cajas',
            'finanzas_movimientos' => 'Movimientos de Tesorería',
            'asistencias'       => 'Reloj de Personal',
            'purchases'         => 'Compras a Proveedores',
            'client_ledgers'    => 'Cuentas Corrientes Clientes',
            'supplier_ledgers'  => 'Cuentas Corrientes Proveedores'
        ];

        // Por simplicidad en este entorno, generaremos un CSV consolidado o redireccionaremos al tipo
        if ($type === 'sql' || $type === 'media' || $type === 'tokens') {
            return $this->exportToCsv($empresa, $tables);
        }

        return redirect()->back()->with('error', 'Tipo de resguardo no soportado actualmente.');
    }

    private function exportToCsv($empresa, $tables)
    {
        $fileName = 'backup_' . str_replace(' ', '_', strtolower($empresa->nombre_comercial)) . '_' . date('d-m-Y') . '.csv';

        $response = new StreamedResponse(function () use ($empresa, $tables) {
            $handle = fopen('php://output', 'w');
            
            // Añadir BOM para visualización correcta en Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            foreach ($tables as $tableName => $label) {
                if (!Schema::hasTable($tableName)) continue;

                // Separador de sección
                fputcsv($handle, ["--- SECCIÓN: $label ---"]);

                $query = DB::table($tableName)->where('empresa_id', $empresa->id);
                
                // Obtener columnas
                $columns = Schema::getColumnListing($tableName);
                fputcsv($handle, $columns);

                $query->chunk(100, function ($rows) use ($handle, $columns) {
                    foreach ($rows as $row) {
                        $data = [];
                        foreach ($columns as $column) {
                            $data[] = $row->{$column} ?? '';
                        }
                        fputcsv($handle, $data);
                    }
                });

                fputcsv($handle, []); // Línea vacía entre tablas
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);

        ActivityLog::log("Generó un resguardo de datos en CSV");

        return $response;
    }
}
