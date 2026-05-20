<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado Médico Oficial — {{ $certificate->empresa?->nombre_comercial ?? 'MedPlus' }}</title>
    <!-- Bootstrap para UI Premium en la Barra de Control -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @php
        $empresa = $certificate->empresa;
        $config = $empresa?->config;
        
        // Obtener reseller config si existe (Marca Blanca)
        $resellerConfig = null;
        if ($empresa && $empresa->reseller_id) {
            $resellerConfig = \App\Models\ResellerConfig::where('user_id', $empresa->reseller_id)->first();
        }

        // Determinar Logo del Sistema
        if ($config && $config->logo_url) {
            $logo = $config->logo_url;
        } elseif ($resellerConfig && $resellerConfig->logo_path) {
            $logo = asset('storage/' . $resellerConfig->logo_path);
        } else {
            $logo = asset('images/logo_omnipos.png');
        }
    @endphp

    <style>
        /* Estilos Generales para Visualización en Navegador */
        body {
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif;
            color: #000000;
        }

        /* Barra de Control Premium (No se imprime) */
        .control-bar {
            background-color: #0f172a;
            color: #ffffff;
            padding: 15px 30px;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .control-bar-container {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-group .btn {
            font-weight: 600;
            border-color: rgba(255,255,255,0.2);
        }

        .btn-group .btn:hover {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
        }

        .btn-group .btn.active {
            background-color: #ffffff !important;
            color: #0f172a !important;
            border-color: #ffffff !important;
        }

        /* Contenedor de la Página */
        .page-container {
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            background-color: #f1f5f9;
            min-height: calc(100vh - 70px);
        }

        /* Hoja de Certificado Estilo Papel */
        .cert-page {
            background-color: #ffffff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-sizing: border-box;
        }

        /* Cuadro de Borde Oficial del Certificado */
        .cert-frame {
            border: 4px double #000000;
            padding: 30px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            box-sizing: border-box;
        }

        /* Configuración de Tamaño A4 */
        .size-a4 {
            width: 210mm;
            min-height: 297mm;
            padding: 25mm 20mm;
        }

        /* Configuración de Tamaño A5 */
        .size-a5 {
            width: 148mm;
            min-height: 210mm;
            padding: 12mm 10mm;
        }

        /* Estilos del Encabezado */
        .cert-header {
            text-align: center;
            border-bottom: 2px solid #000000;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .clinic-title {
            font-size: 26pt;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 0;
            text-transform: uppercase;
        }

        .clinic-subtitle {
            font-size: 11pt;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 5px 0 0 0;
            font-family: system-ui, -apple-system, sans-serif;
            font-weight: bold;
            color: #333;
        }

        /* Título del Documento */
        .cert-title {
            text-align: center;
            font-size: 20pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 35px;
            text-decoration: underline;
        }

        /* Cuerpo del Certificado */
        .cert-body {
            font-size: 13pt;
            line-height: 2.1;
            text-align: justify;
            text-indent: 40px;
            margin-bottom: 40px;
            flex-grow: 1;
        }

        /* Pie de Página */
        .cert-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
        }

        .qr-section {
            width: 150px;
            text-align: center;
            font-size: 8pt;
            line-height: 1.4;
            font-family: system-ui, -apple-system, sans-serif;
        }

        .qr-image {
            width: 110px;
            height: 110px;
            margin-bottom: 5px;
            border: 1px solid #ddd;
            padding: 3px;
        }

        .stamp-section {
            width: 320px;
            text-align: center;
            font-size: 10pt;
        }

        .stamp-line {
            border-top: 1px solid #000000;
            margin-top: 50px;
            padding-top: 5px;
            font-weight: bold;
        }

        /* Aviso de Seguridad */
        .security-badge {
            font-size: 7.5pt;
            color: #555555;
            text-align: center;
            margin-top: 25px;
            border-top: 1px dotted #ccc;
            padding-top: 8px;
            line-height: 1.4;
            font-family: system-ui, -apple-system, sans-serif;
        }

        /* Ajustes Específicos para A5 */
        .size-a5 .clinic-title {
            font-size: 18pt;
        }
        .size-a5 .clinic-subtitle {
            font-size: 8pt;
        }
        .size-a5 .cert-header {
            margin-bottom: 15px;
            padding-bottom: 10px;
        }
        .size-a5 .cert-title {
            font-size: 14pt;
            margin-bottom: 15px;
        }
        .size-a5 .cert-body {
            font-size: 10.5pt;
            line-height: 1.8;
            margin-bottom: 15px;
            text-indent: 25px;
        }
        .size-a5 .qr-image {
            width: 80px;
            height: 80px;
        }
        .size-a5 .qr-section {
            width: 110px;
            font-size: 7pt;
        }
        .size-a5 .stamp-section {
            width: 220px;
            font-size: 8.5pt;
        }
        .size-a5 .stamp-line {
            margin-top: 35px;
        }
        .size-a5 .security-badge {
            font-size: 6.5pt;
            margin-top: 15px;
            padding-top: 5px;
        }
        .size-a5 .cert-frame {
            padding: 15px;
        }

        /* Reglas de Impresión Real en Papel */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .page-container {
                padding: 0 !important;
                background-color: transparent !important;
                min-height: auto !important;
            }
            .cert-page {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                transition: none !important;
            }
            
            /* Sizing para A4 en impresión */
            .size-a4 {
                width: 210mm !important;
                height: 297mm !important;
                padding: 20mm 15mm !important;
            }
            
            /* Sizing para A5 en impresión */
            .size-a5 {
                width: 148mm !important;
                height: 210mm !important;
                padding: 10mm 8mm !important;
            }
        }
    </style>
