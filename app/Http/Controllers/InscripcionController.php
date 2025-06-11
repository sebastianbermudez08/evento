<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscrito;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Evento;
use Barryvdh\DomPDF\Facade\Pdf;

class InscripcionController extends Controller
{
    // Mostrar formulario de inscripción
    public function mostrarFormulario()
    {
        $evento = Evento::latest()->first();

        if (!$evento) {
            return redirect()->route('inicio')->with('error', 'Actualmente no hay eventos disponibles para inscribirse.');
        }

        return view('inscripcion.formulario', compact('evento'));
    }

    // Procesar inscripción
    public function registrar(Request $request)
{
    $validator = Validator::make($request->all(), [
        'nombre_completo'     => 'required|string|max:255',
        'numero_documento'    => 'required|string|max:50|unique:inscritos',
        'edad'                => 'required|integer|min:1|max:120',
        'genero'              => 'required|in:Masculino,Femenino,Otro',
        'correo'              => 'required|email|unique:inscritos',
        'telefono'            => 'required|string|max:20',
        'profesion'           => 'nullable|string|max:100',
        'empresa'             => 'nullable|string|max:100',
        'fecha_registro'      => 'required|date',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $token = Str::random(40);

    $inscrito = Inscrito::create([
        'nombre_completo'    => $request->nombre_completo,
        'numero_documento'   => $request->numero_documento,
        'edad'               => $request->edad,
        'genero'             => $request->genero,
        'correo'             => $request->correo,
        'telefono'           => $request->telefono,
        'profesion'          => $request->profesion,
        'empresa'            => $request->empresa,
        'fecha_registro'     => $request->fecha_registro,
        'comprobante_token'  => $token,
    ]);

    $pdf = PDF::loadView('inscripcion.comprobante', compact('inscrito'));
    $pdfContent = $pdf->output();
    $pdfBase64 = base64_encode($pdfContent);

    // Redirigir a una vista con mensaje y comprobante para descargar automáticamente
    return view('inscripcion.registro_exitoso', [
        'inscrito' => $inscrito,
        'pdfBase64' => $pdfBase64
    ]);
}


    // Mostrar PDF en panel administrativo (ya lo tienes funcionando)
    public function verComprobanteAdmin($id)
    {
        $inscrito = Inscrito::findOrFail($id);
        $pdf = PDF::loadView('pdf.inscrito', compact('inscrito'));
        return $pdf->stream('comprobante_admin.pdf');
    }
}
