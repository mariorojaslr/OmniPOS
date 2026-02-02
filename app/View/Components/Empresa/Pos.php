<?php

namespace App\View\Components\Empresa;

use Illuminate\View\Component;
use App\Models\Product;

class Pos extends Component
{
    public $productos;
    public $venta = [];

    public function __construct()
    {
        $this->productos = Product::with('images')
            ->where('empresa_id', auth()->user()->empresa_id)
            ->where('active', true)
            ->get();
    }

    public function render()
    {
        return view('components.empresa.pos');
    }
}
