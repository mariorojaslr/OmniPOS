<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>📺 Pantalla de Sala de Espera — {{ $empresa->nombre_comercial }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #000000;
            color: #ffffff;
            height: 100vh;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }

        .header-tv {
            height: 10vh;
            border-bottom: 2px solid rgba(255, 255, 255, 0.08);
            background: #090d16;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
        }

        .main-container-tv {
            height: 72vh;
            display: flex;
            flex-direction: row;
        }

        .ambient-panel {
            width: 65%;
            height: 100%;
            padding: 20px 10px 20px 20px;
        }

        .video-container {
            height: 100%;
            width: 100%;
            overflow: hidden;
            border-radius: 24px;
            border: 2px solid rgba(255, 255, 255, 0.08);
            background-color: #000000;
        }

        .queue-panel {
            width: 35%;
            height: 100%;
            background-color: #000000;
            padding: 20px 20px 20px 10px;
            display: flex;
            flex-direction: column;
        }

        .scrollable-history {
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.1) transparent;
        }
        .scrollable-history::-webkit-scrollbar {
            width: 6px;
        }
        .scrollable-history::-webkit-scrollbar-track {
            background: transparent;
        }
        .scrollable-history::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
        }

        .full-width-ticker {
            height: 18vh;
            width: 100%;
            background: #000000; /* OLED Deep Black */
            border-top: 2px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            overflow: hidden;
            position: relative;
            padding: 0;
        }

        .ticker-wrap {
            width: 100%;
            overflow: hidden;
            white-space: nowrap;
            display: flex;
            align-items: center;
        }

        .ticker-content {
            display: inline-flex;
            align-items: center;
            animation: marquee 50s linear infinite;
        }

        .ticker-item {
            display: inline-flex;
            align-items: center;
            padding-right: 150px;
        }

        .ticker-badge {
            font-size: 1.5rem;
            font-weight: 800;
            color: #ffc107;
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.3);
            text-transform: uppercase;
            padding: 10px 24px;
            border-radius: 14px;
            margin-right: 25px;
            letter-spacing: 2px;
        }

        .ticker-text {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ffffff;
        }

        @keyframes marquee {
            0% { transform: translate3d(0, 0, 0); }
            100% { transform: translate3d(-50%, 0, 0); }
        }

        .card-turno {
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            transition: all 0.3s ease;
        }

        /* Tarjeta de Alerta de Llamado Activo */
        .card-llamado-grande {
            background: #090d16;
            border: 4px solid #ffc107;
            border-radius: 22px;
            box-shadow: 0 0 35px rgba(255, 193, 7, 0.18);
            animation: alertGlow 1.8s infinite alternate ease-in-out;
        }

        @keyframes alertGlow {
            from { border-color: #ffc107; box-shadow: 0 0 20px rgba(255, 193, 7, 0.1); }
            to { border-color: #10b981; box-shadow: 0 0 45px rgba(16, 185, 129, 0.28); }
        }

        /* ANIMACIONES */
        .animate-pulse {
            animation: pulse 1.8s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.4); }
            70% { transform: scale(1.02); box-shadow: 0 0 0 15px rgba(255, 193, 7, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
        }

        .animate-bounce-custom {
            animation: bounceCustom 0.8s infinite alternate ease-in-out;
        }
        @keyframes bounceCustom {
            from { transform: translateY(0); }
            to { transform: translateY(-8px); }
        }

        .glass-unlock-card {
            background: rgba(15, 23, 42, 0.88);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 28px;
            padding: 45px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
            max-width: 620px;
            width: 90%;
        }
    </style>
</head>
<body>

    <!-- OVERLAY OBLIGATORIO DE DESBLOQUEO DE AUDIO (CUMPLIMIENTO AUTOPLAY BROWSER) -->
    <div id="audio_unlock_overlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="background: #000000; z-index: 9999999; transition: opacity 0.5s ease;">
        <div class="glass-unlock-card text-center">
            <span class="display-1 mb-4 d-block animate-bounce-custom">🔊</span>
            <h2 class="fw-extrabold text-white mb-3">Pantalla de Sala de Espera Activa</h2>
            <p class="text-secondary fs-5 mb-4">Para dar cumplimiento a las políticas de seguridad de reproducción automática de los navegadores, haga clic en el botón inferior para activar los llamados acústicos y por voz.</p>
            <button class="btn btn-warning btn-lg rounded-pill px-5 py-3 fw-bold text-dark fs-5 shadow" onclick="unlockAudioAndStart()">
                📺 INICIAR MONITOR DE SALA
            </button>
        </div>
    </div>

    <!-- TV HEADER -->
    <div class="header-tv">
        <div class="d-flex align-items-center gap-3">
            <span class="fs-3">🏥</span>
            <h2 class="mb-0 fw-bold tracking-wide text-white">{{ $empresa->nombre_comercial }}</h2>
        </div>
        <div class="d-flex align-items-center gap-4">
            <span class="badge bg-success px-4 py-2 rounded-pill fw-bold text-uppercase fs-6 tracking-wide shadow-sm">
                ● Sala de Espera Activa
            </span>
            <h2 id="clock_tv" class="mb-0 fw-extrabold text-white font-monospace fs-2">00:00:00</h2>
        </div>
    </div>

    <!-- MAIN CONTAINER (72vh): Split Screen with video on left (65%) and history/alert on right (35%) -->
    <div class="main-container-tv row g-0">
        <!-- Left Panel: 65% width with YouTube Playlist Player -->
        <div class="ambient-panel">
            @php
                $playlistId = $empresa->config_pasarelas['youtube_playlist_id'] ?? 'PLUw7Nsql2TwQrUwKitNw4OIoxYAzEWczn';
            @endphp
            <div class="video-container shadow-sm">
                <iframe 
                    id="tv_youtube_player"
                    class="w-100 h-100 border-0" 
                    src="https://www.youtube.com/embed/videoseries?list={{ $playlistId }}&autoplay=1&mute=1&loop=1&controls=1&modestbranding=1&rel=0&enablejsapi=1" 
                    allow="autoplay; encrypted-media" 
                    allowfullscreen>
                </iframe>
            </div>
        </div>

        <!-- Right Panel: 35% width with queue list (scrollable) -->
        <div class="queue-panel">
            <h4 class="fw-bold border-bottom border-secondary border-opacity-25 pb-2 text-light uppercase text-center letter-spacing mb-2 fs-5">
                🔊 Historial de Llamados Recientes
            </h4>
            <div id="historics_tv_list" class="d-flex flex-column gap-2 flex-grow-1 scrollable-history pr-1">
                <!-- Se alimenta dinámicamente -->
                <div class="text-center py-5 text-white opacity-75 fs-5">
                    🛋️ Esperando llamados del día...
                </div>
            </div>
        </div>
    </div>

    <!-- BOTTOM FULL-WIDTH TICKER (18vh): Spans the whole screen width, underneath both panels -->
    @php
        $tTitle1 = $empresa->config_pasarelas['ticker_title_1'] ?? '📇 Tenga su DNI Listo';
        $tDesc1  = $empresa->config_pasarelas['ticker_desc_1'] ?? 'Entregue su documento nacional de identidad en recepción para registrar su ingreso.';
        $tTitle2 = $empresa->config_pasarelas['ticker_title_2'] ?? '📁 Digitalice sus Estudios';
        $tDesc2  = $empresa->config_pasarelas['ticker_desc_2'] ?? 'Presente radiografías, PDF o análisis previos a la secretaria para cargarlos de inmediato.';
        $tTitle3 = $empresa->config_pasarelas['ticker_title_3'] ?? '🧼 Cuidado e Higiene';
        $tDesc3  = $empresa->config_pasarelas['ticker_desc_3'] ?? 'Utilice el dispenser de alcohol en gel disponible en los pasillos al entrar y salir.';
    @endphp
    <div class="full-width-ticker">
        <div class="ticker-wrap">
            <div class="ticker-content">
                <div class="ticker-item">
                    <span class="ticker-badge">{{ $tTitle1 }}</span>
                    <span class="ticker-text">{{ $tDesc1 }}</span>
                </div>
                <div class="ticker-item">
                    <span class="ticker-badge">{{ $tTitle2 }}</span>
                    <span class="ticker-text">{{ $tDesc2 }}</span>
                </div>
                <div class="ticker-item">
                    <span class="ticker-badge">{{ $tTitle3 }}</span>
                    <span class="ticker-text">{{ $tDesc3 }}</span>
                </div>
                <!-- Duplicamos los ítems para asegurar un loop infinito continuo y perfecto -->
                <div class="ticker-item">
                    <span class="ticker-badge">{{ $tTitle1 }}</span>
                    <span class="ticker-text">{{ $tDesc1 }}</span>
                </div>
                <div class="ticker-item">
                    <span class="ticker-badge">{{ $tTitle2 }}</span>
                    <span class="ticker-text">{{ $tDesc2 }}</span>
                </div>
                <div class="ticker-item">
                    <span class="ticker-badge">{{ $tTitle3 }}</span>
                    <span class="ticker-text">{{ $tDesc3 }}</span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Reloj en Cabecera
        function updateClockTV() {
            const now = new Date();
            document.getElementById('clock_tv').innerText = now.toTimeString().split(' ')[0];
        }
        setInterval(updateClockTV, 1000);
        updateClockTV();

        // Variables de Control
        let audioActive = false;
        let lastCalledKey = null;
        let isAlertShowing = false;
        let pollInterval = null;

        // Desbloquear Audio al primer clic del usuario
        function unlockAudioAndStart() {
            try {
                // Crear y desbloquear AudioContext
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }
                
                // Inicializar síntesis de voz con texto vacío para activarla en el TV
                if ('speechSynthesis' in window) {
                    window.speechSynthesis.cancel();
                    const dummyUtterance = new SpeechSynthesisUtterance('');
                    window.speechSynthesis.speak(dummyUtterance);
                }

                audioActive = true;

                // Tono acústico corto de confirmación (desbloqueo exitoso)
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.value = 523.25; // C5
                gain.gain.setValueAtTime(0.01, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.2, audioCtx.currentTime + 0.05);
                gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.4);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.5);

            } catch (e) {
                console.warn('Fallo al desbloquear audio.', e);
            }

            // Ocultar Overlay con desvanecimiento
            const overlay = document.getElementById('audio_unlock_overlay');
            overlay.style.opacity = '0';
            setTimeout(() => {
                overlay.remove();
                
                // Intentar reproducir video explícitamente en el navegador del TV
                // Iniciar Polling de llamados
                pollActiveCall();
                pollInterval = setInterval(pollActiveCall, 3000);
            }, 500);
        }

        // Emitir tono acústico de llamado clásico de hospital (A5 -> C6)
        function playSmartTVChime() {
            if (!audioActive) return;
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const freqs = [880.00, 1046.50];
                freqs.forEach((freq, idx) => {
                    setTimeout(() => {
                        const osc = audioCtx.createOscillator();
                        const gain = audioCtx.createGain();
                        osc.type = 'triangle';
                        osc.frequency.value = freq;
                        
                        gain.gain.setValueAtTime(0.01, audioCtx.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.35, audioCtx.currentTime + 0.05);
                        gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.9);
                        
                        osc.connect(gain);
                        gain.connect(audioCtx.destination);
                        osc.start();
                        osc.stop(audioCtx.currentTime + 1.1);
                    }, idx * 220);
                });
            } catch(e) {
                console.warn('Web Audio no disponible.', e);
            }
        }

        // Síntesis de Voz en Español Nativa (Text-To-Speech)
        function speakLlamado(paciente, doctor, consultorio) {
            if (!audioActive || !('speechSynthesis' in window)) return;
            try {
                // Cancelar cualquier audio de voz previo
                window.speechSynthesis.cancel();
                
                // Construir mensaje amigable
                const textoLlamado = `Paciente: ${paciente}. Por favor, diríjase al ${consultorio} de el doctor ${doctor}.`;
                
                const utterance = new SpeechSynthesisUtterance(textoLlamado);
                utterance.lang = 'es-ES'; // Idioma Español
                utterance.rate = 0.88;    // Velocidad ligeramente pausada para máxima claridad
                utterance.pitch = 1.0;    // Tono neutro natural
                
                // Intentar buscar una voz en español del dispositivo
                const voices = window.speechSynthesis.getVoices();
                const esVoice = voices.find(v => v.lang.startsWith('es'));
                if (esVoice) {
                    utterance.voice = esVoice;
                }
                
                // Reproducir llamado por voz
                window.speechSynthesis.speak(utterance);
            } catch(e) {
                console.warn('Error en la síntesis de voz.', e);
            }
        }

        // Consultar llamado activo vía API (Polling de 3 segundos)
        function pollActiveCall() {
            fetch('{{ url("/portal/api/turnero") }}/{{ $empresa->slug }}/activo')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        
                        // 1. GESTIONAR EVENTO DE LLAMADO SONORO/DE VOZ (SI ES RECIENTE Y NO SE HA MOSTRADO)
                        if (data.called) {
                            const callKey = `${data.paciente}_${data.doctor}_${data.consultorio}_${data.called_at}`;
                            
                            if (callKey !== lastCalledKey && !isAlertShowing) {
                                lastCalledKey = callKey;
                                isAlertShowing = true;

                                // Disparar campana sonora y voz
                                playSmartTVChime();
                                setTimeout(() => {
                                    speakLlamado(data.paciente, data.doctor, data.consultorio || 'BOX 1');
                                }, 1200);

                                // Desactivar el estado animado de alerta llamando tras 15 segundos
                                setTimeout(() => {
                                    isAlertShowing = false;
                                    pollActiveCall(); // Refrescar renderizado inmediatamente
                                }, 15000);
                            }
                        }

                        // 2. RENDERIZAR LISTA UNIFICADA EN LA COLUMNA DERECHA
                        const list = document.getElementById('historics_tv_list');
                        list.innerHTML = '';

                        if (data.historicos && data.historicos.length > 0) {
                            data.historicos.forEach((h, idx) => {
                                const isFirst = (idx === 0);
                                const isCalling = (isFirst && isAlertShowing);

                                if (isFirst) {
                                    // La primera arriba (última llamada) es gigante y llamativa
                                    const item = `
                                        <div class="card-turno ${isCalling ? 'card-llamado-grande animate-pulse' : 'bg-dark bg-opacity-40 border border-success'} p-4 mb-3 text-start d-flex align-items-center justify-content-between" style="min-height: 220px; transition: all 0.3s ease;">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <span class="badge ${isCalling ? 'bg-warning text-dark' : 'bg-success text-white'} rounded-pill px-3 py-1 font-monospace fs-5 fw-bold animate-pulse">
                                                        ${isCalling ? '📢 LLAMANDO EN BOX' : '📍 ÚLTIMO LLAMADO'}
                                                    </span>
                                                    <span class="text-success font-monospace fs-5 fw-bold ms-auto">${h.hora} hs</span>
                                                </div>
                                                
                                                <h1 class="${isCalling ? 'text-warning' : 'text-white'} fw-extrabold text-uppercase mb-2" style="font-size: 2.5rem; line-height: 1.1; letter-spacing: 1px;">
                                                    ${h.paciente}
                                                </h1>
                                                
                                                <div class="fs-4 text-white opacity-75 mb-2">
                                                    Médico: <strong class="text-info">Dr. ${h.doctor}</strong>
                                                </div>
                                                
                                                <div class="badge bg-success py-2 px-4 rounded-pill fs-4 fw-extrabold border border-white border-opacity-10 mt-1 d-inline-flex align-items-center gap-2">
                                                    📍 ${h.consultorio || 'Box 1'}
                                                </div>
                                            </div>
                                            ${isCalling ? `
                                            <div class="animate-bounce-custom pe-3 d-none d-xl-block">
                                                <i class="bi bi-volume-up-fill text-warning" style="font-size: 4.5rem;"></i>
                                            </div>` : ''}
                                        </div>
                                    `;
                                    list.innerHTML += item;
                                } else {
                                    // Las demás llamadas históricas son más pequeñas y compactas
                                    const item = `
                                        <div class="card-turno bg-dark bg-opacity-20 border border-secondary border-opacity-20 p-3 mb-2 d-flex align-items-center justify-content-between text-start" style="min-height: 54px;">
                                            <div>
                                                <h5 class="fw-bold text-white mb-0" style="font-size: 1.35rem; text-transform: uppercase;">${h.paciente}</h5>
                                                <span class="x-small text-white opacity-75 fs-6">Dr. ${h.doctor}</span>
                                            </div>
                                            <div class="text-end d-flex align-items-center gap-2">
                                                <span class="badge bg-success bg-opacity-25 border border-success border-opacity-25 text-success rounded-pill px-3 py-1 fs-6 fw-bold">📍 ${h.consultorio || 'Box 1'}</span>
                                                <span class="x-small text-success font-monospace" style="font-size: 0.95rem;">${h.hora} hs</span>
                                            </div>
                                        </div>
                                    `;
                                    list.innerHTML += item;
                                }
                            });
                        } else {
                            list.innerHTML = `
                                <div class="text-center py-5 text-white opacity-75 fs-5">
                                    🛋️ Esperando llamados del día...
                                </div>
                            `;
                        }
                    }
                })
                .catch(err => console.error('Error al consultar llamados.', err));
        }

        // Cargar voces en segundo plano para asegurar que estén listas
        if ('speechSynthesis' in window) {
            window.speechSynthesis.getVoices();
            if (window.speechSynthesis.onvoiceschanged !== undefined) {
                window.speechSynthesis.onvoiceschanged = window.speechSynthesis.getVoices;
            }
        }
    </script>
</body>
</html>
