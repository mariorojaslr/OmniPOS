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

    {{-- PRICING --}}
    <section id="planes">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-2">Simplicidad en el Precio.</h2>
            <p class="text-muted">Elegí la escala que tu negocio merece hoy.</p>
        </div>
        <div class="pricing-grid">
            <div class="price-card" id="plan-starter">
                <h4>STARTER</h4>
                <div class="amount">$25.000 <span>/ mes</span></div>
                <hr class="my-4 opacity-10">
                <ul class="list-unstyled text-start small text-dim">
                    <li class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i> 1 Local / Sucursal</li>
                    <li class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i> Hasta 500 Productos</li>
                    <li class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i> Escaneo Móvil Incluido</li>
                    <li class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i> Soporte Básico</li>
                </ul>
                <a href="#" class="btn btn-outline-light w-100 rounded-pill mt-4">Comenzar</a>
            </div>
            <div class="price-card featured" id="plan-pro">
                <h4>PROFESSIONAL</h4>
                <div class="amount">$45.000 <span>/ mes</span></div>
                <hr class="my-4 opacity-10">
                <ul class="list-unstyled text-start small">
                    <li class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i> Hasta 3 Sucursales</li>
                    <li class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i> Productos Ilimitados</li>
                    <li class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i> Sesiones Colaborativas</li>
                    <li class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i> Etiquetas PDF Pro</li>
                    <li class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i> Soporte Prioritario</li>
                </ul>
                <a href="#" class="btn btn-primary w-100 rounded-pill mt-4 fw-bold">Elegir Profesional</a>
            </div>
            <div class="price-card" id="plan-business">
                <h4>BUSINESS</h4>
                <div class="amount">$89.000 <span>/ mes</span></div>
                <hr class="my-4 opacity-10">
                <ul class="list-unstyled text-start small text-dim">
                    <li class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i> Locales Ilimitados</li>
                    <li class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i> Streaming de Video Bunny</li>
                    <li class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i> Reportes Avanzados</li>
                    <li class="mb-2"><i class="bi bi-check-circle me-2 text-success"></i> API de Integración</li>
                </ul>
                <a href="#" class="btn btn-outline-light w-100 rounded-pill mt-4">Comenzar</a>
            </div>
            <div class="price-card">
                <h4>ENTERPRISE</h4>
                <div class="amount">CUSTOM</div>
                <hr class="my-4 opacity-10">
                <p class="text-dim">Grandes corporaciones y distribuidoras. Soluciones a medida con infraestructura dedicada.</p>
                <a href="#" class="btn btn-outline-light w-100 rounded-pill mt-4">Contactar</a>
            </div>
        </div>
    </section>

    <footer>
        <h2 class="fw-bold mb-4">MultiPOS</h2>
        <p class="text-muted mb-5">El estándar de oro en gestión comercial SaaS.</p>
        <div class="d-flex justify-content-center gap-4 mb-5">
            <a href="#" class="text-dim fs-3"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-dim fs-3"><i class="bi bi-linkedin"></i></a>
            <a href="#" class="text-dim fs-3"><i class="bi bi-whatsapp"></i></a>
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
    </script>

</body>
</html>
