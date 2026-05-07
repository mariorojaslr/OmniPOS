<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MultiPOS - Recuperación</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @php
        $user = auth()->user();
        $empresa = $user?->empresa ?? null;
    @endphp
</head>
<body style="background: #f8fafc;">
    <nav class="navbar navbar-dark bg-dark shadow-sm mb-4">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">🛡️ MODO RECUPERACIÓN: {{ $empresa?->nombre_comercial ?? 'MultiPOS' }}</span>
            <div class="d-flex">
                <a href="{{ route('empresa.dashboard') }}" class="btn btn-outline-light btn-sm me-2">Inicio</a>
                <a href="{{ route('logout.get') }}" class="btn btn-danger btn-sm">Salir</a>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
