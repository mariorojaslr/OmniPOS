<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MedPlus — Centro de Verificación de Documentos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #030712; /* OLED Deep Dark */
            color: #f3f4f6;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .validation-card {
            background-color: #0f172a; /* Slate 900 */
            border: 2px solid #10b981; /* Neon green verified border */
            box-shadow: 0 0 35px rgba(16, 185, 129, 0.2);
            border-radius: 28px;
            max-width: 600px;
            width: 100%;
            overflow: hidden;
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-glow {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(6, 78, 59, 0.2));
            padding: 40px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(16, 185, 129, 0.2);
        }

        .verified-icon {
            font-size: 5rem;
            color: #10b981;
            text-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
            animation: scaleUp 0.5s ease-out;
        }

        @keyframes scaleUp {
            from { transform: scale(0.7); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .badge-verified {
            background-color: rgba(16, 185, 129, 0.15);
            color: #34d399;
            font-weight: 800;
            font-size: 0.8rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 8px 20px;
            border-radius: 50px;
            border: 1px solid rgba(16, 185, 129, 0.3);
            display: inline-block;
            margin-top: 15px;
        }

        .field-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #9ca3af;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .field-value {
            font-size: 0.95rem;
            color: #f3f4f6;
            font-weight: 600;
        }

        .content-box {
            background-color: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 18px;
            padding: 20px;
            font-size: 0.95rem;
            line-height: 1.6;
            color: #d1d5db;
        }

        .clinic-brand {
            font-size: 0.9rem;
            font-weight: 800;
            color: #9ca3af;
            letter-spacing: 1.5px;
        }
    </style>
</head>
<body>

    <div class="validation-card shadow-lg">
        
        <!-- Cabecera de Verificación -->
        <div class="header-glow">
            <i class="bi bi-shield-fill-check verified-icon d-block mb-3"></i>
            <h4 class="fw-bold mb-1 text-white">Documento Médico Auténtico</h4>
            <div class="clinic-brand mb-2">MEDPLUS PORTAL DE AUDITORÍA</div>
            <span class="badge-verified"><i class="bi bi-check-all"></i> Código de Verificación Validado</span>
        </div>

        <!-- Cuerpo del Registro -->
        <div class="p-4">
            
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <div class="field-label">Paciente</div>
                    <div class="field-value text-white">{{ $certificate->patient?->name }}</div>
                </div>
                <div class="col-6">
                    <div class="field-label">Documento Identidad</div>
                    <div class="field-value text-white">DNI: {{ $certificate->patient?->document ?? 'Sin DNI' }}</div>
                </div>
                <div class="col-6">
                    <div class="field-label">Médico Emisor</div>
                    <div class="field-value">Dr. {{ $certificate->doctor?->name }}</div>
                </div>
                <div class="col-6">
                    <div class="field-label">Matrícula Profesional</div>
                    <div class="field-value">M.P. {{ $certificate->doctor?->profesionalConfig?->matricula ?? '48225' }}</div>
                </div>
                <div class="col-6">
                    <div class="field-label">Fecha de Emisión</div>
                    <div class="field-value">{{ $certificate->created_at->format('d/m/Y H:i') }} hs</div>
                </div>
                <div class="col-6">
                    <div class="field-label">Vigencia Clínica</div>
                    <div class="field-value">
                        @if($certificate->vigencia_hasta)
                            Vence el {{ $certificate->vigencia_hasta->format('d/m/Y') }}
                        @else
                            Vigencia Permanente
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contenido Clínico del Certificado -->
            <div class="field-label mb-2">Contenido Clínico Certificado</div>
            <div class="content-box mb-4">
                "{{ $certificate->contenido }}"
            </div>

            <!-- Datos Generales -->
            <div class="row g-2 mb-2 text-center border-top border-secondary border-opacity-25 pt-3">
                <div class="col-12">
                    <div class="field-label">Código Digital Único</div>
                    <code class="text-success fw-bold fs-6">{{ $certificate->codigo_validacion }}</code>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="x-small text-muted mb-0" style="font-size: 0.75rem;">
                    Esta consulta de autenticidad en tiempo real está conectada de forma directa con los servidores centrales de MedPlus. Cumple con los estándares de firma digital y validez oficial.
                </p>
            </div>

        </div>

    </div>

</body>
</html>
