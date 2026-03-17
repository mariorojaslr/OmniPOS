<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::where('empresa_id', auth()->user()->empresa_id)
            ->with('category', 'user')
            ->orderByDesc('date');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('from')) {
            $query->where('date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->where('date', '<=', $request->to);
        }

        $expenses = $query->paginate(20);
        $categories = ExpenseCategory::where('empresa_id', auth()->user()->empresa_id)->get();
        
        $total = $query->sum('amount');

        return view('empresa.expenses.index', compact('expenses', 'categories', 'total'));
    }

    public function create()
    {
        $categories = ExpenseCategory::where('empresa_id', auth()->user()->empresa_id)
            ->where('activo', true)
            ->get();
        return view('empresa.expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'category_id' => 'nullable|exists:expense_categories,id',
            'description' => 'required|string|max:500',
        ]);

        Expense::create([
            'empresa_id' => auth()->user()->empresa_id,
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
            'receipt_url' => $this->extractFirstImage($request->description)
        ]);

        return redirect()->route('empresa.gastos.index')->with('success', 'Gasto registrado correctamente.');
    }

    public function edit(Expense $gasto)
    {
        if ($gasto->empresa_id !== auth()->user()->empresa_id) abort(403);
        
        $categories = ExpenseCategory::where('empresa_id', auth()->user()->empresa_id)->get();
        return view('empresa.expenses.edit', compact('gasto', 'categories'));
    }

    public function update(Request $request, Expense $gasto)
    {
        if ($gasto->empresa_id !== auth()->user()->empresa_id) abort(403);

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'category_id' => 'nullable|exists:expense_categories,id',
            'description' => 'required|string|max:500',
        ]);

        $gasto->update([
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
            'receipt_url' => $this->extractFirstImage($request->description)
        ]);

        return redirect()->route('empresa.gastos.index')->with('success', 'Gasto actualizado.');
    }

    public function destroy(Expense $gasto)
    {
        if ($gasto->empresa_id !== auth()->user()->empresa_id) abort(403);
        $gasto->delete();
        return redirect()->route('empresa.gastos.index')->with('success', 'Gasto eliminado.');
    }

    public function uploadMedia(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('expenses', $filename, 'public');
            
            // Usar la ruta local.media para máxima compatibilidad
            $url = route('local.media', ['path' => 'expenses/' . $filename]);
            
            return response()->json(['url' => $url]);
        }
        return response()->json(['error' => 'Error al subir'], 400);
    }

    private function extractFirstImage($text)
    {
        // Regex para capturar la primera URL de imagen markdown ![desc](url)
        if (preg_match('/!\[.*?\]\((.*?)\)/', $text, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
