<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscrito;
use App\Models\Evento;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class InscripcionController extends Controller
{
    public function mostrarFormulario(Request $request)
    {
        $documento = $request->input('documento') ?? null;
        $evento = Evento::latest()->first();

        if (!$evento) {
            return redirect()->route('inicio')->with('error', 'Actualmente no hay eventos disponibles para inscribirse.');
        }

        return view('inscripcion.formulario', compact('evento', 'documento'));
    }

    public function mostrarValidar()
    {
        $evento = Evento::latest()->first();

        if (!$evento) {
            return redirect()->route('inicio')->with('error', 'Actualmente no hay eventos disponibles para inscribirse.');
        }

        return view('inscripcion.validar', compact('evento'));
    }

    public function registrar(Request $request)
    {
        $evento = Evento::latest()->first();

        if (!$evento) {
            return redirect()->route('inicio')->with('error', 'No hay evento activo para registrar.');
        }

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

        $inscrito = Inscrito::create([
            'evento_id'          => $evento->id,
            'nombre_completo'    => $request->nombre_completo,
            'numero_documento'   => $request->numero_documento,
            'edad'               => $request->edad,
            'genero'             => $request->genero,
            'correo'             => $request->correo,
            'telefono'           => $request->telefono,
            'profesion'          => $request->profesion,
            'empresa'            => $request->empresa,
            'fecha_registro'     => $request->fecha_registro,
            'comprobante_token'  => Str::random(40),
        ]);

        $pdf = PDF::loadView('inscripcion.comprobante', compact('inscrito'));
        $pdfBase64 = base64_encode($pdf->output());

        return view('inscripcion.registro_exitoso', [
            'inscrito' => $inscrito,
            'pdfBase64' => $pdfBase64
        ]);
    }

    public function verComprobanteAdmin($id)
    {
        $inscrito = Inscrito::findOrFail($id);
        $pdf = PDF::loadView('pdf.inscrito', compact('inscrito'));
        return $pdf->stream('comprobante_admin.pdf');
    }

    public function validar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero_documento' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $inscrito = Inscrito::where('numero_documento', $request->numero_documento)->first();

        if (!$inscrito) {
            return redirect()->route('registro.formulario', [
                'documento' => $request->numero_documento
            ]);
        }

        return view('inscripcion.existe', compact('inscrito'));
    }
}
