<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::where(function($query) {
            $query->where('empresa_id', auth()->user()->empresa_id)
                  ->orWhereNull('empresa_id');
        })->get();
        
        return view('empresa.expenses.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ]);

        ExpenseCategory::create([
            'nombre' => $request->nombre,
            'color' => $request->color ?? '#3b82f6',
            'empresa_id' => auth()->user()->empresa_id,
            'activo' => true
        ]);

        return redirect()->back()->with('success', 'Categoría de gasto creada.');
    }

    public function update(Request $request, ExpenseCategory $category)
    {
        // Asignación directa y manual para máxima seguridad
        $category->nombre = $request->input('nombre');
        $category->color = $request->input('color');
        $category->activo = $request->has('activo');
        
        $category->save();

        return redirect()->back()->with('success', '¡LISTO! Se guardó como: ' . $category->nombre);
    }

    public function destroy(ExpenseCategory $category)
    {
        if ($category->expenses()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar una categoría con gastos.');
        }

        $category->delete();
        return redirect()->back()->with('success', 'Categoría eliminada.');
    }
}
