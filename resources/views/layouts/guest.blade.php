<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'MultiPOS') }}</title>

    {{-- ======================================================
        FAVICON MULTIPOS
    ====================================================== --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    {{-- ======================================================
        BOOTSTRAP (solo GRID + UTILIDADES)
    ====================================================== --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- ======================================================
        CSS COMPILADO (TAILWIND + APP.CSS)
    ====================================================== --}}
    @vite('resources/css/app.css')

    {{-- ======================================================
        COLORES DINÁMICOS MULTIEMPRESA
    ====================================================== --}}
    @php
        $primary   = '#2563eb';
        $secondary = '#16a34a';

        if(isset($empresa) && $empresa->configuracion){
            $primary   = $empresa->configuracion->color_primario   ?? $primary;
            $secondary = $empresa->configuracion->color_secundario ?? $secondary;
        }
    @endphp

    <style>
        :root {
            --color-primary: {{ $primary }};
            --color-secondary: {{ $secondary }};
        }

        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            background: #f4f6f9;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        }

        .guest-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
    </style>
</head>

<body>

    <div class="guest-wrapper">
        @yield('content')
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
