<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\AdminController;

// Página principal del sitio (inicio con información del evento y botones)
Route::get('/', [HomeController::class, 'index'])->name('inicio');


// ---------------------
// Rutas públicas (inscripción)
// ---------------------

Route::get('/validar', [InscripcionController::class, 'mostrarValidar'])->name('registro.formValidar');
Route::post('/validar', [InscripcionController::class, 'validar'])->name('inscripcion.validar');


// Mostrar formulario de inscripción
Route::get('/inscripcion', [InscripcionController::class, 'mostrarFormulario'])->name('registro.formulario');

// Procesar inscripción
Route::post('/inscripcion', [InscripcionController::class, 'registrar'])->name('inscripcion.registrar');

// Descargar comprobante (PDF)
Route::post('/descargar-comprobante', function (Illuminate\Http\Request $request) {
    $pdfContent = base64_decode($request->input('pdf'));

    return Response::make($pdfContent, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="comprobante_inscripcion.pdf"',
    ]);
})->name('descargar.comprobante');


// ---------------------
// Rutas del administrador
// ---------------------

// Login administrador
Route::get('/admin/login', [AdminController::class, 'formLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Vista para editar evento
Route::get('/admin/evento/editar/{id}', [AdminController::class, 'formEditarEvento'])->name('admin.evento.editar');

// Generar PDF de un inscrito
Route::get('/admin/inscrito/{id}/pdf', [AdminController::class, 'generarPDF'])->name('admin.inscrito.pdf');

// Eliminar inscritos seleccionados
Route::delete('/admin/inscritos/eliminar-seleccionados', [AdminController::class, 'eliminarSeleccionados'])->name('admin.inscritos.eliminar_seleccionados');


// ---------------------
// Rutas protegidas con login
// ---------------------
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/evento', [AdminController::class, 'guardarEvento'])->name('admin.evento.guardar');
});
