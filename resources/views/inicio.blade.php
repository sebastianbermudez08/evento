@extends('layouts.app')

@section('content')

@if(session('registro_exitoso'))
    <div class="alert alert-success text-center mt-4">
        ¡Te registraste con éxito!
        <br>
        <small>Tu comprobante se descargo...</small>
    </div>

    <form id="descargarComprobante" method="POST" action="{{ route('descargar.comprobante') }}">
        @csrf
        <input type="hidden" name="pdf" value="{{ session('pdf') }}">
    </form>

    <script>
        document.getElementById('descargarComprobante').submit();
    </script>
@endif

<div class="card shadow mx-auto" style="background-color: {{ $evento->color_fondo ?? '#ffffff' }}; max-width: 900px;">
    <div class="row g-0">
        {{-- Imagen del evento --}}
        @if($evento && $evento->imagen)
            <div class="col-md-5">
                <img src="{{ asset('storage/' . $evento->imagen) }}" 
                     alt="Imagen del evento" 
                     class="img-fluid h-100 w-100 rounded-start" 
                     style="object-fit: cover;">
            </div>
        @endif

        {{-- Contenido del evento --}}
        <div class="col-md-7">
            <div class="card-body d-flex flex-column justify-content-between h-100">
                @if($evento)
                    <div>
                        <h5 class="card-title">{{ $evento->titulo }}</h5>
                        <p class="card-text">{{ $evento->descripcion }}</p>
                    </div>
                @else
                    <div>
                        <h5 class="card-title">Próximo Evento</h5>
                        <p class="card-text">Aún no hay información disponible para el evento. Vuelve pronto.</p>
                    </div>
                @endif

                {{-- Botones --}}
                <div class="mt-4 d-flex flex-column flex-md-row gap-2">
                    @if($evento)
                        <a href="{{ route('inscripcion.formulario') }}" class="btn btn-primary">Registrarme al evento</a>
                    @endif
                    <a href="{{ route('admin.login') }}" class="btn btn-secondary">Soy administrador</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
