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
        ]);

        $empresa = Auth::user()->empresa;
        $generator = new BarcodeGeneratorPNG();
        $labels = [];

        // Definición de tamaños estándar en mm (Ancho x Avance)
        $sizes = [
            'small'  => ['w' => 1.0, 'h' => 30, 'cols' => 5], // 33x22 mm
            'medium' => ['w' => 1.5, 'h' => 45, 'cols' => 3], // 50x25 mm
            'large'  => ['w' => 2.2, 'h' => 70, 'cols' => 2], // 100x50 mm
        ];
        $config = $sizes[$request->format];

        foreach ($request->items as $productId) {
            $qty = (int) ($request->quantities[$productId] ?? 1);
            $product = Product::where('empresa_id', $empresa->id)->find($productId);
            
            if ($product && $product->barcode && $qty > 0) {
                // Generamos una sola vez la imagen base64 del código de barras
                $barcodeImage = base64_encode($generator->getBarcode($product->barcode, 'C128', $config['w'], $config['h']));

                for ($i = 0; $i < $qty; $i++) {
                    $labels[] = [
                        'name'    => $product->name,
                        'price'   => number_format($product->price, 2),
                        'barcode' => $barcodeImage,
                        'code'    => $product->barcode,
                        'empresa' => $empresa->nombre
                    ];
                }
            }
        }

        if (empty($labels)) {
            return back()->with('error', 'No se seleccionaron etiquetas válidas.');
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
     */
    public function printSingle(Product $product)
    {
        $empresa = Auth::user()->empresa;
        if ($product->empresa_id !== $empresa->id) abort(403);
        
        if (!$product->barcode) {
            return back()->with('error', 'Este producto no tiene un código de barras asignado. Por favor cárgalo antes de imprimir.');
        }

        $generator = new BarcodeGeneratorPNG();
        $labels = [];
        
        $barcodeImage = base64_encode($generator->getBarcode($product->barcode, 'C128', 1.5, 35));

        // Generamos una hoja con 21 etiquetas (3x7) del mismo producto
        for ($i = 0; $i < 21; $i++) {
            $labels[] = [
                'name'    => $product->name,
                'price'   => number_format($product->price, 2),
                'barcode' => $barcodeImage,
                'code'    => $product->barcode,
                'empresa' => $empresa->nombre
            ];
        }

        $pdf = Pdf::loadView('pdf.labels', [
            'labels' => $labels,
            'format' => 'medium',
            'cols'   => 3
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("etiquetas_{$product->id}.pdf");
    }
}
