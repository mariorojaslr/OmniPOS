<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        // KPIs reales hoy
        $usuariosCount = User::where('empresa_id', $empresa->id)->count();
        $productosCount = Product::where('empresa_id', $empresa->id)->count();

        // KPIs futuros (placeholders)
        $ventasHoy = 0;
        $ventasMes = 0;
        $stockBajo = 0;

        return view('empresa.dashboard', [
            'empresa'         => $empresa,
            'usuariosCount'   => $usuariosCount,
            'productosCount'  => $productosCount,
            'ventasHoy'       => $ventasHoy,
            'ventasMes'       => $ventasMes,
            'stockBajo'       => $stockBajo,
        ]);
    }
}
