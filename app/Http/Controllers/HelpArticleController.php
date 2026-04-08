<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HelpArticle;

class HelpArticleController extends Controller
{
    /**
     * 🔍 Buscar ayuda para la ruta actual
     */
    public function fetch(Request $request)
    {
        $routeName = $request->query('route');
        
        $article = HelpArticle::where('route_name', $routeName)
            ->where('is_active', true)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $article
        ]);
    }

    /**
     * 💾 Guardar o actualizar la ayuda (Solo Owner)
     */
    public function save(Request $request)
    {
        $request->validate([
            'route_name' => 'required|string',
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'video_url'  => 'nullable|url'
        ]);

        $article = HelpArticle::updateOrCreate(
            ['route_name' => $request->input('route_name')],
            [
                'title'     => $request->input('title'),
                'content'   => $request->input('content'),
                'video_url' => $request->input('video_url'),
                'is_active' => true
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Manual actualizado correctamente',
            'data'    => $article
        ]);
    }
}
