<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BackupController extends Controller
{
    /**
     * Muestra la Bóveda de Resguardo de la Empresa.
     */
    public function index()
    {
        $empresa = Auth::user()->empresa;
        
        // Aquí podríamos listar backups reales de S3 o Bunny si existieran localmente.
        // Por ahora cargamos la interfaz de la Bóveda protegida.
        return view('empresa.backup.index', compact('empresa'));
    }
}
