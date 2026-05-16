<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OmniPOS | La Suite Intergaláctica de Software</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primario: #0d6efd;
            --bg-deep: #020617;
            --bg-accent: #0b1120;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-deep);
            color: #ffffff;
            overflow-x: hidden;
        }

        /* HERO CAROUSEL PREMIUM */
        .hero-section {
            position: relative;
            height: 90vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .carousel-item {
            height: 90vh;
            background-size: cover;
            background-position: center;
            animation: kenburns 20s infinite alternate;
        }
        @keyframes kenburns {
            from { transform: scale(1); }
            to { transform: scale(1.1); }
        }
        .carousel-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to bottom, rgba(2,6,23,0.1) 0%, rgba(2,6,23,0.95) 100%);
            z-index: 2;
        }
        .hero-content {
            position: relative;
            z-index: 10;
        }
        .hero-title {
            font-size: 5rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -2px;
            margin-bottom: 2rem;
            text-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        /* SECCIONES DE VERTICALES */
        .sector-section {
            padding: 100px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .sector-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
        }
        .sector-desc {
            font-size: 1.25rem;
            color: rgba(255,255,255,0.7);
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }
        .sector-image-container {
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .sector-image {
            width: 100%;
            transition: transform 0.5s ease;
        }
        .sector-image:hover {
            transform: scale(1.05);
        }

        .narrative-card {
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .narrative-title { font-weight: 800; color: #fff; margin-bottom: 10px; }
        .narrative-text { color: rgba(255,255,255,0.6); margin-bottom: 0; }

        .feature-box {
            background: rgba(255,255,255,0.02);
            padding: 20px;
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.05);
            height: 100%;
        }
        .feature-icon { font-size: 2rem; margin-bottom: 15px; display: block; color: var(--color-primario); }

        .btn-premium {
            background: #fff;
            color: #000;
            padding: 18px 45px;
            border-radius: 50px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
            border: none;
        }
        .btn-premium:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 30px rgba(255,255,255,0.2);
            background: #fff;
        }

        .tracking-widest { letter-spacing: 2px; }
        .text-readable { color: rgba(255,255,255,0.7); }
    </style>
</head>
<body>

    <!-- NAVBAR PREMIUM -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top px-4 py-3" style="background: rgba(2, 6, 23, 0.8); backdrop-filter: blur(20px);">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center gap-3" href="#">
                <img src="{{ asset('images/logo_omnipos.png') }}" alt="OmniPOS" style="height: 40px;">
                <span class="fw-bold tracking-widest fs-4">OMNIPOS</span>
            </a>
            <div class="d-flex gap-3">
                <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4 fw-bold">ACCESO AGENTES</a>
                <a href="{{ route('demo.mode') }}" class="btn btn-primary rounded-pill px-4 fw-bold">VER DEMO</a>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <div id="heroCarousel" class="carousel slide carousel-fade hero-section" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('{{ asset('images/c_1.png') }}');"></div>
            <div class="carousel-item" style="background-image: url('{{ asset('images/c_2.png') }}');"></div>
            <div class="carousel-item" style="background-image: url('{{ asset('images/c_3.png') }}');"></div>
        </div>
        <div class="carousel-overlay"></div>
        
        <div class="hero-content">
            <div class="container">
                <h1 class="hero-title">OmniPOS: <br>La Suite Intergaláctica.</h1>
                <p class="lead text-white mx-auto mb-5" style="max-width: 800px; font-size: 1.5rem; text-shadow: 0 2px 10px rgba(0,0,0,0.8);">
                    El ecosistema de gestión definitivo para empresas que no conocen fronteras. 
                    Módulos especializados para Salud, Gastronomía, Retail y más.
                </p>
                <a href="#soluciones" class="btn btn-premium">Explorar Galaxia de Soluciones</a>
            </div>
        </div>
    </div>

    <!-- VERTICALES -->
    <div id="soluciones">

        {{-- OMNIHEALTH --}}
        <section class="sector-section" style="background: linear-gradient(180deg, #020617 0%, #0b1120 100%);">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-4 fw-bold tracking-widest">VERTICAL SALUD</span>
                        <h2 class="sector-title">OmniHealth Pro</h2>
                        <p class="sector-desc">
                            Transforma la gestión médica con una plataforma que integra historias clínicas, turnos inteligentes y liquidaciones de obras sociales en un solo lugar.
                        </p>
                        <div class="narrative-card border-primary border-opacity-25">
                            <h5 class="narrative-title">Precisión & Cuidado:</h5>
                            <p class="narrative-text">
                                Diseñado para clínicas y consultorios que exigen máxima eficiencia. 
                                Desde la primera cita hasta la facturación electrónica, cada paso está optimizado.
                            </p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <i class="bi bi-heart-pulse feature-icon"></i>
                                    <h6 class="fw-bold fs-5 text-white">Historia Clínica</h6>
                                    <p class="text-readable small mb-0">Evoluciones, diagnósticos y adjuntos médicos con seguridad de grado militar.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <i class="bi bi-calendar-check feature-icon"></i>
                                    <h6 class="fw-bold fs-5 text-white">Agenda Inteligente</h6>
                                    <p class="text-readable small mb-0">Gestión de turnos por profesional, recordatorios automáticos y salas de espera.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="sector-image-container">
                            <img src="{{ asset('images/h_health.png') }}" alt="Salud" class="sector-image">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- OMNIGASTRO --}}
        <section class="sector-section" style="background: linear-gradient(180deg, #0b1120 0%, #020617 100%);">
            <div class="container">
                <div class="row align-items-center g-5 flex-lg-row-reverse">
                    <div class="col-lg-6">
                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill mb-4 fw-bold tracking-widest">VERTICAL GASTRONOMÍA</span>
                        <h2 class="sector-title">OmniGastro Elite</h2>
                        <p class="sector-desc">
                            El latido de tu restaurante en tiempo real. OmniGastro controla desde la comanda en cocina hasta la entrega a domicilio, pasando por la gestión de mozos y mesas.
                        </p>
                        <div class="narrative-card border-warning border-opacity-25">
                            <h5 class="narrative-title">Sabor & Tecnología:</h5>
                            <p class="narrative-text">
                                No importa si vendes pizzas, lomitos o alta cocina. Maneja pedidos para llevar, delivery con seguimiento y mesas con la agilidad que tu salón requiere.
                            </p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <i class="bi bi-egg-fried feature-icon text-warning"></i>
                                    <h6 class="fw-bold fs-5 text-white">Monitor KDS</h6>
                                    <p class="text-readable small mb-0">Control visual de pedidos en cocina, tiempos de preparación y avisos a mozos.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <i class="bi bi-bicycle feature-icon text-warning"></i>
                                    <h6 class="fw-bold fs-5 text-white">Logística de Entrega</h6>
                                    <p class="text-readable small mb-0">Gestión de repartidores propia, zonas de entrega y cobros en domicilio.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="sector-image-container">
                            <img src="{{ asset('images/h_gastro.png') }}" alt="Gastronomía" class="sector-image">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- OMNIMARKET --}}
        <section class="sector-section" style="background: linear-gradient(180deg, #020617 0%, #0b1120 100%);">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-4 fw-bold tracking-widest">VERTICAL RETAIL</span>
                        <h2 class="sector-title">OmniMarket Super</h2>
                        <p class="sector-desc">
                            Velocidad de checkout inigualable. Diseñado para supermercados y tiendas de alto tráfico que requieren facturación electrónica instantánea y control de stock masivo.
                        </p>
                        <div class="narrative-card border-success border-opacity-25">
                            <h5 class="narrative-title">Volumen & Velocidad:</h5>
                            <p class="narrative-text">
                                Escaneo ultra-rápido, manejo de múltiples cajas y sincronización total con el inventario central. La eficiencia que tu retail necesita para crecer.
                            </p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <i class="bi bi-upc-scan feature-icon text-success"></i>
                                    <h6 class="fw-bold fs-5 text-white">POS Ultra-Fast</h6>
                                    <p class="text-readable small mb-0">Facturación en un solo clic, compatible con cualquier hardware fiscal.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <i class="bi bi-boxes feature-icon text-success"></i>
                                    <h6 class="fw-bold fs-5 text-white">Multi-Almacén</h6>
                                    <p class="text-readable small mb-0">Transferencias entre sucursales, auditoría de stock y reposición automática.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="sector-image-container">
                            <img src="{{ asset('images/h_market.png') }}" alt="Supermercados" class="sector-image">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- OMNIRETAIL --}}
        <section class="sector-section" style="background: linear-gradient(180deg, #0b1120 0%, #020617 100%);">
            <div class="container">
                <div class="row align-items-center g-5 flex-lg-row-reverse">
                    <div class="col-lg-6">
                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill mb-4 fw-bold tracking-widest">VERTICAL COMERCIO</span>
                        <h2 class="sector-title">OmniRetail Studio</h2>
                        <p class="sector-desc">
                            La moda y el diseño merecen una gestión a su altura. Controla talles, colores y colecciones con una interfaz elegante que potencia tu marca.
                        </p>
                        <div class="narrative-card border-info border-opacity-25">
                            <h5 class="narrative-title">Estilo & Organización:</h5>
                            <p class="narrative-text">
                                Perfectamente adaptado para boutiques, zapaterías y tiendas de diseño. Gestiona tu catálogo online y tus ventas físicas en una sola plataforma.
                            </p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <i class="bi bi-grid-3x3-gap feature-icon text-info"></i>
                                    <h6 class="fw-bold fs-5 text-white">Matriz de Variantes</h6>
                                    <p class="text-readable small mb-0">Control total sobre talles, colores y temporadas sin complicaciones.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <i class="bi bi-phone-vibrate feature-icon text-info"></i>
                                    <h6 class="fw-bold fs-5 text-white">Venta Móvil</h6>
                                    <p class="text-readable small mb-0">Vende desde cualquier lugar del local con tablets o smartphones.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="sector-image-container">
                            <img src="{{ asset('images/h_retail.png') }}" alt="Tiendas" class="sector-image">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- OMNISTAY --}}
        <section class="sector-section" style="background: linear-gradient(180deg, #020617 0%, #0b1120 100%);">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-4 fw-bold tracking-widest">VERTICAL HOSPITALIDAD</span>
                        <h2 class="sector-title">OmniStay Resort</h2>
                        <p class="sector-desc">
                            Hospitalidad de alta gama. OmniStay integra cada rincón del hotel para una experiencia de huésped superior. Desde la reserva hasta el bar, todo fluye en una sola cuenta maestra.
                        </p>
                        <div class="narrative-card border-primary border-opacity-25">
                            <h5 class="narrative-title">Lujo & Control:</h5>
                            <p class="narrative-text">
                                Gestiona habitaciones (UTI, Terapia, Ambulatoria), personal de limpieza y cargos directos desde el restaurante o spa a la cuenta del huésped.
                            </p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <i class="bi bi-door-open feature-icon"></i>
                                    <h6 class="fw-bold fs-5 text-white">Recepción Master</h6>
                                    <p class="text-readable small mb-0">Check-in digital, mapa visual de habitaciones y estados de mantenimiento.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <i class="bi bi-receipt-cutoff feature-icon"></i>
                                    <h6 class="fw-bold fs-5 text-white">Folio Integrado</h6>
                                    <p class="text-readable small mb-0">Cargos automáticos desde el Bar, Room Service y Minibar a la habitación.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="sector-image-container">
                            <img src="{{ asset('images/h_stay.png') }}" alt="Hotelería" class="sector-image">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- OMNIASSOC --}}
        <section class="sector-section" style="background: linear-gradient(180deg, #0b1120 0%, #020617 100%);">
            <div class="container">
                <div class="row align-items-center g-5 flex-lg-row-reverse">
                    <div class="col-lg-6">
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-4 fw-bold tracking-widest">VERTICAL INSTITUCIONAL</span>
                        <h2 class="sector-title">OmniAssoc Elite</h2>
                        <p class="sector-desc">
                            La solución definitiva para Gremios, Asociaciones y Clubes con membresías. OmniAssoc transforma la gestión de socios en una experiencia digital fluida.
                        </p>
                        <div class="narrative-card border-success border-opacity-25">
                            <h5 class="narrative-title">Comunidad Conectada:</h5>
                            <p class="narrative-text">
                                Emite credenciales digitales, gestiona el cobro masivo de cuotas y envía comunicaciones oficiales con un solo clic.
                            </p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <i class="bi bi-people feature-icon text-success"></i>
                                    <h6 class="fw-bold fs-5 text-white">Padrón de Socios</h6>
                                    <p class="text-readable small mb-0">Control de categorías, antigüedades y estados de deuda en tiempo real.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <i class="bi bi-patch-check feature-icon text-success"></i>
                                    <h6 class="fw-bold fs-5 text-white">Certificaciones</h6>
                                    <p class="text-readable small mb-0">Emisión automática de certificados de cumplimiento y carnés digitales.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="sector-image-container">
                            <img src="{{ asset('images/h_assoc.png') }}" alt="Asociaciones" class="sector-image">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- OMNIDOC --}}
        <section class="sector-section" style="background: linear-gradient(180deg, #020617 0%, #0b1120 100%);">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill mb-4 fw-bold tracking-widest">SOLUCIÓN TRANSVERSAL</span>
                        <h2 class="sector-title">OmniDoc Vault</h2>
                        <p class="sector-desc">
                            Potencia cualquier vertical con nuestra Bóveda Documental de alta seguridad. Diseñado para el resguardo de información sensible.
                        </p>
                        <div class="narrative-card border-info border-opacity-25">
                            <h5 class="narrative-title">Seguridad Intergaláctica:</h5>
                            <p class="narrative-text">
                                Desde el resguardo de análisis clínicos para OmniHealth hasta contratos legales y DDJJ.
                            </p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <i class="bi bi-shield-lock feature-icon text-info"></i>
                                    <h6 class="fw-bold fs-5 text-white">Bóveda Encriptada</h6>
                                    <p class="text-readable small mb-0">Almacenamiento seguro de archivos pesados vía Bunny.net.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <i class="bi bi-cloud-upload feature-icon text-info"></i>
                                    <h6 class="fw-bold fs-5 text-white">Solicitud Digital</h6>
                                    <p class="text-readable small mb-0">Portal para que tus clientes suban su documentación de forma ágil.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center">
                        <img src="{{ asset('images/v_security.png') }}" alt="Seguridad" class="sector-image" style="max-width: 400px;">
                    </div>
                </div>
            </div>
        </section>

    </div>

    <!-- FOOTER CORPORATIVO -->
    <footer class="py-5" style="background: #000; border-top: 1px solid rgba(255,255,255,0.05);">
        <div class="container text-center">
            <img src="{{ asset('images/logo_omnipos.png') }}" alt="OmniPOS" style="height: 50px; margin-bottom: 20px;">
            <p class="text-readable mb-4">© 2026 OmniPOS Enterprise Suite. Todos los derechos reservados.</p>
            <div class="d-flex justify-content-center gap-4">
                <a href="#" class="text-white text-decoration-none opacity-50 hover-opacity-100">Privacidad</a>
                <a href="#" class="text-white text-decoration-none opacity-50 hover-opacity-100">Términos</a>
                <a href="#" class="text-white text-decoration-none opacity-50 hover-opacity-100">Soporte</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
