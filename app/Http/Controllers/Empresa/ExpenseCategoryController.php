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
        // PODER TOTAL: Quitamos cualquier validación de ID para que el Admin pueda corregir errores.
        $request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ]);

        $category->update([
            'nombre' => $request->nombre,
            'color' => $request->color,
            'activo' => $request->has('activo') ? true : false,
        ]);

        return redirect()->back()->with('success', 'Cambios guardados con éxito.');
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
