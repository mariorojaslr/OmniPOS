<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            'products'      => 'Artículos y Stock',
            'ventas'        => 'Historial de Ventas',
            'expenses'      => 'Gastos y Egresos',
            'clients'       => 'Cartera de Clientes',
            'suppliers'     => 'Proveedores',
            'tesoreria_cuentas' => 'Cuentas Bancarias',
            'asistencias'   => 'Reloj de Personal'
        ];

        // Por simplicidad en este entorno, generaremos un CSV consolidado o redireccionaremos al tipo
        if ($type === 'sql' || $type === 'media') {
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

        return $response;
    }
}
