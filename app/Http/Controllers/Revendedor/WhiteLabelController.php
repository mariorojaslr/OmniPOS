<?php

namespace App\Http\Controllers\Revendedor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WhiteLabelController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $config = $user->resellerConfig()->firstOrCreate(
            ['user_id' => $user->id],
            ['owner_commission_percentage' => 10.00] // Default, if the owner hasn't set one
        );

        return view('revendedor.white_label', compact('config'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'brand_name' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        $user = Auth::user();
        $config = $user->resellerConfig()->firstOrCreate(
            ['user_id' => $user->id]
        );

        $data = [
            'brand_name' => $request->input('brand_name'),
            'primary_color' => $request->input('primary_color', '#4f46e5'),
        ];

        if ($request->hasFile('logo')) {
            // Eliminar logo anterior si existe
            if ($config->logo_path) {
                Storage::disk('public')->delete($config->logo_path);
            }
            $path = $request->file('logo')->store('resellers/logos', 'public');
            $data['logo_path'] = $path;
        }

        $config->update($data);

        return redirect()->back()->with('success', 'Configuración de Marca Blanca actualizada correctamente.');
    }
}
