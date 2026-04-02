<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'MultiPOS') }}</title>

    {{-- ======================================================
        FAVICON MULTIPOS
    ====================================================== --}}
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    {{-- ======================================================
        BOOTSTRAP (solo GRID + UTILIDADES)
    ====================================================== --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- ======================================================
        CSS COMPILADO (TAILWIND + APP.CSS)
    ====================================================== --}}
    {{-- Vite eliminado para evitar error 500 en hosting tradicional --}}
    {{-- @vite('resources/css/app.css') --}}

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
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background: #0f172a; /* Fondo oscuro elegante premium */
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #f8fafc;
        }

        /* Fondo oscuro sólido y profesional */
        .premium-bg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            background: #0f172a; /* Fondo base neutro */
        }

        /* Animación eliminada para evitar distracciones en login */

        .guest-wrapper {
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* ----- ESTILOS GLASSMORPHISM GLOBALES PARA AUTH ----- */
        .auth-container {
            width: 100%;
            max-width: 420px;
            transition: all 0.3s ease;
        }

        .auth-container.broad {
            max-width: 900px;
        }

        .auth-card {
            background: rgba(30, 41, 59, 0.65);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
            transition: all 0.3s ease;
        }

        .auth-logo img {
            max-width: 280px;
            height: auto;
            margin: 0 auto 25px auto;
            display: block;
            filter: drop-shadow(0px 8px 15px rgba(0,0,0,0.5));
            border-radius: 12px;
        }

        .auth-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: #fff;
            letter-spacing: -0.5px;
        }

        .auth-input {
            background: rgba(15, 23, 42, 0.5) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
            padding: 12px 15px !important;
            border-radius: 12px !important;
            transition: all 0.3s ease;
        }

        .auth-input:focus {
            background: rgba(15, 23, 42, 0.8) !important;
            border-color: var(--color-primary) !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3) !important;
        }
        
        .auth-input::placeholder {
            color: rgba(255,255,255,0.4);
        }

        .auth-btn {
            background: linear-gradient(135deg, var(--color-primary), #3b82f6);
            color: #fff;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.05rem;
            margin-top: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4);
        }

        .auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(37, 99, 235, 0.5);
        }

        .auth-links {
            margin-top: 25px;
        }

        .auth-links a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .auth-links a:hover {
            color: #fff;
        }
        
        .form-check-label {
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
        }
        
        .auth-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

    </style>
</head>

<body>

    <div class="premium-bg"></div>
    <div class="guest-wrapper {{ isset($isCatalog) && $isCatalog ? 'flex-column align-items-stretch pt-0 px-0 w-100' : 'align-items-center justify-content-center px-2 px-md-4' }}">
        <div class="auth-container {{ isset($isBroad) && $isBroad ? 'broad' : '' }}">
            @yield('content')
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
