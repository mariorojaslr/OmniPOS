@extends('layouts.app')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
<style>
    :root {
        --oled-black: #000000;
        --oled-card: #0a0a0a;
        --oled-border: rgba(255, 255, 255, 0.1);
        --accent-blue: #3b82f6;
        --accent-purple: #a855f7;
        --accent-green: #22c55e;
        --accent-yellow: #eab308;
    }

    body {
        background-color: var(--oled-black) !important;
        font-family: 'Outfit', sans-serif;
    }

    .premium-bg {
        background: radial-gradient(circle at 10% 10%, rgba(59, 130, 246, 0.05), transparent 30%),
                    radial-gradient(circle at 90% 90%, rgba(168, 85, 247, 0.05), transparent 30%);
    }

    .header-title {
        font-weight: 800;
        font-size: 2.2rem;
        background: linear-gradient(to right, #fff, #94a3b8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -1px;
    }

    .oled-card {
        background: var(--oled-card);
        border: 1px solid var(--oled-border);
        border-radius: 20px;
        padding: 1.8rem;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        overflow: hidden;
    }

    .oled-card:hover {
        transform: translateY(-8px);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.8);
    }

    .oled-card::after {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; height: 2px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        opacity: 0;
        transition: opacity 0.4s;
    }

    .oled-card:hover::after { opacity: 1; }

    .stat-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #60a5fa; /* Celeste atenuado */
        font-weight: 700;
        margin-bottom: 0.5rem;
        padding-left: 2px;
    }

    .stat-value {
        font-size: 2.8rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-diff {
        font-size: 0.85rem;
        font-weight: 500;
    }

    .text-glow-primary { text-shadow: 0 0 20px rgba(59, 130, 246, 0.4); }
    .text-glow-success { text-shadow: 0 0 20px rgba(34, 197, 94, 0.4); }
    .text-glow-purple { text-shadow: 0 0 20px rgba(168, 85, 247, 0.4); }
    .text-glow-warning { text-shadow: 0 0 20px rgba(234, 179, 8, 0.4); }

    .infra-tag {
        font-size: 0.7rem;
        padding: 4px 10px;
        border-radius: 6px;
        background: rgba(255,255,255,0.05);
        color: #94a3b8;
        border: 1px solid rgba(255,255,255,0.05);
    }

    .command-btn {
        background: var(--oled-card);
        border: 1px solid var(--oled-border);
        color: #cbd5e1;
        border-radius: 14px;
        padding: 12px 20px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        text-align: left;
        margin-bottom: 12px;
        text-decoration: none;
    }

    .command-btn:hover {
        background: rgba(255,255,255,0.05);
        border-color: rgba(255,255,255,0.2);
        color: #fff;
        transform: translateX(5px);
    }

    .command-btn i {
        font-size: 1.2rem;
        opacity: 0.7;
    }

    .scanline {
        width: 100%;
        height: 1px;
        background: rgba(59, 130, 246, 0.1);
        margin: 2rem 0;
    }

    .stat-mini-label { font-size: 0.7rem; color: #ffffff; letter-spacing: 1px; opacity: 0.8; }

    /* Animaciones & Interactividad Premium */
    .clickable-card {
        display: block;
        text-decoration: none !important;
        color: inherit;
        cursor: pointer;
        outline: none;
    }
    .clickable-card:active {
        transform: scale(0.97) !important;
        border-color: #fff;
    }
    .radar-sweep {
        position: absolute;
        top: 0; left: 0;
        width: 150%; height: 100%;
        background: linear-gradient(110deg, transparent, rgba(168, 85, 247, 0.15), transparent);
        animation: radar 3s linear infinite;
        opacity: 0.8;
        pointer-events: none;
    }
    .radar-sweep-green {
        background: linear-gradient(110deg, transparent, rgba(34, 197, 94, 0.15), transparent);
    }
    .radar-sweep-blue {
        background: linear-gradient(110deg, transparent, rgba(59, 130, 246, 0.15), transparent);
    }
    @keyframes radar {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    @keyframes pulse-border {
        0% { border-color: rgba(59, 130, 246, 0.1); }
        50% { border-color: rgba(59, 130, 246, 0.6); }
        100% { border-color: rgba(59, 130, 246, 0.1); }
    }

    .live-indicator {
        width: 8px; height: 8px;
        background: #22c55e;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
        box-shadow: 0 0 10px #22c55e;
        animation: blink 2s infinite;
    }
    .live-indicator-fast {
        width: 8px; height: 8px;
        background: #ef4444;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
        box-shadow: 0 0 15px #ef4444;
        animation: blink 1s infinite;
    }
        width: 8px; height: 8px;
        background: #22c55e;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
        box-shadow: 0 0 10px #22c55e;
        animation: blink 2s infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }
</style>
@endsection

@section('content')
<div class="px-2 pb-5">

    {{-- HUD HEADER --}}
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <div class="stat-mini-label mb-1">
                <span class="live-indicator"></span> SISTEMA OPERATIVO MASTER · v1.02.0
            </div>
            <h1 class="header-title mb-0">CENTRO DE MANDO MultiPOS</h1>
        </div>
        <div class="text-end">
            <a href="{{ route('owner.empresas.create') }}" class="btn btn-primary px-4 py-2 fw-bold" style="border-radius: 12px; box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);">
                + DESPLEGAR EMPRESA
            </a>
        </div>
    </div>

    {{-- RACE CONTROL: VELOCÍMETROS DE SALUD --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="oled-card text-center p-3">
                <div id="chartSales" style="min-height: 200px;"></div>
                <div class="stat-label">VELOCIDAD DE VENTAS</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="oled-card text-center p-3">
                <div id="chartExpenses" style="min-height: 200px;"></div>
                <div class="stat-label">TASA DE GASTOS</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="oled-card text-center p-3">
                <div id="chartGlobal" style="min-height: 200px;"></div>
                <div class="stat-label">ESTADO GLOBAL DE LA MISIÓN</div>
            </div>
        </div>
    </div>

    {{-- METRICAS CORE (LEVEL 1) --}}
    <div class="row g-4 mb-4">
        <div class="col-md-2">
            <a href="{{ route('owner.empresas.index') }}" class="oled-card clickable-card">
                <div class="stat-label">Empresas</div>
                <div class="stat-value text-glow-primary" style="font-size: 2rem;">{{ $empresasCount }}</div>
                <div class="stat-mini-label">{{ $empresasActivas }} OK</div>
            </a>
        </div>

        <div class="col-md-2">
            <a href="{{ route('owner.empresas.index') }}" class="oled-card clickable-card">
                <div class="stat-label">Usuarios</div>
                <div class="stat-value text-glow-purple" style="font-size: 2rem;">{{ $usuariosCount }}</div>
                <div class="stat-mini-label">ACTIVOS</div>
            </a>
        </div>

        <div class="col-md-2">
            <div class="oled-card">
                <div class="stat-label">Artículos</div>
                <div class="stat-value text-glow-primary" style="font-size: 2rem;">{{ $articulosCount }}</div>
                <div class="stat-mini-label">SKU TOTAL</div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="oled-card">
                <div class="stat-label text-success">Clientes</div>
                <div class="stat-value text-glow-success" style="font-size: 2rem;">{{ $clientesCount }}</div>
                <div class="stat-mini-label">REGISTRADOS</div>
            </div>
        </div>

        <div class="col-md-2">
            <a href="{{ route('owner.facturacion.index') }}" class="oled-card clickable-card" style="border-color: rgba(34, 197, 94, 0.3);">
                <div class="stat-label text-success">MRR</div>
                <div class="stat-value text-glow-success" style="font-size: 1.8rem;">{{ $mrr }}</div>
                <div class="stat-mini-label">MENSUAL</div>
            </a>
        </div>

        <div class="col-md-2">
            <a href="{{ route('owner.facturacion.index') }}" class="oled-card clickable-card">
                <div class="stat-label text-warning">Ventas Mes</div>
                <div class="stat-value text-glow-warning" style="font-size: 1.8rem;">{{ $facturacionMes }}</div>
                <div class="stat-mini-label">RENOVACIONES</div>
            </a>
        </div>
    </div>

    {{-- RADAR DE ADQUISICIÓN Y TRÁFICO (LEVEL 1.5) --}}
    <div class="row g-4 mb-5">
        <div class="col-md-12 mb-1">
            <h5 class="stat-mini-label mb-0"><span class="live-indicator-fast"></span> RADAR DE ADQUISICIÓN EXTERNA (EN VIVO)</h5>
        </div>
        <div class="col-md-4">
            <a href="{{ route('owner.crm.index') }}" class="oled-card clickable-card" style="border-color: rgba(168, 85, 247, 0.4); box-shadow: 0 0 20px rgba(168, 85, 247, 0.1) inset;">
                <div class="radar-sweep"></div>
                <div class="stat-label" style="color: #c084fc;"><i class="bi bi-globe2 me-1"></i> Visitantes Landing Page</div>
                <div class="d-flex align-items-end gap-3 mb-1 mt-2">
                    <span class="fs-1 fw-bold text-white text-glow-purple lh-1">{{ $landingVisits }}</span>
                    <span class="text-success small fw-bold mb-1"><i class="bi bi-arrow-up-right"></i> +14%</span>
                </div>
                <div class="stat-mini-label">PERSONAS HOY</div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('owner.crm.index') }}" class="oled-card clickable-card" style="border-color: rgba(59, 130, 246, 0.4); box-shadow: 0 0 20px rgba(59, 130, 246, 0.1) inset;">
                <div class="radar-sweep radar-sweep-blue"></div>
                <div class="stat-label" style="color: #93c5fd;"><i class="bi bi-play-circle me-1"></i> Entradas al DEMO</div>
                <div class="d-flex align-items-end gap-3 mb-1 mt-2">
                    <span class="fs-1 fw-bold text-white text-glow-primary lh-1">{{ $demoEntries }}</span>
                    <span class="text-success small fw-bold mb-1"><i class="bi bi-arrow-up-right"></i> Activos</span>
                </div>
                <div class="stat-mini-label">PRUEBAS EN CURSO</div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('owner.crm.index') }}" class="oled-card clickable-card" style="border-color: rgba(34, 197, 94, 0.4); box-shadow: 0 0 20px rgba(34, 197, 94, 0.1) inset;">
                <div class="radar-sweep radar-sweep-green"></div>
                <div class="stat-label" style="color: #86efac;"><i class="bi bi-funnel me-1"></i> Conversión de Bot</div>
                <div class="d-flex align-items-end gap-3 mb-1 mt-2">
                    <span class="fs-1 fw-bold text-white text-glow-success lh-1">{{ $conversionRate }}%</span>
                    <span class="text-white-50 small fw-bold mb-1">Efectividad</span>
                </div>
                <div class="stat-mini-label">LEADS CALIENTES GENERADOS</div>
            </a>
        </div>
    </div>

    <div class="scanline">    {{-- SECTOR DE RECURSOS (NUEVO) --}}
    <div class="row g-4 mb-5">
        <div class="col-md-12">
            <h5 class="stat-mini-label mb-3"><span class="live-indicator"></span> MONITOR DE RECURSOS GLOBALES</h5>
            <div class="oled-card p-4" style="background: linear-gradient(135deg, #0a0a0a 0%, #111 100%); border-color: rgba(59, 130, 246, 0.2);">
                <div class="row g-4 text-center">
                    {{-- Tarjeta 1: Base de Datos --}}
                    <div class="col-md">
                        <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                            <div class="stat-label text-info"><i class="bi bi-database me-2"></i>Base de Datos</div>
                            <div class="fs-2 fw-bold text-white">{{ $dbSize }}</div>
                            <div class="stat-mini-label mt-1">PESO EN DISCO</div>
                        </div>
                    </div>
                    {{-- Tarjeta 2: Multimedia --}}
                    <div class="col-md">
                        <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                            <div class="stat-label text-purple"><i class="bi bi-images me-2"></i>Contenido</div>
                            <div class="fs-2 fw-bold text-white">{{ $archivosSubidos }}</div>
                            <div class="stat-mini-label mt-1">FOTOS / VIDEOS</div>
                        </div>
                    </div>
                    {{-- Tarjeta 3: Almacenamiento Bunny --}}
                    <div class="col-md">
                        <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                            <div class="stat-label text-warning"><i class="bi bi-hdd-network me-2"></i>Nube (Bunny)</div>
                            <div class="fs-2 fw-bold text-white">{{ $consumoStorage }}</div>
                            <div class="stat-mini-label mt-1">CAPACIDAD USADA</div>
                        </div>
                    </div>
                    {{-- Tarjeta 4: Proyección de Costo --}}
                    <div class="col-md">
                        <div class="p-3 rounded-4" style="background: rgba(234, 179, 8, 0.05); border: 1px solid rgba(234, 179, 8, 0.2);">
                            <div class="stat-label text-warning fw-bold"><i class="bi bi-calculator me-2"></i>Costo Proyectado</div>
                            <div class="fs-2 fw-bold text-warning text-glow-warning">{{ $costoProyectado }}</div>
                            <div class="stat-mini-label mt-1">ESTIMADO FIN DE MES</div>
                        </div>
                    </div>
                </div>

                {{-- Barra de progreso global de recursos --}}
                <div class="mt-4 pt-2">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="stat-mini-label">ESTADO DE CAPACIDAD DEL SERVIDOR</span>
                        <span class="stat-mini-label text-info">99.9% UPTIME ACTUANDO</span>
                    </div>
                    <div class="progress" style="height: 6px; background: rgba(255,255,255,0.05); border-radius: 10px;">
                        <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" style="width: 15%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>    </div>

        <div class="col-md-4">
            <h5 class="stat-mini-label mb-4">PANEL DE OPERACIONES</h5>
            
            <a href="{{ route('owner.crm.index') }}" class="command-btn border-primary border-opacity-50" style="background: linear-gradient(90deg, rgba(59, 130, 246, 0.15), transparent);">
                <i class="bi bi-people-fill text-primary"></i>
                <div>
                    <div class="text-white fw-bold">CRM DE PROSPECTOS</div>
                    <small class="text-primary opacity-75" style="font-size: 0.7rem;">Nuevos Leads & Pagos pendientes</small>
                </div>
            </a>

            <a href="{{ route('owner.empresas.index') }}" class="command-btn">
                <i class="bi bi-building"></i>
                <div>
                    <div>GESTIÓN DE EMPRESAS</div>
                    <small class="text-muted" style="font-size: 0.7rem;">Control de suscripciones</small>
                </div>
            </a>

            <a href="{{ route('owner.facturacion.index') }}" class="command-btn">
                <i class="bi bi-wallet2"></i>
                <div>
                    <div>CENTRO FINANCIERO</div>
                    <small class="text-muted" style="font-size: 0.7rem;">Cobranzas globales</small>
                </div>
            </a>

            <a href="{{ route('owner.soporte.index') }}" class="command-btn">
                <i class="bi bi-headset"></i>
                <div>
                    <div>CENTRAL DE SOPORTE</div>
                    <small class="text-muted" style="font-size: 0.7rem;">Gestión de tickets</small>
                </div>
            </a>

            <a href="{{ route('owner.planes.index') }}" class="command-btn border-primary border-opacity-25" style="background: rgba(59, 130, 246, 0.05);">
                <i class="bi bi-gear-wide-connected text-primary"></i>
                <div>
                    <div>PLANES Saas</div>
                    <small class="text-muted" style="font-size: 0.7rem;">Precios y servicios</small>
                </div>
            </a>

            <a href="{{ route('owner.updates.index') }}" class="command-btn">
                <i class="bi bi-broadcast-pin"></i>
                <div>
                    <div>COMUNICADOS</div>
                    <small class="text-muted" style="font-size: 0.7rem;">Logs de actualización</small>
                </div>
            </a>

            {{-- AJUSTES GLOBALES (NUEVO) --}}
            <button type="button" class="command-btn border-warning border-opacity-50" data-bs-toggle="modal" data-bs-target="#modalSettings" style="background: linear-gradient(90deg, rgba(234, 179, 8, 0.1), transparent); cursor: pointer;">
                <i class="bi bi-sliders text-warning"></i>
                <div>
                    <div class="text-white fw-bold">AJUSTES GLOBALES</div>
                    <small class="text-warning opacity-75" style="font-size: 0.7rem;">Configuración maestra del SaaS</small>
                </div>
            </button>
        </div>

    </div>

    <div class="scanline"></div>

    {{-- RECENT COMPANIES (LEVEL 3) --}}
    <div class="row g-4 mb-5">
        <div class="col-md-12">
            <h5 class="stat-mini-label mb-4">ÚLTIMAS EMPRESAS DESPLEGADAS</h5>
            <div class="oled-card p-0 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-dark mb-0 align-middle" style="--bs-table-bg: transparent; --bs-table-border-color: rgba(255,255,255,0.05);">
                        <thead class="stat-mini-label border-bottom">
                            <tr>
                                <th class="ps-4 py-3">EMPRESA / RAZÓN SOCIAL</th>
                                <th>PLAN</th>
                                <th class="text-center">FECHA ALTA</th>
                                <th class="text-center">ESTADO</th>
                                <th class="text-end pe-4">COMANDOS</th>
                            </tr>
                        </thead>
                        <tbody class="small fw-normal" style="color: #94a3b8;">
                            @foreach($ultimasEmpresas as $emp)
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                                <td class="ps-4">
                                    <div class="fw-bold text-white fs-6">{{ $emp->nombre_comercial }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $emp->cuit }}</div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-dark text-primary border border-primary border-opacity-25 px-3">
                                        {{ $emp->plan->nombre ?? 'BÁSICO' }}
                                    </span>
                                </td>
                                <td class="text-center">{{ $emp->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    @if($emp->activo)
                                        <span class="text-success small fw-bold"><i class="bi bi-circle-fill me-1" style="font-size: 7px;"></i> ONLINE</span>
                                    @else
                                        <span class="text-danger small fw-bold"><i class="bi bi-circle-fill me-1" style="font-size: 7px;"></i> OFFLINE</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ url('owner/empresas/' . $emp->id . '/edit') }}" class="btn btn-sm btn-outline-secondary border-0 opacity-75">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        @php $admin = $emp->users()->where('role', 'empresa')->first() ?? $emp->users->first(); @endphp
                                        @if($admin)
                                            <a href="{{ url('owner/mimetizar/empresa/' . $emp->id . '/usuario/' . $admin->id) }}" class="btn btn-sm btn-outline-primary border-0" title="Entrar como Admin">
                                                <i class="bi bi-person-fill-gear"></i> ENTRAR
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3 text-end">
                <a href="{{ route('owner.empresas.index') }}" class="text-primary text-decoration-none small fw-bold" style="letter-spacing: 1px;">
                    VER TODAS LAS EMPRESAS <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

