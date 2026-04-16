<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Client;
use App\Models\Cheque;

class ListadoController extends Controller
{
    public function articulos()
    {
        $empresaId = auth()->user()->empresa_id;
        $items = Product::where('empresa_id', $empresaId)
            ->where('active', true)
            ->with('rubro')
            ->orderBy('name')
            ->get();
        return view('empresa.listados.articulos', compact('items'));
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
