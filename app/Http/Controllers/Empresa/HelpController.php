<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HelpArticle;
use Illuminate\Support\Facades\Route;

class HelpController extends Controller
{
    public function show($topic = null)
    {
        // Si no hay tópico, intentamos usar el nombre de la ruta actual
        if (!$topic || $topic === 'general') {
             // Podríamos deducirlo del Referer si esto viene por AJAX
             $topic = 'general';
        }

        $article = HelpArticle::where('route_name', $topic)
            ->where('is_active', true)
            ->first();

        if (!$article) {
            return "<div class='text-center py-5'>
                        <i class='bi bi-magic fs-1 text-muted opacity-25 mb-3'></i>
                        <h5 class='fw-bold'>Sección aún no documentada</h5>
                        <p class='text-muted small'>No hay instrucciones registradas para: <code>" . $topic . "</code></p>
                    </div>";
        }

        return "
            <div class='p-1'>
                <h4 class='fw-bold mb-3'>{$article->title}</h4>
                " . ($article->video_url ? "<div class='ratio ratio-16x9 mb-3'><iframe src='{$article->video_url}' allowfullscreen></iframe></div>" : "") . "
                <div class='help-content text-muted' style='line-height: 1.6; font-size: 0.95rem;'>
                    {$article->content}
                </div>
            </div>
        ";
    }
}