</div>

{{-- ==========================================
     MODAL: AJUSTES GLOBALES
=========================================== --}}
<div class="modal fade" id="modalSettings" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content oled-card border-warning border-opacity-50" style="background: #000;">
            <div class="modal-header border-bottom border-white border-opacity-10 py-3">
                <h5 class="modal-title text-white fw-bold"><i class="bi bi-sliders me-2 text-warning"></i>Ajustes del Sistema</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('owner.settings.update') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="stat-label d-block mb-2">Video Tutorial AFIP (YouTube ID)</label>
                        <input type="text" name="afip_tutorial_video" value="{{ $settings['afip_tutorial_video'] ?? 'v6r4D3Ljuy8' }}" class="form-control bg-dark border-secondary text-white py-2" placeholder="Ej: v6r4D3Ljuy8" style="background: rgba(255,255,255,0.05) !important;">
                        <small class="text-muted d-block mt-2">Este video se mostrará a todas las empresas en el asistente de migración fiscal.</small>
                    </div>
                </div>
                <div class="modal-footer border-top border-white border-opacity-10 py-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">CANCELAR</button>
                    <button type="submit" class="btn btn-warning btn-sm fw-bold px-4">GUARDAR CAMBIOS</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Configuración Base para Velocímetros OLED
    const commonOptions = {
        chart: { type: 'radialBar', height: 250, sparkline: { enabled: true } },
        plotOptions: {
            radialBar: {
                startAngle: -90, endAngle: 90,
                track: { background: "#111", strokeWidth: '97%', margin: 5 },
                dataLabels: {
                    name: { show: false },
                    value: { offsetY: -2, fontSize: '30px', fontWeight: '800', color: '#fff', formatter: (val) => val + '%' }
                }
            }
        },
        grid: { padding: { top: -10 } },
        stroke: { lineCap: "round" }
    };

    // 1. SALES VELOCITY (AZUL NEON)
    new ApexCharts(document.querySelector("#chartSales"), {
        ...commonOptions,
        series: [{{ $saludVentas }}],
        colors: ["#3b82f6"],
        fill: { type: 'gradient', gradient: { shade: 'dark', type: 'horizontal', gradientToColors: ['#60a5fa'], stops: [0, 100] } }
    }).render();

    // 2. BURN RATE (ROJO/AMARILLO)
    new ApexCharts(document.querySelector("#chartExpenses"), {
        ...commonOptions,
        series: [{{ $saludGastos }}],
        colors: ["#ef4444"],
        fill: { type: 'gradient', gradient: { shade: 'dark', type: 'horizontal', gradientToColors: ['#f59e0b'], stops: [0, 100] } }
    }).render();

    // 3. GLOBAL STATUS (VERDE NEON)
    new ApexCharts(document.querySelector("#chartGlobal"), {
        ...commonOptions,
        series: [{{ $saludGlobal }}],
        colors: ["#22c55e"],
        fill: { type: 'gradient', gradient: { shade: 'dark', type: 'horizontal', gradientToColors: ['#4ade80'], stops: [0, 100] } }
    }).render();
</script>
@endsection
