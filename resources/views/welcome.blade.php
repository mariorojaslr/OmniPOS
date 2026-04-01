<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MultiPOS - El Futuro de tu Negocio Hoy</title>
    <!-- CSS Standard -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    
    <style>
        :root {
            --primary: #8b5cf6;
            --primary-dark: #6366f1;
            --accent: #10b981;
            --bg-dark: #09090b;
            --card-bg: rgba(24, 24, 27, 0.95);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #fafafa;
            --text-dim: #d1d1d6; /* MEJORADO: Más claro para legibilidad total */
            --whatsapp: #25d366;
        }

        body { background-color: var(--bg-dark); color: var(--text-main); font-family: 'Outfit', sans-serif; overflow-x: hidden; }

        .bg-mesh {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
            background: radial-gradient(circle at 0% 0%, rgba(139, 92, 246, 0.12) 0%, transparent 50%),
                        radial-gradient(circle at 100% 100%, rgba(16, 185, 129, 0.08) 0%, transparent 55%);
        }

        .navbar { background: rgba(0,0,0,0.9) !important; backdrop-filter: blur(20px); border-bottom: 1px solid var(--glass-border); padding: 1rem 0; }
        .nav-link { color: #ccc !important; font-weight: 500; transition: 0.3s; }
        .nav-link:hover { color: white !important; }
        
        .hero { padding: 10rem 0 5rem; min-height: 90vh; display: flex; align-items: center; }
        .btn-premium { padding: 1rem 2.5rem; border-radius: 50px; font-weight: 700; transition: 0.3s; text-decoration: none; border: none; }
        .btn-premium:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(139, 92, 246, 0.3); }

        .feature-card { background: var(--card-bg); border: 1px solid var(--glass-border); border-radius: 30px; padding: 3rem; transition: 0.4s; height: 100%; }
        .feature-card:hover { border-color: var(--primary); transform: translateY(-10px); }
        .feature-card i { font-size: 3rem; color: var(--primary); margin-bottom: 1.5rem; display: block; }
        .feature-card p { color: var(--text-dim); }

        .price-card { background: var(--card-bg); border: 1px solid var(--glass-border); padding: 3rem 2rem; border-radius: 30px; text-align: center; }
        .price-card.featured { border: 2px solid var(--primary); position: relative; }
        .price-card.featured::after { content: 'MÁS POPULAR'; position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: var(--primary); color: white; padding: 2px 15px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; }
        .price-card p { color: var(--text-dim); }

        #chat-window { width: 400px; height: 600px; max-height: 85vh; transform-origin: bottom right; }
        @media (max-width: 576px) { #chat-window { width: 92vw !important; height: 75vh !important; } }

        .scroll-custom::-webkit-scrollbar { width: 4px; }
        .scroll-custom::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
        
        .footer-link { color: var(--text-dim); text-decoration: none; transition: 0.3s; }
        .footer-link:hover { color: white; }

        .text-gradient { background: linear-gradient(135deg, #fff 0%, var(--primary) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        /* Efecto de escucha activa */
        .listening-ring {
            animation: pulse-ring 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.85); opacity: 0.5; }
            50% { opacity: 0.3; }
            100% { transform: scale(1.5); opacity: 0; }
        }
    </style>
</head>
<body>

    <div class="bg-mesh"></div>

    @php 
        $wa_clean = preg_replace('/[^0-9]/', '', config('platform.whatsapp')); 
        
        $plan_info = "";
        foreach($plans as $p) {
            $storage = ($p->max_storage_mb >= 1024) ? (($p->max_storage_mb/1024) . "GB") : ($p->max_storage_mb . "MB");
            $plan_info .= "Plan " . $p->name . " por $" . number_format($p->price, 0, ',', '.') . " con " . $p->max_users . " usuarios y " . $storage . " de espacio. ";
        }

        function formatMB($mb) {
            if ($mb >= 1024) {
                $gb = $mb / 1024;
                return (round($gb) == $gb ? number_format($gb, 0) : number_format($gb, 1)) . 'GB';
            }
            return number_format($mb, 0) . 'MB';
        }
    @endphp

    {{-- NAVBAR RESPONSIVE --}}
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container px-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <img src="{{ asset('images/logo_premium.png') }}" alt="MultiPOS" style="height: 40px">
                <span class="fw-bold fs-3 mt-1">MultiPOS</span>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-center gap-3 py-3 py-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#experiencia">Experiencia</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tecnologia">Tecnología</a></li>
                    <li class="nav-item"><a class="nav-link" href="#planes">Planes</a></li>
                    <li class="nav-item"><a class="nav-link text-white fw-bold" href="{{ route('demo.mode') }}"><i class="bi bi-stars text-primary"></i> Demo</a></li>
                    <li class="nav-item ms-lg-3">
                        <a href="{{ route('login') }}" class="btn-premium bg-white text-black py-2 px-4 shadow">Ingresar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <section class="hero">
        <div class="container px-4">
            <div class="row align-items-center">
                <div class="col-lg-7 text-center text-lg-start">
                    <span class="badge rounded-pill bg-primary px-3 py-2 mb-4 animate__animated animate__fadeInLeft">🚀 NUEVA VERSIÓN 4.0</span>
                    <h1 class="display-1 fw-bold mb-4">Controlá tu Negocio <span class="text-primary">como un Pro.</span></h1>
                    <p class="fs-4 mb-5" style="color: #e2e2e7;">La plataforma SaaS definitiva que unifica Ventas, Stock por Escáner y Finanzas con una estética de alto nivel y tecnología de punta.</p>
                    <div class="d-flex flex-column flex-md-row gap-3 justify-content-center justify-content-lg-start">
                        <a href="{{ route('register') }}" class="btn-premium bg-primary text-white fs-5">Empezar Ahora</a>
                        <a href="{{ route('demo.mode') }}" class="btn-premium border border-primary text-white fs-5">✨ PROBAR DEMO</a>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <img src="{{ asset('images/hero_scanner_mobile.png') }}" class="img-fluid rounded-5 shadow-2xl animate__animated animate__fadeInRight" style="transform: perspective(1000px) rotateY(-15deg) rotateX(5deg); border: 1px solid var(--glass-border);" alt="MultiPOS Mobile">
                </div>
            </div>
        </div>
    </section>

    {{-- TECNOLOGIA - MAGIC SCAN --}}
    <section id="tecnologia" class="py-5">
        <div class="container px-4 py-5 bg-black border border-secondary rounded-5 shadow-lg">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <img src="{{ asset('images/multipos_mobile_pos_premium_1775021097894.png') }}" class="img-fluid rounded-4 shadow-lg border border-primary" alt="Escaneo Móvil">
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <span class="badge bg-primary mb-3">EXCLUSIVO 4.0</span>
                    <h2 class="display-4 fw-bold mb-4 text-white">Vendé Desde Donde Estés: <span class="text-primary text-gradient">Tu teléfono es tu POS.</span></h2>
                    <p class="fs-5 mb-4" style="color: #d1d1d6;">Olvidá las cajas registradoras pesadas. Con MultiPOS Mobile, transformás cualquier smartphone en un punto de venta profesional con escaneo ultra-rápido de productos.</p>
                    <div class="d-flex gap-4">
                        <div class="text-center p-3 bg-dark rounded-4 border border-secondary flex-grow-1">
                            <i class="bi bi-phone text-primary fs-1"></i>
                            <h5 class="mt-2 text-white">POS Portátil</h5>
                        </div>
                        <div class="text-center p-3 bg-dark rounded-4 border border-secondary flex-grow-1">
                            <i class="bi bi-qr-code-scan text-primary fs-1"></i>
                            <h5 class="mt-2 text-white">Magic Scan</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- NUEVO: CONTROL DE CAMPO & QR --}}
    <section class="py-5 mt-5">
        <div class="container px-4">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 order-2 order-lg-1">
                    <span class="badge bg-success mb-3">CONTROL TOTAL</span>
                    <h2 class="display-4 fw-bold mb-4">Eliminá el Faltante de Caja <br><span class="text-success text-gradient">en Tiempo Real.</span></h2>
                    <p class="fs-5 mb-5 text-muted">Nuestro sistema de **Asistencia por QR** y **Registro de Gasto Rápido** permite que tu personal de campo rinda cuentas al instante. Sacá una foto a la factura y restalo de la caja en segundos.</p>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success"><i class="bi bi-camera fs-3"></i></div>
                                <div><h6 class="fw-bold text-white">Fotos de Facturas</h6><p class="small text-muted">Prueba visual inmediata de cada gasto realizado.</p></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success"><i class="bi bi-geo-alt fs-3"></i></div>
                                <div><h6 class="fw-bold text-white">Control de Ubicación</h6><p class="small text-muted">Sabés dónde y cuándo se hizo cada arqueo.</p></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2">
                    <div class="position-relative">
                        <img src="{{ asset('images/multipos_qr_attendance_1775021114033.png') }}" class="img-fluid rounded-5 shadow-2xl skew-right" alt="Control QR">
                        <div class="position-absolute bottom-0 start-0 m-4 bg-dark bg-opacity-75 p-3 rounded-4 backdrop-blur border border-white border-opacity-10 d-none d-md-block animate__animated animate__fadeInLeft">
                            <div class="small fw-bold text-success animate__animated animate__flash animate__infinite">● MARCACIÓN CORRECTA</div>
                            <div class="small text-white-50">Juan Pérez · Entrada: 08:30hs</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECCIÓN OMNICANALIDAD --}}
    <section class="py-5" style="background: linear-gradient(180deg, #09090b 0%, #000 100%);">
        <div class="container px-4 text-center">
            <h2 class="display-3 fw-bold mb-5">Un Solo Motor, <span class="text-primary">Múltiples Canales.</span></h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="feature-card">
                        <i class="bi bi-shop"></i>
                        <h4 class="fw-bold text-white mb-3">Ventas Locales</h4>
                        <p>Gestión de mostrador ultra rápida con soporte para múltiples cajeros, turnos y arqueos automáticos.</p>
                        <div class="mt-4 pt-3 border-top border-secondary">
                            <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3">SINCRO TOTAL</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="feature-card">
                        <i class="bi bi-globe"></i>
                        <h4 class="fw-bold text-white mb-3">Ventas Online</h4>
                        <p>Tu propio catálogo autogestionado. Recibí pedidos por WhatsApp o Web y visualizalos en tiempo real.</p>
                        <div class="mt-4 pt-3 border-top border-secondary">
                            <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3">24/7 ACTIVO</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CARINA ASSISTANT (CON MÁS PODER) --}}
    <section id="asistente" class="py-5 mt-5">
        <div class="container px-4">
            <div class="row align-items-center bg-black border border-secondary p-4 p-lg-5 rounded-5 shadow-lg">
                <div class="col-lg-4 text-center mb-4 mb-lg-0">
                    <div class="position-relative d-inline-block">
                        <div id="voice-pulse" class="position-absolute top-50 start-50 translate-middle rounded-circle bg-primary opacity-25 d-none" style="width: 250px; height: 250px;"></div>
                        <img src="{{ asset('images/ai_avatar.png') }}" class="rounded-circle border border-primary p-2 animate__animated animate__pulse animate__infinite" style="width: 220px; height: 220px; object-fit: cover; position: relative; z-index: 2;" alt="Carina AI">
                        <span class="position-absolute bottom-0 start-50 translate-middle-x badge rounded-pill bg-success border border-black px-4 py-2 shadow" style="margin-bottom: -15px; z-index: 3;">ESTADO: ONLINE</span>
                    </div>
                </div>
                <div class="col-lg-8 ps-lg-5 text-center text-lg-start">
                    <div class="badge bg-primary px-3 py-1 mb-3">ASISTENTE CON IA GEN</div>
                    <h2 class="display-4 fw-bold mb-3">Hola, soy <span class="text-primary text-gradient">Carina AI</span>.</h2>
                    <p class="fs-4 mb-4 italic" style="color: #f1f1f1; opacity: 0.95;">"Mi voz es amable, dulce y estoy aquí para asesorarte en vivo. ¡Hablame!"</p>
                    <p class="fs-5 mb-5" style="color: #d1d1d6;">Preguntame lo que quieras: Precios, cómo funciona el stock colaborativo, qué es Bunny.net o incluso el clima. ¡Yo sé todo sobre tu éxito!</p>
                    <div class="d-flex flex-column flex-md-row gap-3">
                        <a href="https://wa.me/{{ $wa_clean }}?text=Hola%20Carina,%20quiero%20conocer%20mas" class="btn btn-lg rounded-pill px-5 fw-bold shadow text-white d-flex align-items-center justify-content-center" style="background: var(--whatsapp)">
                            <i class="bi bi-whatsapp me-2 fs-4"></i> Hablar con Carina
                        </a>
                        <button id="activate-voice-btn" class="btn btn-outline-light btn-lg rounded-pill px-5 fw-bold d-flex align-items-center justify-content-center">
                            <i class="bi bi-volume-up-fill me-2 fs-4"></i> <span id="voice-status">Escuchar Voz</span>
                        </button>
                        <button id="listen-btn" class="btn btn-outline-primary btn-lg rounded-pill px-4 fw-bold d-flex align-items-center justify-content-center">
                            <i class="bi bi-mic-fill me-2 fs-4"></i> Hablarle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PLANES --}}
    <section id="planes" class="py-5 mt-5">
        <div class="container px-4">
            <div class="text-center mb-5">
                <h2 class="display-3 fw-bold">Planes Sin Sorpresas.</h2>
                <p class="fs-4" style="color: #d1d1d6;">Elegí la escala de tu negocio hoy.</p>
            </div>
            <div class="row g-4 justify-content-center">
                @foreach ($plans as $plan)
                <div class="col-md-6 col-lg-3">
                    <div class="price-card {{ $plan->name == 'PROFESSIONAL' ? 'featured' : '' }}">
                        <h4 class="text-uppercase" style="color: #fff; letter-spacing: 1px">{{ $plan->name }}</h4>
                        <div class="amount text-white">${{ number_format($plan->price, 0, ',', '.') }}<span class="fs-6 opacity-50">/mes</span></div>
                        
                        @if($plan->description)
                            <p class="small mt-2 mb-3" style="color: #ccc; font-style: italic;">{{ $plan->description }}</p>
                        @endif

                        <ul class="list-unstyled text-start my-4 py-3 border-top border-bottom border-secondary small" style="color: #f1f1f1;">
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> {{ $plan->max_users }} Usuarios</li>
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> {{ $plan->max_products }} Productos</li>
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> {{ formatMB($plan->max_storage_mb) }} Almacén</li>
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> Escaneo Móvil</li>
                        </ul>
                        <a href="{{ route('register', ['plan' => $plan->id]) }}" class="btn {{ $plan->name == 'PROFESSIONAL' ? 'btn-primary' : 'btn-outline-light' }} w-100 rounded-pill py-3 fw-bold">Elegir Este Plan</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="py-5 mt-5 bg-black border-top border-secondary">
        <div class="container text-center">
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <i class="bi bi-telephone text-primary fs-1 mb-3 d-block"></i>
                    <h5 class="text-white">Llamanos</h5>
                    <p style="color: #f1f1f1;">{{ config('platform.phone') }}</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-envelope text-primary fs-1 mb-3 d-block"></i>
                    <h5 class="text-white">Email</h5>
                    <p style="color: #f1f1f1;">{{ config('platform.email') }}</p>
                </div>
                <div class="col-md-4">
                    <a href="https://wa.me/{{ $wa_clean }}" class="text-decoration-none">
                        <i class="bi bi-whatsapp fs-1 mb-3 d-block" style="color: var(--whatsapp)"></i>
                        <h5 class="text-white">WhatsApp</h5>
                        <p style="color: var(--whatsapp);">Chat en Vivo 24/7</p>
                    </a>
                </div>
            </div>
            <p style="color: #ccc;">&copy; 2026 MultiPOS Cloud Management. <br> <span class="small opacity-50">El estándar de oro en gestión comercial.</span></p>
        </div>
    </footer>

    {{-- CHAT SYSTEM --}}
    <div id="carina-chat-system" class="position-fixed bottom-0 end-0 p-4 d-flex flex-column align-items-end" style="z-index: 10000;">
        <div id="chat-window" class="card shadow-2xl animate__animated animate__fadeInUp" style="background: #09090b; border: 1px solid var(--glass-border); border-radius: 25px; margin-bottom: 20px; display: none; flex-direction: column; overflow: hidden;">
            <div class="card-header bg-black border-bottom border-secondary p-4 d-flex align-items-center gap-3">
                <img src="{{ asset('images/ai_avatar.png') }}" class="rounded-circle border border-primary p-1" style="width: 50px; height: 50px; object-fit: cover;">
                <div class="flex-grow-1">
                    <h5 class="mb-0 fw-bold text-white">Carina AI</h5>
                    <small class="text-success fw-bold">● ONLINE / ESCUCHANDO</small>
                </div>
                <button id="close-chat" class="btn btn-sm text-muted fs-3">×</button>
            </div>
            <div id="chat-body" class="card-body p-4 overflow-auto scroll-custom" style="flex: 1; background: #000;">
                <div class="mb-4">
                    <div class="bg-dark p-3 rounded-4 d-inline-block border border-secondary text-light" style="max-width: 90%; line-height: 1.4;">
                        ¡Hola! Soy <strong>Carina</strong> ✨ <br> ¿Querés saber sobre precios, cómo usar tu celular como escáner o qué tan rápido es nuestro sistema con Bunny.net? <br><br> <strong>Hablame o escribime aquí abajo.</strong>
                    </div>
                </div>
                <div id="chat-interactions"></div>
                <div id="typing-indicator" class="d-none text-muted small italic mb-3 ps-2">Carina está analizando tu respuesta...</div>
            </div>
            <div class="card-footer bg-black border-top border-secondary p-4">
                <div class="input-group">
                    <button id="chat-mic-btn" class="btn btn-outline-secondary rounded-circle me-2" style="width: 50px; height: 50px;"><i class="bi bi-mic-fill"></i></button>
                    <input type="text" id="chat-input" class="form-control bg-dark border-secondary text-white rounded-pill px-4" placeholder="Escribir mensaje...">
                    <button id="send-btn" class="btn btn-primary rounded-circle ms-2" style="width: 50px; height: 50px;"><i class="bi bi-send-fill text-white"></i></button>
                </div>
            </div>
        </div>
        <div id="chat-bubble" class="rounded-circle shadow-lg d-flex align-items-center justify-content-center bg-black border-2 border-primary" style="width: 80px; height: 80px; cursor: pointer; border-style: solid; position: relative;">
            <img src="{{ asset('images/ai_avatar.png') }}" class="rounded-circle" style="width: 70px; height: 70px; object-fit: cover;">
            <span class="position-absolute bottom-0 start-50 translate-middle badge rounded-pill bg-success border border-black shadow" style="font-size: 0.7rem; padding: 0.4rem 0.8rem; z-index: 10;">CARINA AI</span>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const bubble = document.getElementById('chat-bubble');
        const windowChat = document.getElementById('chat-window');
        const closeChat = document.getElementById('close-chat');
        const sendBtn = document.getElementById('send-btn');
        const chatInput = document.getElementById('chat-input');
        const chatInteractions = document.getElementById('chat-interactions');
        const typingId = document.getElementById('typing-indicator');
        const voiceBtn = document.getElementById('activate-voice-btn');
        const listenBtn = document.getElementById('listen-btn');
        const chatMicBtn = document.getElementById('chat-mic-btn');
        const voicePulse = document.getElementById('voice-pulse');

        let voiceActive = false;

        // CEREBRO MAESTRO DE CARINA - AHORA DINÁMICO DESDE PHP
        const kb = {
            "precio": "Actualmente tenemos estos planes disponibles: {!! $plan_info !!} ¿Cuál se adapta mejor a tu negocio?",
            "plan": "Tenemos opciones desde la STARTER hasta la BUSINESS. {!! $plan_info !!}",
            "stock": "¡El stock es mi especialidad! Con Magic Scan Pro, usás la cámara de tu iPhone o Android como un escáner industrial. Es tan rápido que podés cargar 100 productos en minutos. Además, varios empleados pueden contar stock al mismo tiempo por QR.",
            "bunny": "Bunny.net es el secreto de nuestra velocidad. Guardamos tus videos y fotos pesadas allí para que tu sistema cargue en menos de 1 segundo, sin importar cuántos archivos tengas. ¡Es tecnología de nivel mundial!",
            "hola": "¡Hola! Soy Carina, tu asistente de éxito en MultiPOS. Estoy aquí para que tu negocio pase al siguiente nivel. ¿Qué duda tenés hoy? ✨",
            "carina": "Esa soy yo! Una inteligencia creada para servirte. Puedo explicarte desde cómo vender hasta cómo ver tus ganancias en tiempo real.",
            "franquicia": "Para franquicias, el plan BUSINESS es imbatible. Podés ver lo que pasa en cada sucursal desde tu casa, unificar el stock y ver reportes consolidados.",
            "demo": "¡La demo es genial! Podés probar el escáner ahora mismo haciendo clic en el botón DEMO de arriba. Te va a encantar.",
            "clima": "Si te dijera que hace calor en New York, te mentiría porque soy una IA, pero lo que sí sé es que en MultiPOS siempre hay un clima de éxito y crecimiento. 😊",
            "dueño": "El dueño se llama Mario y es un apasionado de que tu negocio funcione. Si querés hablar con él, hacé clic en el botón de WhatsApp y te atenderá personalmente."
        };

        // RECONOCIMIENTO DE VOZ (OÍDO)
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (SpeechRecognition) {
            const recognition = new SpeechRecognition();
            recognition.lang = 'es-ES';
            recognition.continuous = false;

            recognition.onstart = () => {
                voicePulse.classList.remove('d-none');
                chatMicBtn.classList.add('btn-primary', 'listening-ring');
                if(!windowChat.classList.contains('d-none') && windowChat.style.display !== 'none') {
                    chatInput.placeholder = "Escuchando...";
                }
            };

            recognition.onresult = (event) => {
                const transcript = event.results[0][0].transcript;
                chatInput.value = transcript;
                sendBtn.click();
            };

            recognition.onend = () => {
                voicePulse.classList.add('d-none');
                chatMicBtn.classList.remove('btn-primary', 'listening-ring');
                chatInput.placeholder = "Escribir mensaje...";
            };

            listenBtn.onclick = () => {
                if(windowChat.style.display === 'none' || windowChat.classList.contains('d-none')) {
                    bubble.click();
                }
                recognition.start();
            };
            chatMicBtn.onclick = () => recognition.start();
        } else {
            listenBtn.style.display = 'none';
            chatMicBtn.style.display = 'none';
        }

        bubble.onclick = () => { windowChat.style.display = windowChat.style.display === 'flex' ? 'none' : 'flex'; };
        closeChat.onclick = () => { windowChat.style.display = 'none'; };

        voiceBtn.onclick = () => {
            voiceActive = !voiceActive;
            voiceBtn.classList.toggle('btn-primary');
            document.getElementById('voice-status').innerText = voiceActive ? "Voz: ACTIVADA 🌸" : "Escuchar Voz";
            if(voiceActive) speak("¡Hola! Soy Carina. Ahora te responderé hablando con todo mi cariño. ¿En qué te ayudo hoy?");
        };

        function speak(text) {
            if(!voiceActive) return;
            window.speechSynthesis.cancel();
            const msg = new SpeechSynthesisUtterance(text);
            msg.lang = 'es-ES'; msg.rate = 1.0; msg.pitch = 1.1;
            window.speechSynthesis.speak(msg);
        }

        function addMessage(text, isUser = false) {
            const div = document.createElement('div');
            div.className = `mb-4 ${isUser ? 'text-end' : ''} animate__animated animate__fadeInUp`;
            div.innerHTML = `<div class="${isUser ? 'bg-primary text-white shadow-lg' : 'bg-dark text-light border border-secondary shadow'} p-3 rounded-4 d-inline-block" style="max-width: 85%; font-size: 1.05rem;">${text}</div>`;
            chatInteractions.appendChild(div);
            document.getElementById('chat-body').scrollTop = document.getElementById('chat-body').scrollHeight;
        }

        sendBtn.onclick = () => {
            const msg = chatInput.value.trim();
            if(!msg) return;
            addMessage(msg, true);
            chatInput.value = '';
            typingId.classList.remove('d-none');
            setTimeout(() => {
                typingId.classList.add('d-none');
                let response = "¡Qué buena pregunta! Me encantaría explicarte eso en detalle por WhatsApp para darte una atención VIP. 🌸 Pero te adelanto que MultiPOS es la mejor opción para vos.";
                const lower = msg.toLowerCase();
                Object.keys(kb).forEach(key => { if(lower.includes(key)) response = kb[key]; });
                addMessage(response);
                speak(response);
                
                // Si la pregunta es técnica, invitar a WhatsApp después
                if(!lower.includes('hola') && !lower.includes('carina')) {
                    setTimeout(() => {
                        addMessage(`<a href="https://wa.me/{{ $wa_clean }}?text=Me%20intereso%20lo%20que%20dijo%20Carina" target="_blank" class="btn btn-sm btn-outline-success rounded-pill mt-2 fw-bold px-3 py-2"><i class="bi bi-whatsapp me-2"></i> Continuar por WhatsApp 📱</a>`);
                    }, 1500);
                }
            }, 1200);
        };
        chatInput.onkeypress = (e) => { if(e.key === 'Enter') sendBtn.click(); };
    </script>
</body>
</html>
