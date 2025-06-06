<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;

class HomeController extends Controller
{
    public function index()
    {
        $evento = Evento::latest()->first(); // Carga el último evento
        return view('inicio', compact('evento')); // Vista principal
    }
}
