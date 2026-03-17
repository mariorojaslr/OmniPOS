<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MultiPOS - El Cerebro de tu Negocio</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="{{ asset('favicon.png') }}">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --accent: #f43f5e;
            --bg-dark: #0f172a;
            --glass: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-dim: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* --- BACKGROUND ANIMATION --- */
        .bg-blobs {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .blob {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, var(--primary) 0%, transparent 70%);
            filter: blur(80px);
            opacity: 0.15;
            animation: move 20s infinite alternate;
        }

        .blob-1 { top: -100px; left: -100px; }
        .blob-2 { bottom: -100px; right: -100px; animation-delay: -5s; }

        @keyframes move {
            from { transform: translate(0, 0) scale(1); }
            to { transform: translate(100px, 50px) scale(1.2); }
        }

        /* --- NAVIGATION --- */
        nav {
            padding: 1.5rem 10%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            z-index: 100;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--glass-border);
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -1px;
            background: linear-gradient(90deg, #fff, var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-main);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: 0.3s;
            opacity: 0.7;
        }

        .nav-links a:hover {
            opacity: 1;
            color: var(--primary);
        }

        .btn-cta {
            background: var(--primary);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.5);
            display: inline-block;
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px -5px rgba(99, 102, 241, 0.6);
            background: var(--primary-dark);
            color: white;
        }

        /* --- HERO SECTION --- */
        section {
            padding: 8rem 10%;
        }

        .hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 90vh;
        }

        .hero-content {
            flex: 1;
            max-width: 600px;
        }

        .hero-badge {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 2rem;
            display: inline-block;
        }

        .hero h1 {
            font-size: 4rem;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            font-weight: 800;
        }

        .hero p {
            font-size: 1.2rem;
            color: var(--text-dim);
            margin-bottom: 2.5rem;
        }

        .hero-image {
            flex: 1.2;
            display: flex;
            justify-content: center;
            perspective: 1000px;
        }

        .hero-image img {
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.5);
            transform: rotateY(-15deg) rotateX(10deg);
            transition: 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .hero-image img:hover {
            transform: rotateY(0) rotateX(0) scale(1.05);
        }

        /* --- FEATURES --- */
        .features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            padding-top: 4rem;
        }

        .feature-card {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            padding: 3.5rem 2.5rem;
            border-radius: 35px;
            transition: 0.4s;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .feature-card:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-12px);
            border-color: var(--primary);
        }

        .feature-icon {
            font-size: 2.8rem;
            color: var(--primary);
            margin-bottom: 1.8rem;
            display: block;
        }

        .feature-card h3 {
            font-size: 1.6rem;
            margin-bottom: 1.2rem;
            font-weight: 700;
        }

        .feature-card p {
            color: var(--text-dim);
            font-size: 1rem;
            line-height: 1.7;
        }

        /* --- SHOWCASE --- */
        .showcase-row {
            display: flex;
            align-items: center;
            gap: 6rem;
            margin-bottom: 12rem;
        }

        .showcase-row:nth-child(even) {
            flex-direction: row-reverse;
        }

        .showcase-text {
            flex: 1;
        }

        .showcase-text h2 {
            font-size: 3rem;
            margin-bottom: 1.8rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .showcase-text p {
            font-size: 1.15rem;
            color: var(--text-dim);
            margin-bottom: 2rem;
        }

        .showcase-image {
            flex: 1.5;
        }

        .showcase-image img {
            width: 100%;
            border-radius: 28px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 40px 80px rgba(0,0,0,0.4);
            transition: 0.5s;
        }
        
        .showcase-image img:hover {
            transform: scale(1.02);
            border-color: var(--primary);
        }

        /* --- FOOTER --- */
        footer {
            text-align: center;
            padding: 8rem 10% 4rem;
            border-top: 1px solid var(--glass-border);
            background: linear-gradient(to bottom, transparent, rgba(99, 102, 241, 0.05));
        }

        .footer-cta h2 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            font-weight: 800;
        }

        .footer-cta p {
            margin-bottom: 3rem;
            color: var(--text-dim);
            font-size: 1.3rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        /* RESPONSIVE */
        @media (max-width: 1200px) {
            .hero h1 { font-size: 3.2rem; }
            section { padding: 6rem 5%; }
        }

        @media (max-width: 992px) {
            nav { padding: 1.5rem 5%; }
            .hero, .showcase-row {
                flex-direction: column !important;
                text-align: center;
                gap: 4rem;
            }
            .hero h1 { font-size: 3rem; }
            .features { grid-template-columns: 1fr; }
            .hero-image img { width: 100%; transform: none !important; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <nav>
        <a href="#" class="logo">
            <img src="{{ asset('images/promo/logo.png') }}" alt="MultiPOS Logo" style="height: 40px; vertical-align: middle; margin-right: 10px;">
            MultiPOS
        </a>
        <div class="nav-links">
            <a href="#features">Funciones</a>
            <a href="#pos">Punto de Venta</a>
            <a href="#gastos">Finanzas</a>
            <a href="#logistica">Logística</a>
            <a href="#reportes">Reportes</a>
            <a href="{{ route('login') }}" class="btn-cta">Acceder al Sistema</a>
        </div>
    </nav>

    <section class="hero" id="inicio">
        <div class="hero-content">
            <span class="hero-badge">SaaS de Gestión Inteligente v4.0</span>
            <h1>Liderá tu Negocio al Próximo Nivel</h1>
            <p>La plataforma definitiva de gestión para emprendedores. Ventas, Stock, Finanzas y Gastos Operativos unificados en una interfaz premium irresistible.</p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('login') }}" class="btn-cta">Empezar Ahora Gratis</a>
                <a href="#features" class="btn-cta" style="background: transparent; border: 1px solid var(--glass-border);">Explorar Funciones</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="{{ asset('images/promo/dashboard.png') }}" alt="Dashboard MultiPOS">
        </div>
    </section>

    <section id="features">
        <div style="text-align: center; margin-bottom: 5rem;">
            <h2 style="font-size: 3rem; font-weight: 800;">Más que un simple POS</h2>
            <p style="color: var(--text-dim); font-size: 1.2rem;">Todo lo que necesitás para tener el control absoluto de tu empresa.</p>
        </div>
        
        <div class="features">
            <div class="feature-card">
                <i class="bi bi-lightning-charge feature-icon"></i>
                <h3>Venta Ultra-Rápida</h3>
                <p>Interfaz POS diseñada para la velocidad. Cobrá, emití tickets y gestioná devoluciones en segundos sin complicaciones.</p>
            </div>
            <div class="feature-card">
                <i class="bi bi-wallet2 feature-icon"></i>
                <h3>Gastos Operativos</h3>
                <p>No pierdas un solo peso. Registrá cada egreso y adjuntá fotos de comprobantes con un simple pegado instantáneo.</p>
            </div>
            <div class="feature-card">
                <i class="bi bi-graph-up-arrow feature-icon"></i>
                <h3>Reportes Vivos</h3>
                <p>Visualizá tu utilidad real neta al instante. Gráficos modernos que convierten tus datos en decisiones ganadoras.</p>
            </div>
        </div>
    </section>

    <section class="showcase" id="pos">
        <div class="showcase-row">
            <div class="showcase-text">
                <span style="color: var(--accent); font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">#01 PUNTO DE VENTA</span>
                <h2>Intuitivo, Veloz y Confiable</h2>
                <p>Gestioná productos con variantes, combos y categorías personalizadas. MultiPOS se adapta a tu rubro, ya sea gastronomía, indumentaria o retail general.</p>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; text-align: left; color: var(--text-dim);">
                    <div style="margin-bottom: 0.8rem;"><i class="bi bi-check2-circle me-2 text-primary"></i> Notas de Crédito</div>
                    <div style="margin-bottom: 0.8rem;"><i class="bi bi-check2-circle me-2 text-primary"></i> Stock en tiempo real</div>
                    <div style="margin-bottom: 0.8rem;"><i class="bi bi-check2-circle me-2 text-primary"></i> Múltiples medios de pago</div>
                    <div style="margin-bottom: 0.8rem;"><i class="bi bi-check2-circle me-2 text-primary"></i> Tickets Personalizados</div>
                </div>
            </div>
            <div class="showcase-image">
                <img src="{{ asset('images/promo/pos.png') }}" alt="POS MultiPOS">
            </div>
        </div>

        <div class="showcase-row" id="gastos">
            <div class="showcase-text">
                <span style="color: var(--accent); font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">#02 FINANZAS</span>
                <h2>Domá tus Costos Fijos</h2>
                <p>El primer sistema que entiende que un negocio tiene gastos reales. Cargá sueldos, alquiler y servicios. Adjuntá la foto de la factura al momento haciendo Ctrl+V.</p>
                <div class="feature-card" style="margin-top: 2rem; padding: 2rem; text-align: left; background: rgba(99, 102, 241, 0.1);">
                    <p style="color: #fff; font-style: italic; font-size: 1.1rem;">"Desde que usamos MultiPOS dejamos de anotar gastos en cuadernos. Ahora todo está en el sistema con su comprobante digital."</p>
                    <small style="display: block; margin-top: 1.5rem; opacity: 0.7; font-weight: 600;">— Mario Rojas (Empresario Retail)</small>
                </div>
            </div>
            <div class="showcase-image">
                <img src="{{ asset('images/promo/gastos.png') }}" alt="Gestión de Gastos">
            </div>
        </div>
        <div class="showcase-row" id="logistica">
            <div class="showcase-text">
                <span style="color: var(--accent); font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">#03 LOGÍSTICA</span>
                <h2>Inventario Bajo Control</h2>
                <p>Olvidate de las sorpresas. Nuestro motor de alertas te avisa antes de que te quedes sin stock. Gestioná rubros, marcas y ubicaciones con total precisión.</p>
                <ul style="list-style: none; margin-top: 1.5rem; color: var(--text-dim);">
                    <li style="margin-bottom: 0.8rem;"><i class="bi bi-check2-circle me-2 text-primary"></i> Alertas de Stock Crítico</li>
                    <li style="margin-bottom: 0.8rem;"><i class="bi bi-check2-circle me-2 text-primary"></i> Actualización Masiva de Precios</li>
                    <li><i class="bi bi-check2-circle me-2 text-primary"></i> Gestión de Múltiples Depósitos</li>
                </ul>
            </div>
            <div class="showcase-image">
                <img src="{{ asset('images/promo/logistica.png') }}" alt="Logística MultiPOS">
            </div>
        </div>

        <div class="showcase-row" id="reportes">
            <div class="showcase-text">
                <span style="color: var(--accent); font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">#04 ANALÍTICA</span>
                <h2>Decisiones Basadas en Datos</h2>
                <p>Accedé a reportes inteligentes de rentabilidad, mejores productos y rendimiento de empleados. Gráficos interactivos de alta calidad que te muestran el camino al crecimiento.</p>
                <div class="feature-card" style="margin-top: 2rem; padding: 2rem; text-align: left; background: rgba(99, 102, 241, 0.1);">
                    <p style="color: #fff; font-weight: 600;">"Lo que no se mide, no se mejora. Con MultiPOS medís cada movimiento de tu negocio."</p>
                </div>
            </div>
            <div class="showcase-image">
                <img src="{{ asset('images/promo/reportes.png') }}" alt="Reportes MultiPOS">
            </div>
        </div>

        <div class="showcase-row" id="proveedores">
            <div class="showcase-text">
                <span style="color: var(--accent); font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">#05 PROVEEDORES</span>
                <h2>Relaciones Profesionales</h2>
                <p>Llevá la cuenta corriente de tus proveedores de forma impecable. Registrá facturas de compra, pagos parciales y notas de crédito de compra sin errores.</p>
                <ul style="list-style: none; margin-top: 1.5rem; color: var(--text-dim);">
                    <li style="margin-bottom: 0.8rem;"><i class="bi bi-check2-circle me-2 text-primary"></i> Libro Mayor de Proveedores</li>
                    <li style="margin-bottom: 0.8rem;"><i class="bi bi-check2-circle me-2 text-primary"></i> Control de Deudas Pendientes</li>
                    <li><i class="bi bi-check2-circle me-2 text-primary"></i> Cuentas Corrientes Impecables</li>
                </ul>
            </div>
            <div class="showcase-image">
                <img src="{{ asset('images/promo/proveedores.png') }}" alt="Proveedores MultiPOS">
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-cta">
            <h2>¿Listo para el gran salto?</h2>
            <p>Unite a los cientos de negocios que ya están profesionalizando su gestión con la plataforma más potente del mercado.</p>
            <a href="{{ route('login') }}" class="btn-cta" style="padding: 1.2rem 4rem; font-size: 1.2rem;">Lanzar mi Negocio Ahora</a>
        </div>
        <div style="margin-top: 6rem; opacity: 0.5; font-size: 0.9rem;">
            &copy; 2026 MultiPOS SaaS - El Estándar de Gestión para el Siglo XXI<br>
            Desarrollado con pasión para emprendedores reales.
        </div>
    </footer>

    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>

</body>
</html>
