<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencia - {{ $empresa->nombre }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #000000;
            --accent-color: #007bff;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --text-primary: #ffffff;
            --text-secondary: #888888;
        }
        body {
            background-color: var(--bg-color);
            color: var(--text-primary);
            font-family: 'Outfit', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }
        .container {
            max-width: 400px;
            padding: 20px;
            text-align: center;
        }
        .card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 30px 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }
        .logo-placeholder {
            width: 80px;
            height: 80px;
            background: var(--accent-color);
            border-radius: 20px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
            box-shadow: 0 0 20px rgba(0, 123, 255, 0.3);
        }
        h1 { font-size: 24px; font-weight: 700; margin-bottom: 5px; }
        p { color: var(--text-secondary); font-size: 14px; margin-bottom: 30px; }
        
        .btn-action {
            border-radius: 18px;
            padding: 15px;
            font-weight: 600;
            font-size: 18px;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 15px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-entry { background-color: var(--success-color); color: white; }
        .btn-exit { background-color: var(--danger-color); color: white; }
        .btn-action:active { transform: scale(0.95); opacity: 0.8; }
        
        .user-badge {
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 15px;
            border-radius: 100px;
            font-size: 13px;
            display: inline-block;
            margin-bottom: 20px;
        }
        input.form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 12px;
            padding: 12px;
            text-align: center;
            margin-bottom: 20px;
        }
        input.form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--accent-color);
            color: white;
            box-shadow: none;
        }
    </style>
</head>
<body>
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success border-0 rounded-4 mb-4">
                {{ session('success') }}
            </div>
            <script>setTimeout(() => window.close(), 3000);</script>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 rounded-4 mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="logo-placeholder">
                {{ substr($empresa->nombre, 0, 1) }}
            </div>
            
            <div class="user-badge">
                👋 Hola, <strong>{{ $user->name }}</strong>
            </div>

            <h1>{{ $empresa->nombre }}</h1>
            <p>Control de Asistencia Biométrico Digital</p>

            @if(!$asistenciaActiva)
                <!-- FORMULARIO DE ENTRADA -->
                <form action="{{ route('empresa.personal.checkin') }}" method="POST">
                    @csrf
                    @if($user->esCajero())
                        <label class="text-secondary small mb-2 d-block">Monto Inicial de Caja (Vuelto)</label>
                        <input type="number" name="vuelto_inicial" class="form-control" placeholder="0.00" required step="0.01">
                    @endif
                    <button type="submit" class="btn btn-action btn-entry">
                        🟢 REGISTRAR ENTRADA
                    </button>
                </form>
            @else
                <!-- FORMULARIO DE SALIDA -->
                <form action="{{ route('empresa.personal.checkout') }}" method="POST">
                    @csrf
                    @if($user->esCajero())
                        <label class="text-secondary small mb-2 d-block">Monto Final en Caja (Total Efectivo)</label>
                        <input type="number" name="vuelto_final" class="form-control" placeholder="0.00" required step="0.01">
                    @endif
                    <button type="submit" class="btn btn-action btn-exit">
                        🔴 REGISTRAR SALIDA
                    </button>
                    <div class="text-secondary small mt-2">
                        Turno iniciado a las: <br>
                        <strong>{{ \Carbon\Carbon::parse($asistenciaActiva->entrada)->format('H:i') }} hs</strong>
                    </div>
                </form>
            @endif

            <a href="{{ route('logout.get') }}" class="btn btn-link text-secondary text-decoration-none mt-4 small">
                Cerrar Sesión
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
