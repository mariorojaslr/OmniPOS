<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::where('empresa_id', auth()->user()->empresa_id)
            ->orderBy('nombre')
            ->get();
        return view('empresa.expenses.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ]);

        ExpenseCategory::create([
            'empresa_id' => auth()->user()->empresa_id,
            'nombre' => $request->nombre,
            'color' => $request->color ?? '#3b82f6',
            'activo' => true
        ]);

        return redirect()->back()->with('success', 'Categoría de gasto creada.');
    }

    public function update(Request $request, ExpenseCategory $category)
    {
        // SI VES ESTO, MI CÓDIGO ESTÁ FUNCIONANDO
        dd('¡CONECTADOS! Intentando guardar: ' . $category->nombre);
        
        $user = auth()->user();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ]);

        $category->update($request->only(['nombre', 'color', 'activo']));

        return redirect()->back()->with('success', 'Cambios guardados con éxito.');
    }

    public function destroy(ExpenseCategory $category)
    {
        $user = auth()->user();
        
        if ($user->role === 'usuario' && $category->empresa_id != $user->empresa_id) {
            abort(403);
        }
        
        if ($category->expenses()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar una categoría con gastos.');
        }

        $category->delete();
        return redirect()->back()->with('success', 'Categoría eliminada.');
    }
}
