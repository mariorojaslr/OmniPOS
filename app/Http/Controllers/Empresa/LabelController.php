<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Barryvdh\DomPDF\Facade\Pdf;

class LabelController extends Controller
{
    /**
     * Vista de selección de qué etiquetas imprimir
     */
    public function index(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        
        $query = Product::where('empresa_id', $empresaId)
            ->whereNotNull('barcode')
            ->orderBy('name');

        // Filtro por Rubro
        if ($request->filled('rubro_id')) {
            $query->where('rubro_id', $request->rubro_id);
        }

        // Filtro por Compras (Últimas compras que contienen este producto)
        if ($request->filled('purchase_id')) {
            $query->whereHas('purchaseItems', function($q) use ($request) {
                $q->where('purchase_id', $request->purchase_id);
            });
        }

        // Filtro por "Nuevas" (Últimas 48 horas)
        if ($request->filter === 'nuevas') {
            $query->where('created_at', '>=', now()->subHours(48));
        }

        $products = $query->get();
        
        $rubros = \App\Models\Rubro::where('empresa_id', $empresaId)->get();
        $compras = \App\Models\Purchase::where('empresa_id', $empresaId)->orderByDesc('id')->limit(10)->get();

        return view('empresa.labels.index', compact('products', 'rubros', 'compras'));
    }

    /**
     * Generar PDF con las etiquetas (Mejorado con formatos y repeticiones)
     */
    public function generate(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'format' => 'required|string|in:small,medium,large',
            'qty_mode' => 'required|string|in:full,specific',
        ]);

        $empresa = Auth::user()->empresa;
        $generator = new BarcodeGeneratorPNG();
        $labels = [];

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

        foreach ($request->items as $productId) {
            $product = Product::find($productId);
            if (!$product || !$product->barcode) continue;

            // Determinar cantidad total de etiquetas a imprimir
            if ($request->qty_mode === 'full') {
                $sheets = (int) ($request->sheets ?? 1);
                $totalQty = $sheets * $perPage;
            } else {
                $totalQty = (int) ($request->quantities[$productId] ?? ($request->dynamic_qty ?? 1));
            }

            if ($totalQty > 500) $totalQty = 500; // Límite de seguridad

            // Generamos una sola vez la imagen base64 del código de barras
            $barcodeImage = base64_encode($generator->getBarcode($product->barcode, 'C128', $config['w'], $config['h']));

            for ($i = 0; $i < $totalQty; $i++) {
                $labels[] = [
                    'name'    => $product->name,
                    'price'   => number_format($product->price, 0, ',', '.'),
                    'barcode' => $barcodeImage,
                    'code'    => $product->barcode,
                    'empresa' => $empresa->nombre_comercial ?? $empresa->nombre
                ];
            }
        }

        if (empty($labels)) {
            return back()->with('error', 'No se pudieron generar etiquetas. Verifique que los productos tengan código de barras.');
        }

        $pdf = Pdf::loadView('pdf.labels', [
            'labels' => $labels,
            'format' => $request->format,
            'cols'   => $config['cols']
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('etiquetas_productos.pdf');
    }
    
    /**
     * Imprimir una sola etiqueta rápida (Ej: desde el edit del producto)
     * Ahora redirige al modal o se puede usar con parámetros por defecto
     */
    public function printSingle(Product $product)
    {
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
    }
}
