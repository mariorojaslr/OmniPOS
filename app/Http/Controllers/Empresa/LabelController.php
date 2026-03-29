<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\BarcodeGeneratorHTML;
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
            'selected_items' => 'required|array',
            'format' => 'required|string|in:small,medium,large',
        ]);

        $empresa = Auth::user()->empresa;
        $generator = new BarcodeGeneratorHTML();
        $labels = [];

        // Definición de tamaños según formato (ancho de barra, altura, columnas sugeridas)
        $sizes = [
            'small'  => ['w' => 0.8, 'h' => 20, 'cols' => 5], 
            'medium' => ['w' => 1.2, 'h' => 30, 'cols' => 3], 
            'large'  => ['w' => 1.7, 'h' => 45, 'cols' => 2], 
        ];
        $config = $sizes[$request->format];

        foreach ($request->selected_items as $productId => $enabled) {
            if ($enabled != "1") continue;

            $qty = (int) ($request->quantities[$productId] ?? 1);
            $product = Product::where('empresa_id', $empresa->id)->find($productId);
            
            if ($product && $product->barcode && $qty > 0) {
                for ($i = 0; $i < $qty; $i++) {
                    $labels[] = [
                        'name'    => $product->name,
                        'price'   => number_format($product->price, 2),
                        'barcode' => $generator->getBarcode($product->barcode, BarcodeGenerator::TYPE_CODE_128, $config['w'], $config['h']),
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

        $generator = new BarcodeGeneratorHTML();
        $labels = [];
        
        // Generamos una hoja con 21 etiquetas (3x7) del mismo producto
        for ($i = 0; $i < 21; $i++) {
            $labels[] = [
                'name'    => $product->name,
                'price'   => number_format($product->price, 2),
                'barcode' => $generator->getBarcode($product->barcode, BarcodeGenerator::TYPE_CODE_128, 1.5, 35),
                'code'    => $product->barcode,
                'empresa' => $empresa->nombre
            ];
        }

        $pdf = Pdf::loadView('pdf.labels', compact('labels'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("etiquetas_{$product->id}.pdf");
    }
}
