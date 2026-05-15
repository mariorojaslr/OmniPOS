<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OmniPOS | Partner Suite</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --oled-black: #000000;
            --reseller-cyan: #22d3ee;
            --reseller-purple: #a855f7;
        }

        body { 
            font-family: 'Outfit', sans-serif; 
            background: var(--oled-black);
            color: #e2e8f0; 
            min-height: 100vh;
            margin: 0;
        }

        /* ===== RESELLER NAVBAR ===== */
        .reseller-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(0, 0, 0, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(34, 211, 238, 0.2);
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }
        .reseller-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .reseller-brand-logo {
            width: 34px; height: 34px; border-radius: 10px;
            object-fit: contain; background: #fff; padding: 4px;
        }
        .reseller-brand-name {
            font-size: 13px; font-weight: 800; color: #fff; letter-spacing: 3px;
        }
        .reseller-brand-sub {
            font-size: 8px; font-weight: 700; color: var(--reseller-cyan); letter-spacing: 4px; text-transform: uppercase;
        }

        .reseller-nav-links {
            display: flex; align-items: center; gap: 4px;
            background: rgba(255,255,255,0.03); padding: 4px; border-radius: 14px;
        }
        .reseller-nav-link {
            padding: 8px 18px; border-radius: 10px; font-size: 10px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 1.5px; color: #64748b; text-decoration: none;
            transition: all 0.25s ease;
        }
        .reseller-nav-link:hover { color: #fff; background: rgba(255,255,255,0.04); }
        .reseller-nav-link.active {
            color: #fff; background: rgba(34, 211, 238, 0.1); border: 1px solid rgba(34, 211, 238, 0.2);
        }

        .btn-logout {
            background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
            color: #64748b; font-size: 10px; font-weight: 800; text-transform: uppercase;
            padding: 8px 18px; border-radius: 10px; cursor: pointer; transition: all 0.25s ease;
        }
        .btn-logout:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

        main { width: 100%; }
    </style>
    @yield('styles')
</head>
<body>

    <nav class="reseller-nav">
        <a href="{{ route('revendedor.dashboard') }}" class="reseller-brand">
            <img src="{{ asset('images/logo_omnipos.png') }}" alt="OmniPOS" class="reseller-brand-logo">
            <div class="reseller-brand-text d-flex flex-column" style="line-height: 1.2;">
                <span class="reseller-brand-name">OMNIPOS</span>
                <span class="reseller-brand-sub">Partner Suite</span>
            </div>
        </a>

        <div class="reseller-nav-links">
            <a href="{{ route('revendedor.dashboard') }}" class="reseller-nav-link {{ request()->routeIs('revendedor.dashboard') ? 'active' : '' }}">
                Cartera
            </a>
            <a href="#" class="reseller-nav-link">Comisiones</a>
            <a href="#" class="reseller-nav-link">Soporte</a>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn-logout">Salir</button>
        </form>
    </nav>

    <main>
        @yield('content')
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
