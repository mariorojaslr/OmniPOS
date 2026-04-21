<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Client;
use App\Models\Cheque;
use App\Models\Rubro;

class ListadoController extends Controller
{
    /**
     * Motor de Listados de Artículos con Filtros Inteligentes
     */
    public function articulos(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        
        $query = Product::where('empresa_id', $empresaId)
            ->where('active', true)
            ->with('rubro');

        // Filtro: Rubro
        if ($request->filled('rubro_id')) {
            $rubroIds = array_filter((array)$request->rubro_id);
            if (!empty($rubroIds)) {
                $query->whereIn('rubro_id', $rubroIds);
            }
        }

        // Filtro: Rango Alfabético (Desde - Hasta)
        if ($request->filled('desde')) {
            $query->where('name', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            // Se le suma 'z' para incluir todas las palabras que empiecen con esa letra
            $query->where('name', '<=', $request->hasta . 'z');
        }

        // Filtro: Stock
        if ($request->has('solo_stock')) {
            $query->where('stock', '>', 0);
        }

        // Filtro: Con Foto
        if ($request->has('solo_con_foto')) {
            $query->whereNotNull('image')->where('image', '!=', '');
        }

        $items = $query->orderBy('name')->get();
        $rubros = Rubro::where('empresa_id', $empresaId)->get();

        return view('empresa.listados.articulos', compact('items', 'rubros'));
    }

    public function clientes()
    {
        $empresaId = auth()->user()->empresa_id;
        $items = Client::where('empresa_id', $empresaId)
            ->where('active', true)
            ->orderBy('name')
            ->get();
        return view('empresa.listados.clientes', compact('items'));
    }

    public function cheques()
    {
        $empresaId = auth()->user()->empresa_id;
        $items = Cheque::where('empresa_id', $empresaId)
            ->orderBy('fecha_vencimiento')
            ->get();
        return view('empresa.listados.cheques', compact('items'));
    }
}
