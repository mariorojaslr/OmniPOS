<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Rubro;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
{
    /**
     * Vista principal de etiquetas con productos y rubros
     */
    public function index()
    {
        // El scope de empresa ya se aplica automáticamente en el modelo Product
        $products = Product::where('barcode', '!=', '')
                           ->whereNotNull('barcode')
                           ->orderBy('name')
                           ->get();
        
        $rubros = \App\Models\Rubro::orderBy('nombre')->get();

        // Cargar las últimas compras para el filtro por compra
        $compras = \App\Models\Purchase::latest()
                                     ->take(50)
                                     ->get();

        return view('empresa.labels.index', compact('products', 'rubros', 'compras'));
    }

    /**
     * Generación de etiquetas OLED premium
     */
    public function generate(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'format' => 'required|string|in:small,medium,large',
            'qty_mode' => 'required|string|in:full,specific',
        ]);

        $empresa = auth()->user()->empresa;
        $generator = new BarcodeGeneratorPNG();
        $labels = [];

        // Definición de tamaños y capacidad por hoja A4
        $sizes = [
            'small'  => ['w' => 1.0, 'h' => 25, 'cols' => 5, 'per_page' => 65], // 38x21 mm
            'medium' => ['w' => 1.5, 'h' => 40, 'cols' => 3, 'per_page' => 24], // 70x37 mm
            'large'  => ['w' => 2.0, 'h' => 60, 'cols' => 2, 'per_page' => 10], // 105x48 mm
        ];
        
        $config = $sizes[$request->format];
        $perPage = $config['per_page'];
        $cols = $config['cols'];

        foreach ($request->items as $productId) {
            $product = Product::find($productId);
            if (!$product || !$product->barcode) continue;

            // Determinar cantidad total de etiquetas
            if ($request->qty_mode === 'full') {
                $sheets = (int) ($request->sheets ?? 1);
                $totalQty = $sheets * $perPage;
            } else {
                // Cantidad fija elegida por el usuario
                $totalQty = (int) ($request->dynamic_qty ?? ($request->quantities[$productId] ?? 1));
            }

            if ($totalQty > 500) $totalQty = 500; // Límite de seguridad

            // Generar imagen del código de barras en base64
            $barcodeImage = base64_encode($generator->getBarcode($product->barcode, 'C128', $config['w'], $config['h']));

            for ($i = 0; $i < $totalQty; $i++) {
                $labels[] = [
                    'name'    => $product->name,
                    'price'   => number_format($product->price, 2, ',', '.'),
                    'barcode' => $barcodeImage,
                    'code'    => $product->barcode,
                    'empresa' => $empresa->nombre_comercial ?? $empresa->nombre
                ];
            }
        }

        if (empty($labels)) {
            return back()->with('error', 'No se pudieron generar etiquetas. Verifique los códigos de barras.');
        }

        $pdf = Pdf::loadView('pdf.labels', [
            'labels' => $labels,
            'format' => $request->format,
            'cols'   => $cols
        ]);

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
            'format' => 'medium',
            'qty_mode' => 'full',
            'sheets' => 1
        ]));
    }
}
