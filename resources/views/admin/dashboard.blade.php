<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .event-card {
            background-color: {{ $evento->color_fondo ?? '#ffffff' }};
            color: #333;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        .event-img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .logout {
            float: right;
        }
    </style>
</head>
<body class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Panel de Administración</h2>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="btn btn-danger">Cerrar sesión</button>
        </form>
    </div>

    {{-- Evento actual --}}
    <div class="event-card">
        <h4>Evento Actual</h4>
        <p><strong>Título:</strong> {{ $evento->titulo ?? 'No hay evento activo' }}</p>
        <p><strong>Descripción:</strong> {{ $evento->descripcion ?? 'Sin descripción' }}</p>

        @if($evento && $evento->imagen)
            <div>
                <img src="{{ asset('storage/' . $evento->imagen) }}" alt="Imagen evento" class="event-img">
            </div>
        @endif
    </div>

    {{-- Crear o editar evento --}}
    <div class="mb-5">
        <h5>{{ $evento ? 'Editar' : 'Crear' }} Evento</h5>
        <form method="POST" action="{{ route('admin.evento.guardar') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $evento->id ?? '' }}">

            <div class="mb-3">
                <label>Título del Evento</label>
                <input type="text" name="titulo" class="form-control" value="{{ $evento->titulo ?? '' }}" required>
            </div>

            <div class="mb-3">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3">{{ $evento->descripcion ?? '' }}</textarea>
            </div>

            <div class="mb-3">
                <label>Color de Fondo</label>
                <input type="color" name="color_fondo" class="form-control form-control-color" value="{{ $evento->color_fondo ?? '#ffffff' }}">
            </div>

            <div class="mb-3">
                <label>Imagen del Evento</label>
                <input type="file" name="imagen" class="form-control">
            </div>

            <button class="btn btn-success">{{ $evento ? 'Actualizar' : 'Crear' }}</button>
        </form>
    </div>

    {{-- Lista de inscritos --}}
    <div>
        <h5>Inscritos al Evento</h5>
        @if($inscritos->isEmpty())
            <p>No hay inscritos aún.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Documento</th>
                        <th>Correo</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inscritos as $i)
                        <tr>
                            <td>{{ $i->nombre_completo }}</td>
                            <td>{{ $i->numero_documento }}</td>
                            <td>{{ $i->correo }}</td>
                            <td>{{ \Carbon\Carbon::parse($i->fecha_registro)->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>
