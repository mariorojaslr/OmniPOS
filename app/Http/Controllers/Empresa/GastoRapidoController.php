<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GastoRapidoController extends Controller
{
    /**
     * 📲 Vista móvil para registro de gasto rápido (Field App)
     */
    public function create()
    {
        $user = Auth::user();

        // Seguridad: Verificar si tiene permiso
        if (!$user->can_register_expenses && $user->role !== 'empresa') {
            abort(403, 'No tienes permisos para registrar gastos de campo.');
        }

        return view('empresa.personal.quick_expense');
    }

    /**
     * 💾 Procesar el gasto rápido desde el móvil
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $empresaId = $user->empresa_id;

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'category' => 'nullable|string',
            'receipt_photo' => 'nullable|image|max:5120' // Max 5MB
        ]);

        // 1. Obtener asistencia activa (Turno)
        $asistencia = Asistencia::where('user_id', $user->id)
            ->whereNull('salida')
            ->latest()
            ->first();

        // 2. Buscar o crear la categoría (Rubro)
        $catName = strtoupper($request->category ?: 'OTROS');
        $categoria = ExpenseCategory::firstOrCreate(
            ['empresa_id' => $empresaId, 'nombre' => $catName]
        );

        // 3. Procesar Foto (Local por ahora, preparando para Bunny)
        $photoPath = null;
        if ($request->hasFile('receipt_photo')) {
            $photoPath = $request->file('receipt_photo')->store('expenses/' . $empresaId, 'public');
        }

        // 4. Crear el Gasto
        $gasto = Expense::create([
            'empresa_id' => $empresaId,
            'user_id' => $user->id,
            'asistencia_id' => $asistencia ? $asistencia->id : null,
            'category_id' => $categoria->id,
            'amount' => $request->amount,
            'provider' => $request->supplier ?: 'Varios',
            'description' => "Gasto rápido de campo: " . ($request->supplier ?: $catName),
            'date' => now(),
            'receipt_url' => $photoPath ? route('local.media', ['path' => $photoPath]) : null,
            'payment_method' => $request->input('payment_method', 'efectivo')
        ]);

        // REGISTRAR ACTIVIDAD
        \App\Models\ActivityLog::log("Registró un gasto rápido de campo por $" . number_format($request->amount, 2, ',', '.') . " ({$catName})");

        return view('empresa.personal.quick_expense_success');
    }
}
