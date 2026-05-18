<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $convention->nombre }} — Portal B2B de Verificación Clínica</title>
    
    <!-- CSS Stack (Bootstrap 5, Icons, Google Fonts) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --bg-oled: #000000;
            --card-oled: #0b0f19;
            --border-oled: #1e293b;
            --accent-emerald: #10b981;
            --accent-blue: #3b82f6;
            --text-primary: #f3f4f6;
            --text-secondary: #94a3b8;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-oled);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        .navbar-oled {
            background-color: rgba(11, 15, 25, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-oled);
        }

        .card-oled-glow {
            background-color: var(--card-oled);
            border: 1px solid var(--border-oled);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .card-oled-glow:focus-within {
            border-color: rgba(16, 185, 129, 0.4);
            box-shadow: 0 0 25px rgba(16, 185, 129, 0.15);
        }

        .form-control-oled {
            background-color: #020617;
            border: 1px solid var(--border-oled);
            color: var(--text-primary);
            border-radius: 50px;
            padding: 14px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .form-control-oled:focus {
            background-color: #0b0f19;
            border-color: var(--accent-emerald);
            color: var(--text-primary);
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.3);
        }

        .btn-search-oled {
            background: linear-gradient(135deg, var(--accent-emerald), #059669);
            border: none;
            color: white;
            font-weight: 700;
            border-radius: 50px;
            padding: 14px 30px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-search-oled:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.4);
        }

        .badge-verified {
            background-color: rgba(16, 185, 129, 0.15);
            border: 1px solid var(--accent-emerald);
            color: var(--accent-emerald);
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            animation: soft-pulse-green 2s infinite;
        }

        .badge-expired {
            background-color: rgba(239, 68, 68, 0.15);
            border: 1px solid #ef4444;
            color: #ef4444;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .cert-result-card {
            background: linear-gradient(145deg, #0f172a, #020617);
            border: 1px solid var(--border-oled);
            border-radius: 20px;
            padding: 24px;
            transition: all 0.3s ease;
        }

        .cert-result-card:hover {
            border-color: rgba(59, 130, 246, 0.3);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.1);
            transform: translateY(-2px);
        }

        .stamp-medical {
            border: 2px dashed rgba(16, 185, 129, 0.3);
            background-color: rgba(16, 185, 129, 0.03);
            padding: 12px 18px;
            border-radius: 12px;
            display: inline-block;
        }

        @keyframes soft-pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.2); }
            70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .footer-oled {
            margin-top: auto;
            border-top: 1px solid var(--border-oled);
            background-color: #020617;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-dark navbar-oled py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <span class="fs-3">🏥</span>
                <div>
                    <h5 class="mb-0 fw-extrabold text-white tracking-wide">MedPlus B2B</h5>
                    <span class="x-small text-secondary" style="font-size: 0.75rem;">Portal de Verificación de Convenios</span>
                </div>
            </div>
            <div class="text-end">
                <span class="badge bg-secondary text-white py-1 px-3 small rounded-pill fw-bold border border-secondary border-opacity-50">
                    🤝 {{ $convention->nombre }}
                </span>
            </div>
        </div>
    </nav>

    <!-- MAIN BODY -->
    <div class="container my-5" style="max-width: 800px;">
        
        <!-- INTRO DUCCIÓN -->
        <div class="text-center mb-5">
            <span class="badge bg-dark border border-secondary border-opacity-25 text-success py-2 px-4 rounded-pill fw-bold mb-3">
                🛡️ CONSULTA OFICIAL EN TIEMPO REAL
            </span>
            <h2 class="fw-extrabold text-white mb-2 fs-1 tracking-tight">Validación Digital de Aptitud Médica</h2>
            <p class="text-muted mx-auto" style="max-width: 550px;">
                Ingrese el número de DNI o documento del paciente para consultar al instante la vigencia, aptitud médica, observaciones y firmas de sus certificados emitidos.
            </p>
        </div>

        <!-- SEARCH CARD -->
        <div class="card card-oled-glow mb-5">
            <div class="card-body p-4 p-md-5">
                <form id="search_form" onsubmit="performSearch(event)">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="position-relative">
                                <span class="position-absolute top-50 start-0 translate-middle-y ps-4 text-secondary"><i class="bi bi-person-vcard fs-5"></i></span>
                                <input type="text" id="document_input" class="form-control form-control-oled ps-5" placeholder="Escriba el número de DNI del paciente..." required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-search-oled w-100 py-3">
                                <i class="bi bi-search me-2"></i> Consultar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- RESULTS CONTAINER -->
        <div id="results_wrapper">
            <!-- Loader o Estado Inicial -->
            <div id="status_message" class="text-center py-5 text-muted card-oled-glow p-4">
                <i class="bi bi-shield-check display-3 text-secondary opacity-25 d-block mb-3"></i>
                <p class="mb-0 fw-medium">Esperando consulta de DNI en sistema...</p>
            </div>

            <div id="certificates_list" class="d-none">
                <!-- Se inyectan dinámicamente -->
            </div>
        </div>

    </div>

    <!-- FOOTER -->
    <footer class="footer-oled py-4 text-center text-muted">
        <div class="container">
            <p class="x-small mb-1" style="font-size: 0.8rem;">
                © 2026 MedPlus SaaS Multiempresa. Todos los derechos reservados.
            </p>
            <p class="x-small text-secondary mb-0" style="font-size: 0.7rem;">
                Aislamiento e inmutabilidad garantizada por token seguro de inquilino.
            </p>
        </div>
    </footer>

    <!-- WEB AUDIO CHIMES AND AJAX SEARCH SCRIPT -->
    <script>
        // Web Audio API para reproducir chimes clínicos premium
        function playChime(freq, type, duration) {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();

                oscillator.type = type;
                oscillator.frequency.value = freq;

                gainNode.gain.setValueAtTime(0.01, audioCtx.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(duration, audioCtx.currentTime + 0.05);
                gainNode.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.8);

                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);

                oscillator.start();
                oscillator.stop(audioCtx.currentTime + 1.0);
            } catch (e) {
                console.warn('Audio Context no soportado o bloqueado por el navegador.', e);
            }
        }

        // Consultar DNI en servidor
        function performSearch(event) {
            event.preventDefault();

            const documentVal = document.getElementById('document_input').value.trim();
            const listContainer = document.getElementById('certificates_list');
            const statusMsg = document.getElementById('status_message');

            statusMsg.innerHTML = `
                <div class="spinner-border text-success mb-3" role="status"></div>
                <p class="mb-0 text-secondary fw-semibold">Consultando base de datos oficial de MedPlus...</p>
            `;
            listContainer.classList.add('d-none');
            listContainer.innerHTML = '';

            fetch(`/portal/agencia/{{ $convention->token_acceso }}/buscar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ document: documentVal })
            })
            .then(response => response.json())
            .then(res => {
                if (res.success && res.certificates && res.certificates.length > 0) {
                    // Sonar Chime Ascendente de Éxito
                    playChime(523.25, 'sine', 0.2); // C5
                    setTimeout(() => playChime(659.25, 'sine', 0.2), 100); // E5
                    setTimeout(() => playChime(783.99, 'sine', 0.3), 200); // G5

                    statusMsg.innerHTML = '';
                    listContainer.classList.remove('d-none');

                    res.certificates.forEach(cert => {
                        const badgeHtml = cert.es_vigente 
                            ? `<span class="badge-verified"><i class="bi bi-shield-fill-check me-1"></i> APROBADO & VIGENTE</span>` 
                            : `<span class="badge-expired"><i class="bi bi-exclamation-octagon-fill me-1"></i> VENCIDO / EXPIRADO</span>`;

                        const downloadBtn = cert.download_url
                            ? `<a href="${cert.download_url}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3 fw-bold"><i class="bi bi-printer-fill me-1"></i> Ver/Imprimir Certificado</a>`
                            : '';

                        const certHtml = `
                            <div class="cert-result-card mb-4">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                                    <div>
                                        <span class="badge bg-secondary rounded py-1 px-2 text-uppercase mb-2" style="font-size: 0.65rem; letter-spacing: 0.5px;">${cert.tipo}</span>
                                        <h4 class="fw-bold text-white mb-0 mt-1">${cert.paciente_nombre}</h4>
                                        <span class="x-small text-muted font-monospace">DNI: ${cert.paciente_documento}</span>
                                    </div>
                                    <div class="text-end">
                                        ${badgeHtml}
                                        <span class="x-small text-muted d-block mt-2 font-monospace" style="font-size: 0.7rem;">Cód: ${cert.codigo}</span>
                                    </div>
                                </div>

                                <div class="p-3 bg-dark bg-opacity-25 rounded-3 mb-3 border border-secondary border-opacity-10">
                                    <h6 class="fw-bold text-secondary mb-2 small text-uppercase">Dictamen de Valoración Médica:</h6>
                                    <p class="text-light small mb-0 lh-base font-monospace">${cert.contenido}</p>
                                </div>

                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 pt-2 border-top border-secondary border-opacity-10">
                                    <div class="stamp-medical">
                                        <div class="fw-bold text-success small" style="font-size: 0.75rem;"><i class="bi bi-check-circle-fill me-1"></i> Firma Digital Validada</div>
                                        <div class="text-white small fw-semibold mt-1" style="font-size: 0.8rem;">${cert.doctor_nombre}</div>
                                        <div class="x-small text-secondary" style="font-size: 0.7rem;">${cert.doctor_especialidad} — Mat. ${cert.doctor_matricula}</div>
                                    </div>
                                    <div class="text-end d-flex flex-column gap-2 align-items-end">
                                        <div class="x-small text-muted" style="font-size: 0.75rem;">
                                            Vence: <strong class="${cert.es_vigente ? 'text-success' : 'text-danger'}">${cert.vigencia}</strong>
                                        </div>
                                        ${downloadBtn}
                                    </div>
                                </div>
                            </div>
                        `;
                        listContainer.innerHTML += certHtml;
                    });

                } else {
                    // Sonar chime bajo de error o advertencia
                    playChime(220, 'triangle', 0.4);

                    statusMsg.innerHTML = `
                        <div class="card-body py-5">
                            <i class="bi bi-shield-slash-fill display-3 text-danger opacity-50 d-block mb-3"></i>
                            <h5 class="fw-bold text-white mb-2">Consulta sin Resultados</h5>
                            <p class="text-muted mx-auto mb-0" style="max-width: 450px;">
                                ${res.message || 'No se han encontrado certificados válidos o aprobados emitidos por MedPlus para el DNI consultado.'}
                            </p>
                        </div>
                    `;
                }
            })
            .catch(err => {
                console.error(err);
                playChime(220, 'triangle', 0.4);
                statusMsg.innerHTML = `
                    <div class="card-body py-5">
                        <i class="bi bi-exclamation-triangle-fill display-3 text-warning opacity-50 d-block mb-3"></i>
                        <h5 class="fw-bold text-white mb-2">Error de Red</h5>
                        <p class="text-muted mb-0">No se pudo establecer conexión con el servidor central de MedPlus.</p>
                    </div>
                `;
            });
        }
    </script>
</body>
</html>
