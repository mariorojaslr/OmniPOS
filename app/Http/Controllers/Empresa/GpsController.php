<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GpsController extends Controller
{
    public function index()
    {
        return view('empresa.gps.index');
    }

    public function rutas()
    {
        return view('empresa.gps.rutas');
    }
}
