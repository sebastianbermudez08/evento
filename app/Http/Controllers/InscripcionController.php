<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscrito;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
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
        // Validar los datos
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


        // Generar token único (comprobante)
        $token = Str::random(40);

        // Guardar en base de datos
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

        // Generar PDF
        $pdf = PDF::loadView('inscripcion.comprobante', compact('inscrito'));
        $pdfBase64 = base64_encode($pdf->output());

        // Redirigir al inicio con mensaje y PDF
        return redirect()->route('inicio')->with([
            'registro_exitoso' => true,
            'pdf' => $pdfBase64,
        ]);



        // Descargar PDF
        return $pdf->download('comprobante_inscripcion.pdf');
    }
}
