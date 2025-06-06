<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Response;

// Página principal del sitio (inicio con información del evento y botones)
Route::get('/', [HomeController::class, 'index'])->name('inicio');

// Vistas públicas (inscripción)
Route::get('/inscripcion', [InscripcionController::class, 'mostrarFormulario'])->name('inscripcion.formulario');
Route::post('/inscripcion', [InscripcionController::class, 'registrar'])->name('inscripcion.registrar');
Route::post('/descargar-comprobante', function (Illuminate\Http\Request $request) {
    $pdfContent = base64_decode($request->input('pdf'));

    return Response::make($pdfContent, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="comprobante_inscripcion.pdf"',
    ]);
})->name('descargar.comprobante');
// Login del administrador
Route::get('/admin/login', [AdminController::class, 'formLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Rutas protegidas del administrador
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/evento', [AdminController::class, 'guardarEvento'])->name('admin.evento.guardar');
});
