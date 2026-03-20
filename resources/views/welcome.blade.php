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
            --text-dim: #a1a1aa;
        }

        body { background-color: var(--bg-dark); color: var(--text-main); font-family: 'Outfit', sans-serif; overflow-x: hidden; }

        .bg-mesh {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
            background: radial-gradient(circle at 0% 0%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 100% 100%, rgba(16, 185, 129, 0.05) 0%, transparent 55%);
        }

        .navbar { background: rgba(0,0,0,0.85) !important; backdrop-filter: blur(15px); border-bottom: 1px solid var(--glass-border); padding: 1rem 0; }
        .nav-link { color: var(--text-dim) !important; font-weight: 500; transition: 0.3s; }
        .nav-link:hover { color: white !important; }
        
        .hero { padding: 10rem 0 5rem; min-height: 90vh; display: flex; align-items: center; }
        .btn-premium { padding: 1rem 2.5rem; border-radius: 50px; font-weight: 700; transition: 0.3s; text-decoration: none; }
        .btn-premium:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(139, 92, 246, 0.3); }

        .feature-card { background: var(--card-bg); border: 1px solid var(--glass-border); border-radius: 30px; padding: 3rem; transition: 0.4s; height: 100%; }
        .feature-card:hover { border-color: var(--primary); transform: translateY(-10px); }
        .feature-card i { font-size: 3rem; color: var(--primary); margin-bottom: 1.5rem; display: block; }

        .price-card { background: var(--card-bg); border: 1px solid var(--glass-border); padding: 3rem 2rem; border-radius: 30px; text-align: center; }
        .price-card.featured { border: 2px solid var(--primary); position: relative; }
        .price-card.featured::after { content: 'MÁS POPULAR'; position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: var(--primary); color: white; padding: 2px 15px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; }

        #chat-window { width: 400px; height: 600px; max-height: 85vh; transform-origin: bottom right; }
        @media (max-width: 576px) { #chat-window { width: 90vw !important; height: 70vh !important; } }

        .scroll-custom::-webkit-scrollbar { width: 4px; }
        .scroll-custom::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        
        .footer-link { color: var(--text-dim); text-decoration: none; transition: 0.3s; }
        .footer-link:hover { color: white; }
    </style>
</head>
<body>

    <div class="bg-mesh"></div>

    @php $wa_clean = preg_replace('/[^0-9]/', '', config('platform.whatsapp')); @endphp

    {{-- NAVBAR RESPONSIVE --}}
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container px-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <img src="{{ asset('images/logo_premium.png') }}" alt="MultiPOS" style="height: 40px">
                <span class="fw-bold fs-3">MultiPOS</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-center gap-3 py-3 py-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#experiencia">Experiencia</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tecnologia">Tecnología</a></li>
                    <li class="nav-item"><a class="nav-link" href="#planes">Planes</a></li>
                    <li class="nav-item"><a class="nav-link text-white fw-bold" href="{{ route('demo.mode') }}"><i class="bi bi-stars text-primary"></i> Demo</a></li>
                    <li class="nav-item ms-lg-3">
                        <a href="{{ route('login') }}" class="btn-premium bg-white text-black py-2">Ingresar</a>
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
                    <p class="fs-4 text-muted mb-5">La plataforma SaaS definitiva que unifica Ventas, Stock por Escáner y Finanzas con una estética de alto nivel.</p>
                    <div class="d-flex flex-column flex-md-row gap-3 justify-content-center justify-content-lg-start">
                        <a href="{{ route('register') }}" class="btn-premium bg-primary text-white fs-5">Empezar Ahora</a>
                        <a href="{{ route('demo.mode') }}" class="btn-premium border border-primary text-white fs-5">✨ PROBAR DEMO</a>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <img src="{{ asset('images/hero_scanner_mobile.png') }}" class="img-fluid rounded-5 shadow-2xl animate__animated animate__fadeInRight" style="transform: perspective(1000px) rotateY(-15deg) rotateX(5deg);" alt="MultiPOS Mobile">
                </div>
            </div>
        </div>
    </section>

    {{-- TECNOLOGIA - MAGIC SCAN --}}
    <section id="tecnologia" class="py-5">
        <div class="container px-4 py-5 bg-black border border-secondary rounded-5 shadow-lg">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <img src="{{ asset('images/hero_scanner_mobile.png') }}" class="img-fluid rounded-4 shadow-lg border border-success" alt="Escaneo Móvil">
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <span class="badge bg-success mb-3">EXCLUSIVO 4.0</span>
                    <h2 class="display-4 fw-bold mb-4 text-white">Magic Scan Pro: <span class="text-success">Tu teléfono es un escáner.</span></h2>
                    <p class="fs-5 text-muted mb-4">Aprovechá la cámara de cualquier celular para vender y cargar stock. Nuestra tecnología procesa códigos de barras en milisegundos.</p>
                    <div class="d-flex gap-4">
                        <div class="text-center p-3 bg-dark rounded-4 border border-secondary flex-grow-1">
                            <i class="bi bi-lightning-fill text-success fs-1"></i>
                            <h5 class="mt-2 small mb-0">Ultrarrápido</h5>
                        </div>
                        <div class="text-center p-3 bg-dark rounded-4 border border-secondary flex-grow-1">
                            <i class="bi bi-shield-check text-success fs-1"></i>
                            <h5 class="mt-2 small mb-0">Sin Errores</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CARINA ASSISTANT --}}
    <section id="asistente" class="py-5 mt-5">
        <div class="container px-4">
            <div class="row align-items-center bg-black border border-secondary p-4 p-lg-5 rounded-5 shadow-lg">
                <div class="col-lg-4 text-center mb-4 mb-lg-0">
                    <div class="position-relative d-inline-block">
                        <img src="{{ asset('images/ai_avatar.png') }}" class="rounded-circle border border-primary p-2 animate__animated animate__pulse animate__infinite" style="width: 220px; height: 220px; object-fit: cover;" alt="Carina AI">
                        <span class="position-absolute bottom-0 start-50 translate-middle-x badge rounded-pill bg-success border border-black px-4 py-2" style="margin-bottom: -15px">ONLINE</span>
                    </div>
                </div>
                <div class="col-lg-8 ps-lg-5 text-center text-lg-start">
                    <h2 class="display-4 fw-bold mb-3">Hola, soy <span class="text-primary">Carina AI</span>.</h2>
                    <p class="fs-4 text-muted mb-4 italic">"Mi voz es amable, dulce y estoy aquí para asesorarte en vivo."</p>
                    <p class="fs-5 text-dim mb-5">Hablame sobre precios, stock o cómo usar Bunny.net. Estoy lista para responderte ahora mismo.</p>
                    <div class="d-flex flex-column flex-md-row gap-3">
                        <a href="https://wa.me/{{ $wa_clean }}?text=Hola%20Carina,%20quiero%20conocer%20mas" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold"><i class="bi bi-whatsapp me-2"></i> Hablar con Carina</a>
                        <button id="activate-voice-btn" class="btn btn-outline-light btn-lg rounded-pill px-5 fw-bold"><i class="bi bi-volume-up-fill me-2"></i> <span id="voice-status">Escuchar Voz</span></button>
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
                <p class="fs-4 text-muted">Elegí la escala de tu negocio.</p>
            </div>
            <div class="row g-4 justify-content-center">
                @foreach ($plans as $plan)
                <div class="col-md-6 col-lg-3">
                    <div class="price-card {{ $plan->name == 'PROFESSIONAL' ? 'featured' : '' }}">
                        <h4 class="text-uppercase text-muted">{{ $plan->name }}</h4>
                        <div class="amount text-white">${{ number_format($plan->price, 0, ',', '.') }}<span class="fs-6 opacity-50">/mes</span></div>
                        <ul class="list-unstyled text-start my-4 py-3 border-top border-bottom border-secondary small">
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> {{ $plan->max_users }} Usuarios</li>
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> {{ $plan->max_products }} Productos</li>
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> {{ $plan->max_storage_mb }}MB Almacén</li>
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> Soporte Prioritario</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn {{ $plan->name == 'PROFESSIONAL' ? 'btn-primary' : 'btn-outline-light' }} w-100 rounded-pill">Elegir</a>
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
                    <p class="text-muted">{{ config('platform.phone') }}</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-envelope text-primary fs-1 mb-3 d-block"></i>
                    <h5 class="text-white">Email</h5>
                    <p class="text-muted">{{ config('platform.email') }}</p>
                </div>
                <div class="col-md-4">
                    <a href="https://wa.me/{{ $wa_clean }}" class="text-decoration-none text-white">
                        <i class="bi bi-whatsapp text-success fs-1 mb-3 d-block"></i>
                        <h5 class="text-white">WhatsApp</h5>
                        <p class="text-muted">Chat en Vivo 24/7</p>
                    </a>
                </div>
            </div>
            <p class="text-muted">&copy; 2026 MultiPOS Cloud Management. <br> <span class="small opacity-50">El estándar de oro en gestión comercial.</span></p>
        </div>
    </footer>

    {{-- CHAT SYSTEM --}}
    <div id="carina-chat-system" class="position-fixed bottom-0 end-0 p-4 d-flex flex-column align-items-end" style="z-index: 10000;">
        <div id="chat-window" class="card shadow-2xl animate__animated animate__fadeInUp" style="background: #09090b; border: 1px solid var(--glass-border); border-radius: 25px; margin-bottom: 20px; display: none; flex-direction: column; overflow: hidden;">
            <div class="card-header bg-black border-bottom border-secondary p-4 d-flex align-items-center gap-3">
                <img src="{{ asset('images/ai_avatar.png') }}" class="rounded-circle border border-primary p-1" style="width: 50px; height: 50px; object-fit: cover;">
                <div class="flex-grow-1">
                    <h5 class="mb-0 fw-bold">Carina AI</h5>
                    <small class="text-success">● Conectada ahora</small>
                </div>
                <button id="close-chat" class="btn btn-sm text-muted fs-3">×</button>
            </div>
            <div id="chat-body" class="card-body p-4 overflow-auto scroll-custom" style="flex: 1; background: #000;">
                <div class="mb-4">
                    <div class="bg-dark p-3 rounded-4 d-inline-block border border-secondary text-light" style="max-width: 90%;">
                        Hola! Soy Carina ✨ ¿Consultas sobre nuestros planes o tecnología? Escribí lo que necesités.
                    </div>
                </div>
                <div id="chat-interactions"></div>
                <div id="typing-indicator" class="d-none text-muted small italic">Carina está pensando...</div>
            </div>
            <div class="card-footer bg-black border-top border-secondary p-4">
                <div class="input-group">
                    <input type="text" id="chat-input" class="form-control bg-dark border-secondary text-white rounded-pill px-4" placeholder="Escribir mensaje...">
                    <button id="send-btn" class="btn btn-primary rounded-circle ms-2" style="width: 50px; height: 50px;"><i class="bi bi-send-fill text-white"></i></button>
                </div>
            </div>
        </div>
        <div id="chat-bubble" class="rounded-circle shadow-lg d-flex align-items-center justify-content-center bg-black border-2 border-primary" style="width: 80px; height: 80px; cursor: pointer; border-style: solid;">
            <img src="{{ asset('images/ai_avatar.png') }}" class="rounded-circle" style="width: 70px; height: 70px; object-fit: cover;">
            <span class="position-absolute bottom-0 start-50 translate-middle badge rounded-pill bg-success border border-black shadow" style="font-size: 0.7rem; padding: 0.4rem 0.8rem">CARINA AI</span>
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
        let voiceActive = false;

        const kb = {
            "precio": "Nuestros planes arrancan en $25.000 ARS por mes. El plan PRO para varios locales vale $45.000.",
            "stock": "Usamos Magic Scan Pro. Cualquier móvil sirve como escáner industrial. También hacemos inventario QR.",
            "bunny": "Tus archivos pesados viven en Bunny.net. Esto hace que tu sistema vuele, sin importar cuántos videos subas.",
            "hola": "¡Hola! Soy Carina. ¿En qué puedo asesorar a tu empresa hoy? ✨",
            "carina": "Esa soy yo! Estoy aquí 24/7 para ayudarte.",
            "franquicia": "El plan Business es el ideal para franquicias, permitiendo gestionar locales ilimitados."
        };

        bubble.onclick = () => { windowChat.style.display = windowChat.style.display === 'flex' ? 'none' : 'flex'; };
        closeChat.onclick = () => { windowChat.style.display = 'none'; };

        voiceBtn.onclick = () => {
            voiceActive = !voiceActive;
            voiceBtn.classList.toggle('btn-primary');
            document.getElementById('voice-status').innerText = voiceActive ? "Voz: ACTIVADA 🌸" : "Escuchar Voz";
            if(voiceActive) speak("Hola! Soy Carina. Ahora te responderé hablando con todo mi cariño.");
        };

        function speak(text) {
            if(!voiceActive) return;
            window.speechSynthesis.cancel();
            const msg = new SpeechSynthesisUtterance(text);
            msg.lang = 'es-ES'; msg.rate = 0.95; msg.pitch = 1.1;
            window.speechSynthesis.speak(msg);
        }

        function addMessage(text, isUser = false) {
            const div = document.createElement('div');
            div.className = `mb-4 ${isUser ? 'text-end' : ''}`;
            div.innerHTML = `<div class="${isUser ? 'bg-primary text-white' : 'bg-dark text-light border border-secondary'} p-3 rounded-4 d-inline-block shadow" style="max-width: 85%;">${text}</div>`;
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
                let response = "Interesante pregunta! Para asesorarte mejor con tu caso particular, hacé click en el botón de WhatsApp abajo en el chat y te atenderemos personalmente. 🌸";
                Object.keys(kb).forEach(key => { if(msg.toLowerCase().includes(key)) response = kb[key]; });
                addMessage(response);
                speak(response);
            }, 1000);
        };
        chatInput.onkeypress = (e) => { if(e.key === 'Enter') sendBtn.click(); };
    </script>
</body>
</html>
