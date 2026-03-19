<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Picqer\Barcode\BarcodeGeneratorSVG;
use Barryvdh\DomPDF\Facade\Pdf;

class LabelController extends Controller
{
    /**
     * Vista de selección de qué etiquetas imprimir
     */
    public function index(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        $products = Product::where('empresa_id', $empresaId)
            ->whereNotNull('barcode')
            ->orderBy('name')
            ->get();
            
        return view('empresa.labels.index', compact('products'));
    }

    /**
     * Generar PDF con las etiquetas
     */
    public function generate(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'quantity' => 'required|integer|min:1|max:100', // etiquetas por producto
        ]);

        $empresa = Auth::user()->empresa;
        $generator = new BarcodeGeneratorSVG();
        $labels = [];

        foreach ($request->items as $itemId) {
            // Podría ser producto o variante
            $product = Product::where('empresa_id', $empresa->id)->find($itemId);
            
            if ($product && $product->barcode) {
                for ($i = 0; $i < $request->quantity; $i++) {
                    $labels[] = [
                        'name'    => $product->name,
                        'price'   => number_format($product->price, 2),
                        'barcode' => $generator->getBarcode($product->barcode, $generator::TYPE_CODE_128, 2, 30),
                        'code'    => $product->barcode,
                        'empresa' => $empresa->nombre
                    ];
                }
            }
        }

        if (empty($labels)) {
            return back()->with('error', 'No se generaron etiquetas (verifica códigos de barras)');
        }

        $pdf = Pdf::loadView('pdf.labels', compact('labels'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('etiquetas_productos.pdf');
    }
    
    /**
     * Imprimir una sola etiqueta rápida (Ej: desde el edit del producto)
     */
    public function printSingle(Product $product)
    {
        $empresa = Auth::user()->empresa;
        if ($product->empresa_id !== $empresa->id) abort(403);
        
        $generator = new BarcodeGeneratorSVG();
        $labels = [];
        
        // Generamos una hoja con 21 etiquetas (3x7) del mismo producto
        for ($i = 0; $i < 21; $i++) {
            $labels[] = [
                'name'    => $product->name,
                'price'   => number_format($product->price, 2),
                'barcode' => $generator->getBarcode($product->barcode, $generator::TYPE_CODE_128, 2, 40),
                'code'    => $product->barcode,
                'empresa' => $empresa->nombre
            ];
        }

        $pdf = Pdf::loadView('pdf.labels', compact('labels'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("etiquetas_{$product->id}.pdf");
    }
}
