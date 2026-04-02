<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MultiPOS | Optimización en curso</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --oled-black: #000000;
            --accent-indigo: #6366f1;
            --accent-blue: #3b82f6;
        }

        body {
            background-color: var(--oled-black);
            color: #fff;
            font-family: 'Outfit', sans-serif;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
        }

        .container {
            text-align: center;
            padding: 2rem;
            max-width: 600px;
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-container {
            margin-bottom: 2.5rem;
            position: relative;
            display: inline-block;
        }

        .logo-main {
            max-height: 50px;
            position: relative;
            z-index: 2;
        }

        .glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
            animation: pulse-glow 3s infinite;
        }

        @keyframes pulse-glow {
            0%, 100% { opacity: 0.5; transform: translate(-50%, -50%) scale(1); }
            50% { opacity: 1; transform: translate(-50%, -50%) scale(1.2); }
        }

        h1 {
            font-weight: 800;
            font-size: 2.5rem;
            letter-spacing: -1px;
            margin-bottom: 1rem;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p {
            color: #94a3b8;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 3rem;
            font-weight: 300;
        }

        .badge-system {
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.2);
            color: var(--accent-indigo);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
        }

        .wave-indicator {
            width: 8px;
            height: 8px;
            background: var(--accent-indigo);
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 10px var(--accent-indigo);
            animation: blink 2s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .btn-action {
            background: white;
            color: black;
            text-decoration: none;
            padding: 14px 40px;
            border-radius: 14px;
            font-weight: 800;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(255, 255, 255, 0.1);
            display: inline-block;
        }

        .btn-action:hover {
            background: var(--accent-indigo);
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.3);
        }

        .support-id {
            margin-top: 4rem;
            font-family: monospace;
            font-size: 0.8rem;
            color: #1e293b;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="logo-container">
            <div class="glow"></div>
            <img src="{{ asset('images/logo_premium.png') }}" alt="MultiPOS" class="logo-main text-white">
        </div>

        <br>

        <div class="badge-system">
            <span class="wave-indicator"></span> SISTEMA DE MONITOREO ACTIVO
        </div>

        <h1>Ajuste de Precisión en Curso</h1>
        
        <p>
            Hemos detectado un comportamiento inusual. No te preocupes, el Sistema de Soporte ya recibió el informe detallado y estamos trabajando para que todo brille de nuevo en segundos. 🤝✨
        </p>

        <a href="{{ url('/') }}" class="btn-action">Volver al inicio</a>

        <div class="support-id">
            ESTADO: AUTO-REPORTADO | ID: {{ now()->timestamp }}
        </div>
    </div>

</body>
</html>
