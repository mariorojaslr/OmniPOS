<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso restringido</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="height:100vh;">

<div class="text-center">

    {{-- ICONO --}}
    <div class="mb-3">
        <div style="font-size:70px;">🔒</div>
    </div>

    {{-- TITULO --}}
    <h1 class="fw-bold text-secondary">Acceso restringido</h1>

    {{-- MENSAJE AMIGABLE --}}
    <p class="text-muted mt-3" style="max-width:420px;">
        Intentaste ingresar a un área para la cual tu usuario no tiene permisos.
        <br><br>
        Todo está funcionando correctamente 👍
        Simplemente esta sección no está disponible para tu perfil.
    </p>

    {{-- CODIGO TECNICO DISCRETO --}}
    <small class="text-muted">Código: 403</small>

    <div class="mt-4">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            Volver
        </a>

        <a href="{{ route('dashboard') }}" class="btn btn-primary ms-2">
            Ir al panel
        </a>
    </div>

</div>

</body>
</html>
