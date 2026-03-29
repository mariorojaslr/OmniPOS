<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\BelongsToEmpresa;

class LabelController extends Controller
{
    use BelongsToEmpresa;

    public function index()
    {
        $products = Product::where('barcode', '!=', '')
                           ->whereNotNull('barcode')
                           ->orderBy('name')
                           ->get();

        return view('empresa.labels.index', compact('products'));
    }

    /**
     * Generación de etiquetas OLED premium
     */
    public function generate(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'format' => 'required|in:small,medium,large'
        ]);

        $generator = new BarcodeGeneratorPNG();
        $labels = [];

        // Definición de capacidad por hoja A4 para cada formato
        // Basado en márgenes estándar y tamaños aproximados
        $pageCapacity = [
            'small'  => 65, // 5 columnas x 13 filas (aprox)
            'medium' => 24, // 3 columnas x 8 filas (aprox)
            'large'  => 10, // 2 columnas x 5 filas (aprox)
        ];

        $perPage = $pageCapacity[$request->format];
        $cols = ($request->format == 'small') ? 5 : (($request->format == 'medium') ? 3 : 2);

        foreach ($request->items as $productId) {
            $product = Product::find($productId);
            if (!$product || !$product->barcode) continue;

            // Lógica forzada de cantidad (Anti-errores OLED)
            $totalQty = 1;
            if ($request->qty_mode === 'full') {
                $sheets = (int) ($request->sheets ?? 1);
                $totalQty = $sheets * $perPage;
            } else if (isset($request->quantities[$productId])) {
                 $totalQty = (int)$request->quantities[$productId];
            } else {
                 $totalQty = (int)($request->dynamic_qty ?? 1);
            }

            if ($totalQty > 500) $totalQty = 500; // Límite de seguridad

            // Generamos una sola vez la imagen base64 del código de barras para ahorrar memoria
            $barcodeImage = base64_encode($generator->getBarcode($product->barcode, 'C128', 2, ($request->format == 'large' ? 70 : 40)));

            for ($i = 0; $i < $totalQty; $i++) {
                $labels[] = [
                    'name'    => $product->name,
                    'price'   => number_format($product->price, 2, ',', '.'),
                    'barcode' => $barcodeImage,
                    'code'    => $product->barcode,
                    'empresa' => auth()->user()->empresa->nombre_comercial ?? 'POS'
                ];
            }
        }

        if (empty($labels)) {
            return back()->with('error', 'No se pudieron generar etiquetas para los productos seleccionados.');
        }

        $pdf = Pdf::loadView('pdf.labels', [
            'labels' => $labels,
            'format' => $request->format,
            'cols'   => $cols
        ]);

        // Configuración de papel A4
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('etiquetas-' . now()->format('YmdHis') . '.pdf');
    }

    /**
     * Acceso rápido desde listado de productos
     */
    public function printSingle($id)
    {
        return $this->generate(new Request([
            'items' => [$id],
            'format' => 'medium', // Por defecto mediana
            'qty_mode' => 'full', // Por defecto llenar hoja
            'sheets' => 1
        ]));
    }
}
