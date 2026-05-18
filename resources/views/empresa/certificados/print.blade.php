<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado Médico Oficial — MedPlus</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #ffffff;
            color: #000000;
            margin: 0;
            padding: 40px;
        }

        .cert-frame {
            border: 4px double #000000;
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
            position: relative;
        }

        .cert-header {
            text-align: center;
            border-bottom: 2px solid #000000;
            padding-bottom: 20px;
            margin-bottom: 35px;
        }

        .clinic-title {
            font-size: 28pt;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 0;
        }

        .clinic-subtitle {
            font-size: 11pt;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 5px 0 0 0;
        }

        .cert-title {
            text-align: center;
            font-size: 20pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 40px;
            text-decoration: underline;
        }

        .cert-body {
            font-size: 13pt;
            line-height: 2.2;
            text-align: justify;
            text-indent: 40px;
            margin-bottom: 50px;
        }

        .cert-footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .qr-section {
            width: 150px;
            text-align: center;
            font-size: 8pt;
            line-height: 1.4;
        }

        .qr-image {
            width: 110px;
            height: 110px;
            margin-bottom: 5px;
            border: 1px solid #ddd;
            padding: 3px;
        }

        .stamp-section {
            width: 300px;
            text-align: center;
            font-size: 10pt;
        }

        .stamp-line {
            border-top: 1px solid #000000;
            margin-top: 60px;
            padding-top: 5px;
            font-weight: bold;
        }

        .security-badge {
            font-size: 8pt;
            color: #555555;
            text-align: center;
            margin-top: 30px;
            border-top: 1px dotted #ccc;
            padding-top: 10px;
        }

        @media print {
            body {
                padding: 0;
            }
            .cert-frame {
                border-color: #000000 !important;
            }
        }
    </style>
</head>
<body>

    <div class="cert-frame">
        <!-- Encabezado Clínico -->
        <div class="cert-header">
            <h1 class="clinic-title">MEDPLUS</h1>
            <p class="clinic-subtitle">Sistemas Clínicos Integrados & Medicina Prepaga</p>
            <p style="font-size: 9pt; margin: 5px 0 0 0; color: #555;">Sede Central - Reg. Sanitario N° 4022-MC</p>
        </div>

        <!-- Título Certificado -->
        <div class="cert-title">
            Certificado Médico Oficial
        </div>

        <!-- Cuerpo del Certificado -->
        <div class="cert-body">
            {{ $certificate->contenido }}
            Se extiende el presente certificado a pedido de parte interesada, en la ciudad de Buenos Aires, con fecha {{ $certificate->created_at->format('d') }} de {{ $certificate->created_at->translatedFormat('F') }} de {{ $certificate->created_at->format('Y') }}.
            @if($certificate->vigencia_hasta)
                Se hace constar expresamente que este certificado posee validez clínica e institucional hasta el día {{ $certificate->vigencia_hasta->format('d/m/Y') }}.
            @else
                Se hace constar expresamente que este certificado posee carácter permanente y validez por tiempo indefinido.
            @endif
        </div>

        <!-- Pie de página con Firma y QR -->
        <div class="cert-footer">
            <!-- Sección de Validación QR -->
            <div class="qr-section">
                <img class="qr-image" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($validationUrl) }}" alt="Código de Validación QR">
                <div style="font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;">Escanear para Verificar</div>
                <div style="color: #666; font-size: 7pt; margin-top: 2px;">Cód: {{ $certificate->codigo_validacion }}</div>
            </div>

            <!-- Firma y Sello Médico -->
            <div class="stamp-section">
                <div style="font-size: 10pt; font-style: italic; margin-bottom: 20px; color: #444;">Firmado digitalmente bajo registro MedPlus</div>
                <div class="stamp-line">
                    Dr. {{ $certificate->doctor?->name }}<br>
                    <span style="font-size: 9pt; font-weight: normal;">Médico Clínico / Generalista</span><br>
                    <span style="font-size: 9pt; font-weight: normal;">M.P. {{ $certificate->doctor?->profesionalConfig?->matricula ?? '48225' }}</span>
                </div>
            </div>
        </div>

        <!-- Aviso Legal de Seguridad -->
        <div class="security-badge">
            Este es un documento médico oficial firmado y validado de forma digital. Cualquier enmienda o raspadura anula su validez legal. La autenticidad de las firmas y el contenido vertido puede verificarse libremente escaneando el código QR seguro impreso o ingresando el código único de validación en la web oficial de validaciones de MedPlus.
        </div>
    </div>

    <!-- Script de Autoprint -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // Esperamos unos instantes para asegurar la carga completa e imprimimos
            setTimeout(() => {
                window.print();
            }, 800);
        });
    </script>
</body>
</html>
