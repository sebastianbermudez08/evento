<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Evento;
use App\Models\Inscrito;

class AdminController extends Controller
{
    // Muestra formulario de login
    public function formLogin()
    {
        return view('admin.login');
    }

    // Procesa el login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Credenciales incorrectas',
        ]);
    }

    // Cierra sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); 
    }



    // Muestra panel del administrador
    public function dashboard()
    {
        $evento = Evento::latest()->first(); // Último evento creado
        $inscritos = Inscrito::all();
        return view('admin.dashboard', compact('evento', 'inscritos'));
    }

    // Guarda o actualiza un evento
    public function guardarEvento(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'color_fondo' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Actualizar si viene ID, si no crear nuevo
        if ($request->filled('id')) {
            $evento = Evento::find($request->id);
            if (!$evento) {
                return redirect()->back()->with('error', 'Evento no encontrado.');
            }
        } else {
            $evento = new Evento();
        }

        $evento->titulo = $request->titulo;
        $evento->descripcion = $request->descripcion;
        $evento->color_fondo = $request->color_fondo;

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($evento->imagen && Storage::disk('public')->exists($evento->imagen)) {
                Storage::disk('public')->delete($evento->imagen);
            }

            // Subir nueva imagen
            $path = $request->file('imagen')->store('eventos', 'public');
            $evento->imagen = $path;
        }

        $evento->save();

        return redirect()->route('admin.dashboard')->with('success', 'Evento guardado correctamente.');
    }

    

}
