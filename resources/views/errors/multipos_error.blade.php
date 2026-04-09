<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MultiPOS | @yield('title')</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    
    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --oled-black: #050505;
            --deep-gray: #121212;
            --accent-primary: #6366f1; /* Indigo Modern */
            --accent-secondary: #0ea5e9; /* Sky Blue */
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--oled-black);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Ambient Background Glows */
        .glow-1 {
            position: absolute;
            top: -10%;
            right: -10%;
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
        }

        .glow-2 {
            position: absolute;
            bottom: -10%;
            left: -10%;
            width: 40vw;
            height: 40vw;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
        }

        .error-card {
            position: relative;
            z-index: 10;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            padding: 3.5rem;
            border-radius: 32px;
            max-width: 550px;
            width: 90%;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: cardEnter 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes cardEnter {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .icon-box {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2.5rem;
            font-size: 2.2rem;
            color: white;
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.3);
            position: relative;
        }

        .icon-box::after {
            content: '';
            position: absolute;
            inset: -4px;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            border-radius: 28px;
            opacity: 0.3;
            filter: blur(12px);
            z-index: -1;
        }

        h1 {
            font-size: 2.8rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            margin-bottom: 1.2rem;
            background: linear-gradient(to bottom, #ffffff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p {
            font-size: 1.15rem;
            color: var(--text-muted);
            line-height: 1.7;
            margin-bottom: 3rem;
            font-weight: 300;
        }

        .btn-safety {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: white;
            color: black;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 18px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(255, 255, 255, 0.1);
        }

        .btn-safety:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(99, 102, 241, 0.4);
            background: var(--accent-primary);
            color: white;
        }

        .status-badge {
            margin-top: 3.5rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .pulse {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            box-shadow: 0 0 10px #22c55e;
            animation: blink 2s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.1); }
        }

        .support-ref {
            margin-top: 1.5rem;
            font-family: monospace;
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.1);
            letter-spacing: 2px;
        }
    </style>
</head>
<body>

    <div class="glow-1"></div>
    <div class="glow-2"></div>

    <div class="error-card">
        <div class="icon-box">
            @yield('icon')
        </div>

        <h1>@yield('head')</h1>
        
        <p>@yield('message')</p>

        <a href="{{ url('/') }}" class="btn-safety">
            <i class="bi bi-shield-check"></i> Volver a Zona Segura
        </a>

        <div class="status-badge">
            <div class="pulse"></div> @yield('status', 'Monitoreo de Seguridad Activo')
        </div>

        <div class="support-ref">
            REF: {{ strtoupper(substr(md5(now()), 0, 8)) }}
        </div>
    </div>

</body>
</html>
