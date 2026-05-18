<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tarjeta de Cama QR — {{ $bed->nombre }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            padding: 40px;
        }

        .qr-card-container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 4px solid #10b981; /* Emerald border representing clinica check */
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.06);
            overflow: hidden;
            padding: 35px;
            text-align: center;
            position: relative;
        }

        .qr-card-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 10px;
            background: linear-gradient(90deg, #10b981, #3b82f6);
        }

        .logo-clinic {
            height: 50px;
            object-fit: contain;
            margin-bottom: 15px;
        }

        .bed-title {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .room-subtitle {
            font-size: 1.1rem;
            color: #475569;
            font-weight: 600;
            margin-bottom: 25px;
        }

        .qr-wrapper {
            background-color: #f1f5f9;
            padding: 20px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 25px;
            border: 2px dashed #cbd5e1;
        }

        .qr-image {
            width: 240px;
            height: 240px;
            display: block;
        }

        .badge-security {
            background-color: rgba(16, 185, 129, 0.1);
            color: #059669;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 20px;
        }

        .instruction-text {
            font-size: 0.85rem;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 0;
        }

        /* Print styling rules */
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            .qr-card-container {
                box-shadow: none;
                border: 4px solid #10b981;
                margin-top: 50px;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="qr-card-container">
        
        <!-- Header -->
        @if($empresa->config && $empresa->config->logo_url)
            <img src="{{ $empresa->config->logo_url }}" class="logo-clinic">
        @else
            <div class="fw-bold fs-4 text-emerald mb-2">🏥 MedPlus</div>
        @endif
        
        <div class="bed-title">{{ $bed->nombre }}</div>
        <div class="room-subtitle">Habitación: {{ $bed->room?->nombre ?? 'Sin Asignar' }}</div>

        <!-- QR Code Wrapper -->
        <div class="qr-wrapper shadow-sm">
            @php
                $verificationUrl = request()->getSchemeAndHttpHost() . '/portal/profesional/escanear-cama/' . $bed->qr_token;
                $qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($verificationUrl);
            @endphp
            <img src="{{ $qrApiUrl }}" class="qr-image" alt="Código QR de Cama Clinica">
        </div>

        <div>
            <span class="badge-security">
                🛡️ Control de Presencia Física Validado
            </span>
        </div>

        <p class="instruction-text">
            <strong>Instrucciones para el personal:</strong><br>
            Abra la cámara del móvil o el lector del portal de guardia y escanee este código QR para registrar su marca de presencia obligatoria y ver la ficha clínica del paciente.
        </p>

    </div>

    <div class="text-center mt-4 no-print">
        <button onclick="window.print()" class="btn btn-emerald btn-lg px-4 py-2 fw-bold text-white rounded-pill" style="background-color: #10b981; border-color: #10b981;">
            🖨️ IMPRIMIR TARJETA DE CAMA
        </button>
    </div>

</body>
</html>
