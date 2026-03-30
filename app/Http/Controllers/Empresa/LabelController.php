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
<<<<<<< HEAD
            'format' => 'required|string|in:small,medium,large',
            'qty_mode' => 'required|string|in:full,specific',
=======
            'format' => 'required|in:small,medium,large'
>>>>>>> staging
        ]);

        $generator = new BarcodeGeneratorPNG();
        $labels = [];

<<<<<<< HEAD
        // Definición de tamaños y capacidad por hoja A4
        // 'w' es el grosor de línea del código de barras, 'h' es altura
        // 'per_page' es cuántas entran en una hoja A4 (aprox)
        $sizes = [
            'small'  => ['w' => 1.0, 'h' => 25, 'cols' => 5, 'per_page' => 65], // 38x21 mm aprox (5x13)
            'medium' => ['w' => 1.5, 'h' => 40, 'cols' => 3, 'per_page' => 24], // 70x37 mm aprox (3x8)
            'large'  => ['w' => 2.0, 'h' => 60, 'cols' => 2, 'per_page' => 10], // 105x48 mm aprox (2x5)
        ];
        
        $config = $sizes[$request->format];
        $perPage = $config['per_page'];
=======
        // Definición de capacidad por hoja A4 para cada formato
        // Basado en márgenes estándar y tamaños aproximados
        $pageCapacity = [
            'small'  => 65, // 5 columnas x 13 filas (aprox)
            'medium' => 24, // 3 columnas x 8 filas (aprox)
            'large'  => 10, // 2 columnas x 5 filas (aprox)
        ];

        $perPage = $pageCapacity[$request->format];
        $cols = ($request->format == 'small') ? 5 : (($request->format == 'medium') ? 3 : 2);
>>>>>>> staging

        foreach ($request->items as $productId) {
            $product = Product::find($productId);
            if (!$product || !$product->barcode) continue;

<<<<<<< HEAD
            // Determinar cantidad total de etiquetas a imprimir
            if ($request->qty_mode === 'full') {
                $sheets = (int) ($request->sheets ?? 1);
                $totalQty = $sheets * $perPage;
            } else {
                $totalQty = (int) ($request->quantities[$productId] ?? ($request->dynamic_qty ?? 1));
=======
            // Lógica forzada de cantidad (Anti-errores OLED)
            $totalQty = 1;
            if ($request->qty_mode === 'full') {
                $sheets = (int) ($request->sheets ?? 1);
                $totalQty = $sheets * $perPage;
            } else if (isset($request->quantities[$productId])) {
                 $totalQty = (int)$request->quantities[$productId];
            } else {
                 $totalQty = (int)($request->dynamic_qty ?? 1);
>>>>>>> staging
            }

            if ($totalQty > 500) $totalQty = 500; // Límite de seguridad

<<<<<<< HEAD
            // Generamos una sola vez la imagen base64 del código de barras
            $barcodeImage = base64_encode($generator->getBarcode($product->barcode, 'C128', $config['w'], $config['h']));
=======
            // Generamos una sola vez la imagen base64 del código de barras para ahorrar memoria
            $barcodeImage = base64_encode($generator->getBarcode($product->barcode, 'C128', 2, ($request->format == 'large' ? 70 : 40)));
>>>>>>> staging

            for ($i = 0; $i < $totalQty; $i++) {
                $labels[] = [
                    'name'    => $product->name,
<<<<<<< HEAD
                    'price'   => number_format($product->price, 0, ',', '.'),
                    'barcode' => $barcodeImage,
                    'code'    => $product->barcode,
                    'empresa' => $empresa->nombre_comercial ?? $empresa->nombre
=======
                    'price'   => number_format($product->price, 2, ',', '.'),
                    'barcode' => $barcodeImage,
                    'code'    => $product->barcode,
                    'empresa' => auth()->user()->empresa->nombre_comercial ?? 'POS'
>>>>>>> staging
                ];
            }
        }

        if (empty($labels)) {
<<<<<<< HEAD
            return back()->with('error', 'No se pudieron generar etiquetas. Verifique que los productos tengan código de barras.');
=======
            return back()->with('error', 'No se pudieron generar etiquetas para los productos seleccionados.');
>>>>>>> staging
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
<<<<<<< HEAD
     * Imprimir una sola etiqueta rápida (Ej: desde el edit del producto)
     * Ahora redirige al modal o se puede usar con parámetros por defecto
=======
     * Acceso rápido desde listado de productos
>>>>>>> staging
     */
    public function printSingle($id)
    {
<<<<<<< HEAD
        if (!$product->barcode) {
            return back()->with('error', 'Este producto no tiene un código de barras asignado.');
        }

        // Por defecto: 1 página completa mediana
        return redirect()->route('empresa.labels.generate', [
            'items' => [$product->id],
            'format' => 'medium',
            'qty_mode' => 'full',
            'sheets' => 1
        ]);
=======
        return $this->generate(new Request([
            'items' => [$id],
            'format' => 'medium', // Por defecto mediana
            'qty_mode' => 'full', // Por defecto llenar hoja
            'sheets' => 1
        ]));
>>>>>>> staging
    }
}
