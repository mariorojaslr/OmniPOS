<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Landing Page Dinámica con Planes desde BD
     */
    public function index()
    {
        $plans = Plan::where('is_active', true)->orderBy('price', 'asc')->get();
        return view('welcome', compact('plans'));
    }
}
