<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function show($topic)
    {
        $viewPath = "empresa.ayuda.topics." . $topic;
        
        if (view()->exists($viewPath)) {
            return view($viewPath)->render();
        }

        return "<div class='alert alert-info'>Manual en desarrollo... pronto estará disponible para tu versión de MultiPOS.</div>";
    }
}