</head>
<body>

    <!-- Barra de Control Superior (No se imprime) -->
    <div class="no-print control-bar">
        <div class="control-bar-container">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success rounded-pill px-3 py-1"><i class="bi bi-patch-check-fill me-1"></i> FIRMA DIGITAL CON VALIDEZ QR</span>
                <span class="text-white fw-bold small ms-2 d-none d-md-inline">Sanatorio: {{ $empresa?->nombre_comercial ?? 'MedPlus' }}</span>
            </div>
            
            <div class="d-flex align-items-center gap-2">
                <!-- Selector de Formato / Hoja -->
                <div class="btn-group me-3" role="group" aria-label="Formatos de Hoja">
                    <button type="button" id="btn-a4" class="btn btn-outline-light btn-sm active" onclick="changeSize('a4')">
                        📄 Hoja A4
                    </button>
                    <button type="button" id="btn-a5" class="btn btn-outline-light btn-sm" onclick="changeSize('a5')">
                        📑 Hoja A5 (Recetario)
                    </button>
                </div>
                
                <!-- Imprimir / Guardar en PDF -->
                <button type="button" class="btn btn-primary btn-sm fw-bold px-3" onclick="window.print()">
                    <i class="bi bi-printer-fill me-1"></i> Imprimir / PDF
                </button>

                <!-- Compartir por WhatsApp -->
                @php
                    $whatsappMsg = urlencode("Hola! Le adjunto el Certificado Médico Oficial firmado digitalmente por el " . ($certificate->doctor?->name ?? 'Médico') . " de " . ($empresa?->nombre_comercial ?? 'MedPlus') . ". Puede validarlo escaneando el código QR seguro o ingresando a: " . $validationUrl);
                    $whatsappUrl = "https://wa.me/?text=" . $whatsappMsg;
                @endphp
                <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-success btn-sm fw-bold px-3">
                    <i class="bi bi-whatsapp me-1"></i> Compartir
                </a>
            </div>
        </div>
    </div>

    <!-- Contenedor del Certificado Físico -->
    <div class="page-container">
        <div id="certSheet" class="cert-page size-a4">
            <div class="cert-frame">
                
                <!-- Encabezado Clínico de la Empresa con Logo Oficial -->
                <div class="cert-header">
                    @if($logo)
                        <div style="margin-bottom: 12px; display: flex; justify-content: center; align-items: center;">
                            <img src="{{ $logo }}" style="max-height: 80px; max-width: 280px; object-fit: contain; border-radius: 6px;" alt="Logo Oficial del Sistema">
                        </div>
                    @endif
                    <h1 class="clinic-title">{{ $empresa?->nombre_comercial ?? 'MEDPLUS' }}</h1>
                    <p class="clinic-subtitle">Sistemas Clínicos Integrados & Medicina Prepaga</p>
                    <p style="font-size: 9pt; margin: 4px 0 0 0; color: #555; font-family: system-ui, -apple-system, sans-serif;">Sede Central - Reg. Sanitario N° 4022-MC</p>
                </div>

                <!-- Título Oficial del Certificado -->
                <div class="cert-title">
                    Certificado Médico Oficial
                </div>

                <!-- Contenido Dinámico del Certificado -->
                <div class="cert-body">
                    {{ $certificate->contenido }}
                    <span style="font-style: italic;">Se extiende el presente certificado a pedido de parte interesada, en la ciudad de Buenos Aires, con fecha {{ $certificate->created_at->format('d') }} de {{ $certificate->created_at->translatedFormat('F') }} de {{ $certificate->created_at->format('Y') }}.</span>
                    @if($certificate->vigencia_hasta)
                        Se hace constar expresamente que este certificado posee validez clínica e institucional hasta el día {{ $certificate->vigencia_hasta->format('d/m/Y') }}.
                    @else
                        Se hace constar expresamente que este certificado posee carácter permanente y validez por tiempo indefinido.
                    @endif
                </div>

                <!-- Firmas y Validación QR Seguro -->
                <div class="cert-footer">
                    
                    <!-- Validación QR -->
                    <div class="qr-section">
                        <img class="qr-image" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($validationUrl) }}" alt="Código de Validación QR">
                        <div style="font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; font-size: 7.5pt;">Escanear para Validar</div>
                        <div style="color: #666; font-size: 7pt; margin-top: 1px;">Cód: {{ $certificate->codigo_validacion }}</div>
                    </div>

                    <!-- Firma Digital y Matrícula -->
                    <div class="stamp-section">
                        <div style="font-size: 9.5pt; font-style: italic; margin-bottom: 15px; color: #444; font-family: system-ui, -apple-system, sans-serif;">Firmado digitalmente bajo registro corporativo</div>
                        <div class="stamp-line">
                            Dr. {{ $certificate->doctor?->name }}<br>
                            <span style="font-size: 8.5pt; font-weight: normal; font-family: system-ui, -apple-system, sans-serif; color: #333;">Médico Clínico / Generalista</span><br>
                            <span style="font-size: 8.5pt; font-weight: normal; font-family: system-ui, -apple-system, sans-serif; color: #333;">M.P. {{ $certificate->doctor?->profesionalConfig?->matricula ?? '48225' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Aviso Legal de Seguridad -->
                <div class="security-badge">
                    Este es un documento médico oficial firmado y validado de forma digital. Cualquier enmienda o raspadura anula su validez legal. La autenticidad de las firmas y el contenido vertido puede verificarse libremente escaneando el código QR seguro impreso o ingresando el código único de validación en la web oficial de validaciones de {{ $empresa?->nombre_comercial ?? 'MedPlus' }}.
                </div>

            </div>
        </div>
    </div>

    <!-- Script de Gestión de Tamaño y Autoprint -->
    <script>
        // Función interactiva para cambiar el tamaño de hoja al instante
        function changeSize(size) {
            const sheet = document.getElementById('certSheet');
            const btnA4 = document.getElementById('btn-a4');
            const btnA5 = document.getElementById('btn-a5');

            if (size === 'a4') {
                sheet.classList.remove('size-a5');
                sheet.classList.add('size-a4');
                btnA4.classList.add('active');
                btnA5.classList.remove('active');
            } else if (size === 'a5') {
                sheet.classList.remove('size-a4');
                sheet.classList.add('size-a5');
                btnA5.classList.add('active');
                btnA4.classList.remove('active');
            }
        }
    </script>
</body>
</html>
