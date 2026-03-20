<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MultiPOS - El Futuro de tu Negocio Hoy</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    
    <style>
        :root {
            --primary: #8b5cf6;
            --primary-dark: #6366f1;
            --accent: #10b981;
            --bg-dark: #09090b;
            --card-bg: rgba(24, 24, 27, 0.8);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #fafafa;
            --text-dim: #a1a1aa;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; scroll-behavior: smooth; }

        body { background-color: var(--bg-dark); color: var(--text-main); overflow-x: hidden; line-height: 1.6; }

        /* --- GRADIENT BG --- */
        .bg-mesh {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
            background: 
                radial-gradient(circle at 0% 0%, rgba(139, 92, 246, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(16, 185, 129, 0.1) 0%, transparent 55%);
        }

        /* --- NAVIGATION --- */
        nav {
            padding: 1.2rem 8%; display: flex; justify-content: space-between; align-items: center;
            position: fixed; width: 100%; z-index: 1000;
            background: rgba(9, 9, 11, 0.8); backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
        }

        .logo { font-size: 1.6rem; font-weight: 800; text-decoration: none; color: white; display: flex; align-items: center; gap: 10px; }
        .nav-links { display: flex; gap: 2rem; align-items: center; }
        .nav-links a { color: var(--text-dim); text-decoration: none; font-size: 0.95rem; font-weight: 500; transition: 0.3s; }
        .nav-links a:hover { color: white; }

        .btn-trial { background: white; color: black; padding: 0.7rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 700; transition: 0.3s; font-size: 0.9rem; }
        .btn-trial:hover { transform: scale(1.05); box-shadow: 0 0 20px rgba(255,255,255,0.2); }

        /* --- SECTIONS --- */
        section { padding: 8rem 8%; }

        .hero { display: flex; align-items: center; gap: 4rem; min-height: 95vh; }
        .hero-content { flex: 1.2; }
        .hero h1 { font-size: 4.5rem; line-height: 1.1; margin-bottom: 1.5rem; font-weight: 900; letter-spacing: -2px; }
        .hero h1 span { color: var(--primary); }
        .hero p { font-size: 1.3rem; color: var(--text-dim); margin-bottom: 3rem; max-width: 90%; }

        .hero-img { flex: 1; position: relative; }
        .hero-img img { width: 100%; border-radius: 30px; box-shadow: 0 50px 100px -20px rgba(0,0,0,0.5); border: 1px solid var(--glass-border); transform: rotate3d(1, -1, 0, 15deg); }

        /* --- FEATURES GRID --- */
        .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; }
        .feature-card { 
            background: var(--card-bg); border: 1px solid var(--glass-border); 
            padding: 3.5rem 2.5rem; border-radius: 35px; transition: 0.4s; position: relative; overflow: hidden;
        }
        .feature-card:hover { transform: translateY(-10px); border-color: var(--primary); }
        .feature-card i { font-size: 3rem; color: var(--primary); margin-bottom: 1.5rem; display: block; }
        .feature-card h3 { font-size: 1.6rem; margin-bottom: 1rem; }
        .feature-card p { color: var(--text-dim); }

        /* --- MAGIC CARDS (Nuevas Funciones) --- */
        .magic-section { background: rgba(139, 92, 246, 0.05); border-radius: 60px; padding: 5rem; margin-top: 5rem; }
        .magic-row { display: flex; align-items: center; gap: 5rem; margin-bottom: 8rem; }
        .magic-row:last-child { margin-bottom: 0; }
        .magic-img { flex: 1; border-radius: 25px; overflow: hidden; box-shadow: 0 30px 60px rgba(0,0,0,0.5); }
        .magic-img img { width: 100%; transition: 0.5s; }
        .magic-img:hover img { transform: scale(1.1); }
        .magic-text { flex: 1; }
        .magic-text h2 { font-size: 3rem; margin-bottom: 1.5rem; font-weight: 800; }
        .magic-badge { background: var(--primary); color: white; padding: 0.4rem 1rem; border-radius: 50px; font-size: 0.8rem; font-weight: 800; margin-bottom: 1rem; display: inline-block; }

        /* --- PRICING --- */
        .pricing-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-top: 4rem; }
        .price-card { 
            background: var(--card-bg); border: 1px solid var(--glass-border); 
            padding: 3rem 2rem; border-radius: 30px; text-align: center; transition: 0.4s;
        }
        .price-card.featured { border: 2px solid var(--primary); transform: scale(1.05); background: linear-gradient(to bottom, rgba(139, 92, 246, 0.1), var(--card-bg)); }
        .price-card h4 { font-size: 1.2rem; margin-bottom: 1rem; opacity: 0.7; }
        .price-card .amount { font-size: 3rem; font-weight: 800; margin-bottom: 0.5rem; }
        .price-card .amount span { font-size: 1rem; opacity: 0.5; }
        
        /* --- AI PLAN MATCHER --- */
        .ai-matcher { 
            background: linear-gradient(135deg, #1e1b4b, #09090b); border-radius: 30px; 
            padding: 4rem; margin-top: 8rem; border: 1px solid var(--primary); text-align: center;
        }
        .matcher-steps { margin-top: 2rem; display: flex; justify-content: center; gap: 10px; }
        .step-btn { background: var(--glass); border: 1px solid var(--glass-border); color: white; padding: 1rem 2rem; border-radius: 15px; cursor: pointer; transition: 0.3s; }
        .step-btn.active { background: var(--primary); border-color: var(--primary); }

        /* FOOTER */
        footer { padding: 6rem 8%; border-top: 1px solid var(--glass-border); text-align: center; }

        @media (max-width: 992px) {
            .hero, .magic-row { flex-direction: column; text-align: center; }
            .hero h1 { font-size: 3rem; }
            .features-grid, .pricing-grid { grid-template-columns: 1fr; }
            .img-mockup { transform: none !important; }
        }
    </style>
</head>
<body>

    <div class="bg-mesh"></div>

    {{-- CARINA FLOATING CHAT WIDGET - REDISEÑO PRO CON VOZ E IA --}}
    @php
        // Blindar WhatsApp: Limpiar cualquier espacio, guión o símbolo del .env para el enlace
        $wa_clean = preg_replace('/[^0-9]/', '', config('platform.whatsapp'));
    @endphp

    <div id="carina-chat-system" class="position-fixed bottom-0 end-0 p-4 d-flex flex-column align-items-end" style="z-index: 9999;">
        
        <!-- Ventana de Chat Pro -->
        <div id="chat-window" class="card shadow-2xl animate__animated animate__fadeInUp" 
             style="width: 400px; height: 600px; max-height: 85vh; border-radius: 30px; border: 1px solid var(--glass-border); background: #09090b; margin-bottom: 20px; display: none; flex-direction: column;">
            
            <!-- Header Pro -->
            <div class="card-header border-0 p-4 rounded-top-5 d-flex align-items-center gap-3" style="background: linear-gradient(135deg, #09090b 0%, #1e1b4b 100%); border-bottom: 1px solid rgba(255,255,255,0.05) !important;">
                <img src="{{ asset('images/ai_avatar.png') }}" class="rounded-circle border border-primary p-1" style="width: 55px; height: 55px; object-fit: cover;" alt="Carina">
                <div class="flex-grow-1">
                    <h5 class="mb-0 fw-bold text-white fs-4">Carina <span class="text-primary">AI</span></h5>
                    <span class="small text-success fw-bold d-flex align-items-center"><i class="bi bi-circle-fill fs-6 me-2" style="font-size: 0.5rem"></i> ASISTENTE ACTIVA</span>
                </div>
                <button id="close-chat" class="btn btn-sm text-muted fs-3 p-0" style="margin-top: -15px">×</button>
            </div>
            
            <!-- Cuerpo del Chat -->
            <div id="chat-body" class="card-body p-4 overflow-auto scroll-custom" style="flex: 1; font-size: 1rem; scroll-behavior: smooth;">
                <div class="mb-4">
                    <div class="bg-dark p-3 rounded-4 d-inline-block shadow-sm border border-secondary" style="max-width: 90%; color: #d1d1d6;">
                        ¡Hola! Soy <strong class="text-primary">Carina</strong>. ✨ Estoy aquí para ayudarte a que tu negocio sea el mejor de tu ciudad. <br><br>
                        ¿En qué puedo asesorarte hoy? 😊
                    </div>
                </div>
                <div id="chat-interactions"></div>
                <!-- Indicador de escritura -->
                <div id="typing-indicator" class="d-none mb-3">
                    <span class="badge bg-dark text-muted rounded-pill px-3 py-2 italic border border-secondary animate__animated animate__flash animate__infinite">Carina está escribiendo...</span>
                </div>
            </div>

            <!-- Footer / Input Chat -->
            <div class="card-footer border-0 p-4 bg-dark rounded-bottom-5" style="border-top: 1px solid rgba(255,255,255,0.05) !important;">
                <div class="input-group">
                    <input type="text" id="chat-input" class="form-control bg-black border-secondary text-white rounded-start-pill py-3 px-4" placeholder="Hacer una pregunta...">
                    <button id="send-btn" class="btn btn-primary rounded-end-pill px-4">
                        <i class="bi bi-send-fill fs-4"></i>
                    </button>
                </div>
                <div class="text-center mt-3">
                    <a href="https://wa.me/{{ $wa_clean }}" target="_blank" class="text-success text-decoration-none small fw-bold">
                        <i class="bi bi-whatsapp me-1 text-success"></i> <span class="text-white">Ir a WhatsApp Directo</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bubble pulsante -->
        <div id="chat-bubble" class="rounded-circle shadow-lg d-flex align-items-center justify-content-center animate__animated animate__zoomIn" 
             style="width: 85px; height: 85px; background: #000; cursor: pointer; border: 2px solid var(--primary); transition: 0.3s transform ease;">
            <img src="{{ asset('images/ai_avatar.png') }}" class="rounded-circle" style="width: 75px; height: 75px; object-fit: cover;" alt="Carina AI Chat">
            <!-- Pulso verde -->
            <div class="position-absolute translate-middle-x" style="bottom: 0px; left: 50%;">
                 <span class="badge rounded-pill bg-success border border-white shadow-sm" style="font-size: 0.65rem; padding: 0.3rem 0.6rem">CARINA ✨</span>
            </div>
        </div>
    </div>

    <!-- Scroll Custom CSS -->
    <style>
        .scroll-custom::-webkit-scrollbar { width: 5px; }
        .scroll-custom::-webkit-scrollbar-track { background: transparent; }
        .scroll-custom::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        
        @media (max-width: 576px) {
            #chat-window { width: 92vw !important; right: 0 !important; margin-bottom: 20px !important; height: 75vh !important; }
            #chat-bubble { width: 70px !important; height: 70px !important; }
        }

        #chat-bubble:hover { transform: scale(1.1) rotate(5deg); box-shadow: 0 0 30px var(--primary); }
    </style>

    <nav>
        <a href="#" class="logo">
            <img src="{{ asset('images/logo_premium.png') }}" alt="MultiPOS" style="height: 45px"> 
            MultiPOS
        </a>
        <div class="nav-links">
            <a href="#experiencia">Experiencia</a>
            <a href="#tecnologia">Tecnología</a>
            <a href="#planes">Planes</a>
            <a href="{{ route('demo.mode') }}" class="nav-link text-white fw-bold me-2"><i class="bi bi-stars"></i> Demo</a>
            <a href="{{ route('login') }}" class="btn-trial">Ingresar</a>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="hero shadow-lg">
        <div class="hero-content mt-3">
            <div class="magic-badge animate__animated animate__fadeInLeft">🚀 NUEVA VERSIÓN 4.0</div>
            <h1>Controlá tu Negocio <span>como un Pro.</span></h1>
            <p>La plataforma SaaS definitiva que unifica Ventas, Stock por Escáner y Finanzas con una estética de alto nivel. Diseñado para quienes no se conforman con lo básico.</p>
            <div class="d-flex" style="gap: 15px">
                <a href="{{ route('register') }}" class="btn-trial" style="background: var(--primary); color: white; padding: 1.2rem 3rem; font-size: 1.1rem">Empezar Ahora</a>
                <a href="{{ route('demo.mode') }}" class="btn-trial" style="background: rgba(139, 92, 246, 0.1); border: 2px solid var(--primary); color: white; padding: 1.2rem 3rem; font-size: 1.1rem">✨ PROBAR GRATIS / DEMO</a>
            </div>
        </div>
        <div class="hero-img">
            <img src="{{ asset('images/hero_scanner_mobile.png') }}" class="img-mockup" alt="Scanner Mobile MultiPOS">
        </div>
    </section>

    {{-- LOGISTICS 4.0 SECTION --}}
    <section id="tecnologia" class="container-fluid py-5" style="background: rgba(46, 204, 113, 0.03);">
        <div class="container py-5">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('images/hero_scanner_mobile.png') }}" class="img-fluid rounded-4 shadow-lg border border-success" alt="Escaneo Móvil">
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <span class="badge bg-success mb-3">EXCLUSIVO 4.0</span>
                    <h2 class="display-4 fw-bold mb-4">Magic Scan Pro: <span class="text-success">Tu teléfono es un escáner.</span></h2>
                    <p class="fs-5 text-muted">Aprovechá la cámara de cualquier celular para vender y cargar stock. Sin cables, sin hardware caro, sin complicaciones. Nuestra tecnología procesa códigos de barras en milisegundos con una precisión industrial.</p>
                    <ul class="list-unstyled mt-4">
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check-circle-fill text-success fs-4 me-3"></i> <span>Velocidad de respuesta ultrarrápida.</span></li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check-circle-fill text-success fs-4 me-3"></i> <span>Detección automática de artículos duplicados.</span></li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check-circle-fill text-success fs-4 me-3"></i> <span>Integración nativa con el Punto de Venta (POS).</span></li>
                    </ul>
                </div>
            </div>

            <div class="row align-items-center flex-row-reverse mt-5 pt-lg-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('images/inventory_sessions_qr.png') }}" class="img-fluid rounded-4 shadow-lg border border-primary" alt="Inventario Colaborativo">
                </div>
                <div class="col-lg-6 pe-lg-5">
                    <span class="badge bg-primary mb-3">NOVEDAD: MULTI-USUARIO</span>
                    <h2 class="display-4 fw-bold mb-4">Sincronización Colaborativa por <span class="text-primary">QR</span>.</h2>
                    <p class="fs-5 text-muted">¿Tenés muchos productos por contar? Generá un QR de sesión, entregalo a tus empleados y todos podrán escanear y subir stock al mismo tiempo. Sin necesidad de crear usuarios ni compartir accesos privados.</p>
                    <ul class="list-unstyled mt-4">
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-qr-code text-primary fs-4 me-3"></i> <span>Acceso temporal y seguro por sesión.</span></li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-people-fill text-primary fs-4 me-3"></i> <span>Varios operarios escaneando en simultáneo.</span></li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-printer-fill text-primary fs-4 me-3"></i> <span>Generador y Hub de Etiquetas PDF integrado.</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    {{-- FEATURES GRID --}}
    <section id="experiencia">
        <div class="text-center mb-5 mt-5">
            <h2 class="display-4 fw-bold mb-3">Tu Negocio, Elevado.</h2>
            <p class="text-muted fs-5">Tecnología de última generación al servicio de tu rentabilidad.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <i class="bi bi-upc-scan"></i>
                <h3>Magic Scan Pro</h3>
                <p>Convertí cualquier smartphone en un escáner profesional. Sin cables, sin hardware costoso. Escaneo veloz garantizado.</p>
            </div>
            <div class="feature-card">
                <i class="bi bi-qr-code-scan"></i>
                <h3>Sincro Colaborativa</h3>
                <p>Generá QRs temporales para auditorías de inventario masivas. Tus empleados escanean y el stock se ajusta solo.</p>
            </div>
            <div class="feature-card">
                <i class="bi bi-file-earmark-pdf"></i>
                <h3>Etiquetas Master</h3>
                <p>Diseño automático de etiquetas con código de barras en formato PDF. Listas para imprimir y pegar en segundos.</p>
            </div>
        </div>
    </section>

    {{-- ADVANCED ANALYTICS SECTION --}}
    <section id="reportes" class="py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 ps-lg-5">
                    <span class="badge bg-purple mb-3" style="background: var(--primary)">DASHBOARD 360°</span>
                    <h2 class="display-4 fw-bold mb-4">Reportes que te hacen <span class="text-primary">Ganar Tiempo.</span></h2>
                    <p class="fs-5 text-muted">Olvidate de las planillas infinitas. Nuestro panel centralizado te muestra ventas, rentabilidad y ranking de productos en tiempo real con gráficas interactivas de alto impacto.</p>
                    <div class="row mt-4">
                        <div class="col-6 mb-3">
                            <h4 class="fw-bold mb-1">Ventas por Fecha</h4>
                            <p class="small text-muted">Comparativas anuales y mensuales.</p>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="fw-bold mb-1">Ranking Pro</h4>
                            <p class="small text-muted">Descubrí tu top 10 de productos estrella.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('images/promo/reportes.png') }}" class="img-fluid rounded-4 shadow-lg border border-secondary" alt="Reportes MultiPOS">
                </div>
            </div>
        </div>
    </section>

    {{-- SUPPLIERS & EXPENSES --}}
    <section id="gestion" class="py-5" style="background: rgba(255, 255, 255, 0.02);">
        <div class="container py-5">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-lg-6 pe-lg-5">
                    <span class="badge bg-info mb-3 text-dark">GESTIÓN INTEGRAL</span>
                    <h2 class="display-4 fw-bold mb-4">Proveedores y Gastos <span class="text-info">bajo Control.</span></h2>
                    <p class="fs-5 text-muted">Cargar facturas de compra y controlar gastos fijos nunca fue tan elegante. Vinculá tus egresos a categorías personalizadas y mantené tus finanzas siempre en verde.</p>
                    <ul class="list-unstyled mt-4">
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-person-badge-fill text-info fs-4 me-3"></i> <span>Directorio detallado de proveedores.</span></li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-wallet2 text-info fs-4 me-3"></i> <span>Módulo de gastos fijos y variables.</span></li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-graph-down-arrow text-info fs-4 me-3"></i> <span>Visualización de flujo de caja automático.</span></li>
                    </ul>
                </div>
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('images/promo/proveedores.png') }}" class="img-fluid rounded-4 shadow-lg border border-info" alt="Gestión de Proveedores">
                </div>
            </div>
        </div>
    </section>

    {{-- MAGIC SHOWCASE --}}
    <section class="magic-section shadow" id="tecnologia">
        <div class="magic-row">
            <div class="magic-img shadow-lg">
                <img src="{{ asset('images/inventory_sessions_qr.png') }}" alt="Collaborative Inventory">
            </div>
            <div class="magic-text">
                <div class="magic-badge">EXCLUSIVO</div>
                <h2>Auditorías a la Velocidad de la Luz</h2>
                <p class="fs-5 text-muted mb-4">¿Te toma días hacer inventario? Con MultiPOS habilitás una sesión, imprimís un QR y todo tu equipo empieza a escanear unidades con sus propios teléfonos. Mirás el progreso en tiempo real desde tu despacho.</p>
                <div class="bg-dark p-4 rounded-4 border border-secondary">
                    <h5 class="fw-bold mb-2 text-primary">✓ 0% Errores de carga</h5>
                    <h5 class="fw-bold mb-2 text-primary">✓ 100% Multidispositivo</h5>
                    <h5 class="fw-bold mb-0 text-primary">✓ Desactivación remota en 1 click</h5>
                </div>
            </div>
        </div>
    </section>

    {{-- AI MATCHER --}}
    <section class="ai-matcher" id="ai-assistant">
        <div class="magic-badge" style="background: var(--accent)">INTELIGENCIA ARTIFICIAL</div>
        <h2 class="display-5 fw-bold mb-3">¿Qué Plan necesitás realmente?</h2>
        <p class="text-dim fs-5 mb-5">Respondé estas 3 preguntas y nuestro asistente te dirá cuál es la mejor opción para vos.</p>
        
        <div id="ai-content">
            <div id="question-box" class="p-5 bg-black rounded-4 border border-secondary shadow-lg">
                <h3 id="question-text" class="mb-5 fw-bold">¿Cuántos locales físicos gestionás hoy?</h3>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <button class="step-btn" onclick="nextQuestion(1, 'solo')">Solo uno</button>
                    <button class="step-btn" onclick="nextQuestion(1, 'varios')">Entre 2 y 5</button>
                    <button class="step-btn" onclick="nextQuestion(1, 'franquicia')">Franquicia o +5</button>
                </div>
            </div>
        </div>
    </section>

    {{-- AI ASSISTANT SECTION --}}
    <section id="asistente" class="py-5" style="background: linear-gradient(180deg, transparent, rgba(139, 92, 246, 0.05));">
        <div class="container">
            <div class="row align-items-center bg-black border border-secondary p-5 rounded-5 shadow-lg">
                <div class="col-lg-4 text-center mb-4 mb-lg-0">
                    <div class="position-relative d-inline-block animate__animated animate__pulse animate__infinite">
                        <img src="{{ asset('images/ai_avatar.png') }}" class="rounded-circle border border-primary p-2 shadow-lg" style="width: 250px; height: 250px; object-fit: cover;" alt="Asistente IA">
                        <div class="position-absolute bottom-0 start-50 translate-middle-x badge bg-success rounded-pill px-4 py-2 border border-black" style="font-size: 0.9rem; margin-bottom: -10px">ESTADO: ONLINE</div>
                    </div>
                </div>
                <div class="col-lg-8 ps-lg-5">
                    <div class="magic-badge mb-3">TECNOLOGÍA CONECTADA</div>
                    <h2 class="display-4 fw-bold mb-3">Hola, soy <span class="text-primary text-gradient">Carina</span>, tu asistente virtual.</h2>
                    <p class="fs-4 text-muted mb-4 italic">"Mi voz es amable, dulce y estoy aquí para que MultiPOS sea lo mejor para tu negocio."</p>
                    <p class="fs-5 text-dim mb-5">Carina conoce cada detalle de nuestro sistema. Puede explicarte cómo funciona el stock colaborativo, guiarte en el alta de productos o simplemente charlar sobre qué plan te conviene más hoy para crecer.</p>
                    <div class="row gap-3">
                        <div class="col-md-5">
                            <a href="https://wa.me/{{ $wa_clean }}?text=Hola%20Carina,%20quiero%20conocer%20mas%20de%20MultiPOS" class="btn btn-primary btn-lg w-100 rounded-pill px-5 fw-bold shadow-lg">
                                <i class="bi bi-whatsapp me-2"></i> Hablar con Carina
                            </a>
                        </div>
                        <div class="col-md-5">
                            <button id="activate-voice-btn" class="btn btn-outline-secondary btn-lg w-100 rounded-pill px-5 fw-bold bg-dark">
                                <i class="bi bi-volume-up-fill me-2 text-primary"></i> <span id="voice-status">Escuchar a Carina</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PRICING --}}
    <section id="planes">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-2">Simplicidad en el Precio.</h2>
            <p class="text-muted">Elegí la escala que tu negocio merece hoy.</p>
        </div>
        <div class="pricing-grid">
            @forelse ($plans as $plan)
                <div class="price-card {{ $plan->name == 'PROFESSIONAL' ? 'featured' : '' }}" id="plan-{{ $plan->id }}">
                    <h4>{{ strtoupper($plan->name) }}</h4>
                    <div class="amount">${{ number_format($plan->price, 0, ',', '.') }} <span>/ mes</span></div>
                    <hr class="my-4 opacity-10">
                    <div class="description mb-4 text-dim small">
                        {{ $plan->description }}
                    </div>
                    <ul class="list-unstyled text-start small {{ $plan->name == 'PROFESSIONAL' ? '' : 'text-dim' }}">
                        <li class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i> Hasta {{ $plan->max_users }} Usuarios</li>
                        <li class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i> Hasta {{ $plan->max_products }} Productos</li>
                        <li class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i> {{ $plan->max_storage_mb }} MB Almacenamiento</li>
                        <li class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i> Escaneo Móvil Incluido</li>
                    </ul>
                    <a href="{{ route('register', ['plan' => $plan->id]) }}" class="btn {{ $plan->name == 'PROFESSIONAL' ? 'btn-primary' : 'btn-outline-light' }} w-100 rounded-pill mt-4">
                        {{ $plan->name == 'PROFESSIONAL' ? 'Elegir Profesional' : 'Comenzar' }}
                    </a>
                </div>
            @empty
                <div class="col-12 text-center text-muted">No hay planes disponibles en este momento.</div>
            @endforelse
            
            {{-- Enterprise ALWAYS visible --}}
            <div class="price-card">
                <h4>ENTERPRISE</h4>
                <div class="amount">CUSTOM</div>
                <hr class="my-4 opacity-10">
                <p class="text-dim">Grandes corporaciones y distribuidoras. Soluciones a medida con infraestructura dedicada.</p>
                <a href="#contacto" class="btn btn-outline-light w-100 rounded-pill mt-4">Contactar</a>
            </div>
        </div>
    </section>

    {{-- CONTACT SECTION --}}
    <section id="contacto" class="py-5 mb-5">
        <div class="container text-center">
            <h2 class="display-4 fw-bold mb-4">¿Hablamos hoy?</h2>
            <p class="fs-4 text-dim mb-5">Estamos a un clic de distancia para llevar tu negocio al siguiente nivel.</p>
            <div class="row justify-content-center">
                <div class="col-md-3">
                    <div class="p-4 bg-black border border-secondary rounded-4 hover-lift">
                        <i class="bi bi-telephone fs-1 text-primary"></i>
                        <h4 class="mt-3 text-white">Llamanos</h4>
                        <p class="text-muted">{{ config('platform.phone') }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 bg-black border border-secondary rounded-4 hover-lift">
                        <i class="bi bi-envelope fs-1 text-primary"></i>
                        <h4 class="mt-3 text-white">Email</h4>
                        <p class="text-muted">{{ config('platform.email') }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <a href="https://wa.me/{{ $wa_clean }}" class="text-decoration-none">
                        <div class="p-4 bg-black border border-secondary rounded-4 hover-lift">
                            <i class="bi bi-whatsapp fs-1 text-success"></i>
                            <h4 class="mt-3 text-white">WhatsApp</h4>
                            <p class="text-muted">Conversá en Vivo</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <h2 class="fw-bold mb-4">MultiPOS</h2>
        <p class="text-muted mb-5">El estándar de oro en gestión comercial SaaS.</p>
        <div class="d-flex justify-content-center gap-4 mb-5">
            <a href="#" class="text-dim fs-3 hover-text-primary"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-dim fs-3 hover-text-primary"><i class="bi bi-linkedin"></i></a>
            <a href="https://wa.me/{{ $wa_clean }}" class="text-dim fs-3 hover-text-success"><i class="bi bi-whatsapp"></i></a>
        </div>
        <p class="small text-muted">&copy; 2026 MultiPOS Cloud Management. Todos los derechos reservados.</p>
    </footer>

    <script>
        let currentStep = 1;
        let answers = {};

        function nextQuestion(step, answer) {
            answers[step] = answer;
            const container = document.getElementById('question-box');
            container.classList.add('animate__animated', 'animate__fadeOutLeft');
            
            setTimeout(() => {
                container.classList.remove('animate__fadeOutLeft');
                if(step === 1) {
                    document.getElementById('question-text').innerText = "¿Necesitás que varios empleados ajusten stock a la vez?";
                    container.innerHTML = `
                        <h3 id="question-text" class="mb-5 fw-bold">¿Necesitás que varios empleados ajusten stock a la vez?</h3>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <button class="step-btn" onclick="nextQuestion(2, 'si')">Sí, es vital</button>
                            <button class="step-btn" onclick="nextQuestion(2, 'no')">No, lo hago yo solo</button>
                        </div>
                    `;
                } else if(step === 2) {
                    processResult();
                }
                container.classList.add('animate__animated', 'animate__fadeInRight');
            }, 300);
        }

        function processResult() {
            const container = document.getElementById('question-box');
            let recommendation = "";
            let planId = "";

            if(answers[1] === 'solo' && answers[2] === 'no') {
                recommendation = "Para vos el plan STARTER es perfecto. Tenés todo lo que necesitás sin pagar de más.";
                planId = "plan-starter";
            } else {
                recommendation = "Tu negocio escala rápido. Te recomendamos el plan PROFESSIONAL para activar las sesiones colaborativas.";
                planId = "plan-pro";
            }

            container.innerHTML = `
                <div class="animate__animated animate__zoomIn">
                    <i class="bi bi-stars fs-1 text-primary mb-3 d-block"></i>
                    <h3 class="fw-bold mb-4">¡Tenemos tu plan ideal!</h3>
                    <p class="fs-5 text-muted mb-5">${recommendation}</p>
                    <a href="#${planId}" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow">VER DETALLES DEL PLAN</a>
                </div>
            `;

            // Highlight the recommended plan
            document.querySelectorAll('.price-card').forEach(c => c.style.opacity = "0.5");
            document.getElementById(planId).style.opacity = "1";
            document.getElementById(planId).style.transform = "scale(1.1)";
            document.getElementById(planId).style.borderColor = "var(--primary)";
        }

        // CARINA CHAT LOGIC - REDISEÑO PRO CON VOZ E IA
        const bubble = document.getElementById('chat-bubble');
        const windowChat = document.getElementById('chat-window');
        const closeChat = document.getElementById('close-chat');
        const sendBtn = document.getElementById('send-btn');
        const chatInput = document.getElementById('chat-input');
        const chatInteractions = document.getElementById('chat-interactions');
        const typingId = document.getElementById('typing-indicator');
        const voiceBtn = document.getElementById('activate-voice-btn');
        let voiceActive = false;

        // Conocimiento de Carina
        const kb = {
            "precio": "Nuestros planes arrancan en solo $25.000 ARS por mes para el plan Starter. Si crecés, el plan Pro vale $45.000 y el Business $89.000 ARS. Todos incluyen escaneo móvil.",
            "stock": "¡Me encanta el stock! Nuestra tecnología Magic Scan Pro permite que tu teléfono sea un escáner industrial. También podés hacer inventarios colaborativos por QR.",
            "bunny": "Usamos Bunny.net para que tus videos y fotos de productos vuelen. Tu web será la más rápida del mercado gracias a este almacenamiento especializado.",
            "hola": "¡Hola! Qué alegría saludarte. Soy Carina, tu asistente digital para que tu negocio crezca con la mejor tecnología. ✨",
            "carina": "Esa soy yo, tu voz amiga en MultiPOS. Estoy aquí las 24 horas para asesorarte de la forma más dulce y amable.",
            "franquicia": "Para franquicias tenemos el plan Business con locales ilimitados y reportes avanzados. ¡Es la opción más potetne!",
            "contacto": "Podés hablar con el dueño de MultiPOS por WhatsApp directo con el botón que te dejo abajo. ¡Te va a atender súper bien! 😊"
        };

        bubble.onclick = () => {
             const isHidden = windowChat.style.display === 'none' || windowChat.classList.contains('d-none');
             if(isHidden) {
                 windowChat.style.display = 'flex';
                 windowChat.classList.remove('d-none');
             } else {
                 windowChat.style.display = 'none';
                 windowChat.classList.add('d-none');
             }
        };

        closeChat.onclick = () => {
            windowChat.style.display = 'none';
            windowChat.classList.add('d-none');
        };

        voiceBtn.onclick = () => {
            voiceActive = !voiceActive;
            voiceBtn.classList.toggle('btn-primary');
            document.getElementById('voice-status').innerText = voiceActive ? "Voz: ACTIVADA 🌸" : "Escuchar a Carina";
            if(voiceActive) speak("¡Me encanta que quieras escucharme! Ahora te responderé hablando con todo mi cariño.");
        };

        function speak(text) {
            if(!voiceActive) return;
            window.speechSynthesis.cancel();
            const msg = new SpeechSynthesisUtterance(text);
            msg.lang = 'es-ES';
            msg.rate = 0.95; // Un poco más lento para ser amable
            msg.pitch = 1.1; // Un poco más agudo para ser dulce
            window.speechSynthesis.speak(msg);
        }

        function addMessage(text, isUser = false) {
            const div = document.createElement('div');
            div.className = `mb-4 animate__animated animate__fadeInUp ${isUser ? 'text-end' : ''}`;
            div.innerHTML = `
                <div class="${isUser ? 'bg-primary text-white' : 'bg-dark text-white-50 border border-secondary'} p-3 rounded-4 d-inline-block shadow-sm" style="max-width: 85%; font-size: 1rem;">
                    ${text}
                </div>
            `;
            chatInteractions.appendChild(div);
            // Scroll al final
            const chatBody = document.getElementById('chat-body');
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        sendBtn.onclick = () => {
            const msg = chatInput.value.trim();
            if(!msg) return;
            addMessage(msg, true);
            chatInput.value = '';
            
            // Mostrar indicador de escritura
            typingId.classList.remove('d-none');
            const chatBody = document.getElementById('chat-body');
            chatBody.scrollTop = chatBody.scrollHeight;

            // Lógica de respuesta inteligente simulada
            setTimeout(() => {
                typingId.classList.add('d-none');
                let response = "¡Qué buena pregunta! Me encantaría contarte más sobre ese detalle si hablamos por WhatsApp con una atención personalizada para tu negocio. 🌸";
                const lower = msg.toLowerCase();
                
                // Buscar en KB
                Object.keys(kb).forEach(key => {
                    if(lower.includes(key)) response = kb[key];
                });

                addMessage(response);
                speak(response);
                
                // Sugerir WhatsApp después de un asesoramiento
                if(!lower.includes('hola')) {
                   setTimeout(() => {
                       addMessage(`<a href="https://wa.me/{{ $wa_clean }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill mt-2 fw-bold px-3 py-2"><i class="bi bi-whatsapp me-2"></i> Seguir por WhatsApp 📱</a>`);
                   }, 800);
                }
            }, 1200);
        };

        chatInput.onkeypress = (e) => { if(e.key === 'Enter') sendBtn.click(); };
    </script>

</body>
</html>
