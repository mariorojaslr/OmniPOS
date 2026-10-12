<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Turnos y Actividades | {{ $empresa->nombre_comercial }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @php
        $primary = $config?->color_primario ?? '#3b82f6';
        $secondary = $config?->color_secundario ?? '#10b981';
        $logo = $config ? $config->logo_url : asset('images/logo_omnipos.png');
        $esOdonto = $empresa->isOdontologica();
    @endphp
    <style>
        :root {
            --primary: {{ $primary }};
            --secondary: {{ $secondary }};
        }
        body {
            background-color: #f8fafc;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color: #1e293b;
            min-height: 100vh;
        }
        .header-bar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .portal-nav-pills .nav-link {
            font-weight: 700;
            color: #64748b;
            border-radius: 9999px;
            padding: 8px 20px;
            font-size: 0.85rem;
        }
        .portal-nav-pills .nav-link.active {
            background: var(--primary);
            color: #ffffff;
        }
        .card-custom {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s;
        }
        .badge-status {
            font-size: 0.75rem;
            font-weight: 800;
            padding: 6px 14px;
            border-radius: 9999px;
            text-transform: uppercase;
        }
        .btn-wsp {
            background: #25d366;
            color: #ffffff;
            font-weight: 700;
            border-radius: 9999px;
            transition: all 0.2s;
        }
        .btn-wsp:hover {
            background: #20bd5a;
            color: #ffffff;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <!-- CABECERA -->
    <header class="header-bar py-3">
        <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $logo }}" alt="Logo" style="height: 42px; border-radius: 8px;">
                <div>
                    <h6 class="fw-bold mb-0 text-dark">{{ $empresa->nombre_comercial }}</h6>
                    <small class="text-muted">Portal de Paciente: <strong class="text-dark">{{ $client->name }}</strong> (DNI: {{ $client->document ?? 'S/D' }})</small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- SWITCH ENTRE PORTAL COMERCIAL Y PORTAL DE SALUD -->
                <a href="{{ route('client.portal', $token) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold">
                    <i class="bi bi-receipt me-1"></i> Ver Portal Comercial / Facturación
                </a>
                @if($whatsappUrl)
                    <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-sm btn-wsp px-3">
                        <i class="bi bi-whatsapp me-1"></i> WhatsApp Clínica
                    </a>
                @endif
            </div>
        </div>
    </header>

    <div class="container py-4">
        <!-- RESUMEN EN TARJETAS -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card-custom p-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="x-small fw-bold text-uppercase text-muted">Próximo Turno</span>
                        <span class="fs-4">📅</span>
                    </div>
                    @if($proximosTurnos->count() > 0)
                        @php $first = $proximosTurnos->first(); @endphp
                        <h4 class="fw-bold text-primary mb-1">{{ \Carbon\Carbon::parse($first->fecha)->format('d/m/Y') }}</h4>
                        <p class="small text-muted mb-0">
                            <strong>{{ substr($first->hora_inicio, 0, 5) }} hs</strong> • {{ $first->servicio?->nombre ?? 'Consulta' }} ({{ $first->user?->name ?? 'Profesional asignado' }})
                        </p>
                    @else
                        <h5 class="fw-bold text-secondary mb-1">Sin turnos pendientes</h5>
                        <p class="small text-muted mb-0">No tienes citas agendadas para los próximos días.</p>
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-custom p-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="x-small fw-bold text-uppercase text-muted">Obligaciones / Estado de Cuenta</span>
                        <span class="fs-4">💳</span>
                    </div>
                    <h4 class="fw-bold mb-1 {{ $saldoPendiente > 0 ? 'text-danger' : 'text-success' }}">
                        ${{ number_format(abs($saldoPendiente), 2, ',', '.') }}
                    </h4>
                    <p class="small text-muted mb-0">
                        @if($saldoPendiente > 0)
                            Saldo pendiente por consultas o tratamientos.
                        @else
                            Al día • No registras deudas pendientes.
                        @endif
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-custom p-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="x-small fw-bold text-uppercase text-muted">Cobertura / Afiliación</span>
                        <span class="fs-4">🛡️</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">
                        {{ $client->obraSocial?->nombre ?? ($client->is_affiliate ? 'Afiliado Plan Med Plus' : 'Particular') }}
                    </h5>
                    <p class="small text-muted mb-0">
                        {{ $client->affiliate_number ? 'Credencial: ' . $client->affiliate_number : 'Sin número de credencial' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- PESTAÑAS PRINCIPALES DEL PORTAL -->
        <ul class="nav portal-nav-pills gap-2 mb-4" id="portalTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#turnos-tab">
                    <i class="bi bi-calendar-check-fill me-1"></i> Mis Turnos & Citas
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#obligaciones-tab">
                    <i class="bi bi-wallet2 me-1"></i> Obligaciones & Pagos Pendientes
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#notificaciones-tab">
                    <i class="bi bi-bell-fill me-1"></i> Notificaciones & Recordatorios
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- TAB 1: TURNOS -->
            <div class="tab-pane fade show active" id="turnos-tab">
                <!-- PRÓXIMAS CITAS -->
                <div class="card-custom p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-calendar-event text-primary me-2"></i>Próximas Citas Médicas / Odontológicas
                        </h5>
                        <a href="{{ route('catalog.booking', $empresa->slug ?? $empresa->id) }}" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">
                            <i class="bi bi-plus-lg me-1"></i> Solicitar Nuevo Turno Online
                        </a>
                    </div>

                    @if($proximosTurnos->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr class="x-small text-muted fw-bold text-uppercase border-bottom">
                                        <th>Fecha & Hora</th>
                                        <th>Servicio / Tratamiento</th>
                                        <th>Profesional</th>
                                        <th>Estado</th>
                                        <th class="text-end">Seguimiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($proximosTurnos as $t)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($t->fecha)->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ substr($t->hora_inicio, 0, 5) }} a {{ substr($t->hora_fin, 0, 5) }} hs</small>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">{{ $t->servicio?->nombre ?? 'Consulta General' }}</span>
                                            </td>
                                            <td>
                                                <span class="text-secondary">{{ $t->user?->name ?? 'A confirmar' }}</span>
                                                @if($t->user?->consultorio)
                                                    <span class="badge bg-light text-dark border ms-1">{{ $t->user->consultorio }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $st = $t->estado_cola ?? 'pendiente';
                                                    $badges = [
                                                        'pendiente' => 'bg-primary bg-opacity-10 text-primary',
                                                        'sala_espera' => 'bg-success bg-opacity-10 text-success',
                                                        'llamado' => 'bg-warning bg-opacity-10 text-warning',
                                                        'en_consulta' => 'bg-info bg-opacity-10 text-info',
                                                        'finalizado' => 'bg-secondary bg-opacity-10 text-secondary'
                                                    ];
                                                @endphp
                                                <span class="badge badge-status {{ $badges[$st] ?? 'bg-light text-dark' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $st)) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('turno.seguimiento', $t->id) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                                                    <i class="bi bi-broadcast me-1"></i> Seguir en Vivo
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                            No tienes citas futuras registradas.
                        </div>
                    @endif
                </div>

                <!-- HISTORIAL DE CITAS PASADAS -->
                <div class="card-custom p-4">
                    <h6 class="fw-bold mb-3 text-muted">
                        <i class="bi bi-clock-history me-2"></i>Historial Reciente de Turnos Anteriores
                    </h6>
                    @if($historialTurnos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm align-middle text-muted">
                                <thead>
                                    <tr class="x-small fw-bold text-uppercase border-bottom">
                                        <th>Fecha</th>
                                        <th>Servicio</th>
                                        <th>Profesional</th>
                                        <th>Estado Final</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($historialTurnos as $ht)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($ht->fecha)->format('d/m/Y') }} {{ substr($ht->hora_inicio, 0, 5) }} hs</td>
                                            <td>{{ $ht->servicio?->nombre ?? 'Consulta' }}</td>
                                            <td>{{ $ht->user?->name ?? '-' }}</td>
                                            <td><span class="badge bg-light text-secondary border">Completado</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="small text-muted mb-0">Sin historial de citas pasadas.</p>
                    @endif
                </div>
            </div>

            <!-- TAB 2: OBLIGACIONES Y PAGOS -->
            <div class="tab-pane fade" id="obligaciones-tab">
                <div class="card-custom p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">
                                <i class="bi bi-receipt-cutoff text-danger me-2"></i>Comprobantes & Obligaciones Pendientes
                            </h5>
                            <small class="text-muted">Detalle de aranceles, copagos o cuotas del plan que requieren abono.</small>
                        </div>
                        <a href="{{ route('client.portal', $token) }}" class="btn btn-sm btn-dark rounded-pill px-3 fw-bold">
                            <i class="bi bi-cash-stack me-1"></i> Ver Cuenta Corriente Completa
                        </a>
                    </div>

                    @if($comprobantesPendientes->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr class="x-small text-muted fw-bold text-uppercase border-bottom">
                                        <th>Fecha</th>
                                        <th>Concepto</th>
                                        <th class="text-end">Importe Total</th>
                                        <th class="text-end">Saldo Adeudado</th>
                                        <th class="text-end">Pagar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($comprobantesPendientes as $cp)
                                        <tr>
                                            <td>{{ $cp->created_at->format('d/m/Y') }}</td>
                                            <td><strong>{{ $cp->description ?? 'Prestación médica / odontológica' }}</strong></td>
                                            <td class="text-end">${{ number_format($cp->amount, 2, ',', '.') }}</td>
                                            <td class="text-end text-danger fw-bold">${{ number_format($cp->saldo_pendiente, 2, ',', '.') }}</td>
                                            <td class="text-end">
                                                @if($cp->venta_id)
                                                    <a href="{{ route('client.portal.pay_simulator', ['token' => $token, 'id' => $cp->venta_id]) }}" class="btn btn-sm btn-success rounded-pill px-3 fw-bold">
                                                        <i class="bi bi-credit-card me-1"></i> Pagar Online
                                                    </a>
                                                @else
                                                    <span class="badge bg-light text-muted border">Coordinar con recepción</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-success">
                            <i class="bi bi-check-circle fs-2 d-block mb-2"></i>
                            <h6 class="fw-bold">¡Estás al día!</h6>
                            <p class="small text-muted mb-0">No se registran aranceles o copagos pendientes en tu cuenta.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- TAB 3: NOTIFICACIONES Y RECORDATORIOS -->
            <div class="tab-pane fade" id="notificaciones-tab">
                <div class="card-custom p-4">
                    <h5 class="fw-bold mb-3 text-dark">
                        <i class="bi bi-chat-dots-fill text-info me-2"></i>Canales de Notificación y Recordatorios
                    </h5>
                    <p class="text-secondary small mb-4">
                        Para que no olvides tus citas ni tus obligaciones, el sistema ofrece múltiples canales de comunicación directa:
                    </p>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-4 border bg-light h-100">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-whatsapp fs-4 text-success"></i>
                                    <h6 class="fw-bold mb-0">Recordatorios vía WhatsApp</h6>
                                </div>
                                <p class="small text-muted mb-3">
                                    Recibirás un mensaje automático 24 hs antes de tu turno con enlace directo de confirmación y cancelación.
                                </p>
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-1 rounded-pill">
                                    <i class="bi bi-check-lg me-1"></i> Activo para el número {{ $client->phone ?? 'No registrado' }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-4 border bg-light h-100">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-envelope-check fs-4 text-primary"></i>
                                    <h6 class="fw-bold mb-0">Comprobantes y HCE por Email</h6>
                                </div>
                                <p class="small text-muted mb-3">
                                    Los recibos de pago, certificados y recetas quedan respaldados y pueden ser enviados a tu correo electrónico.
                                </p>
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-1 rounded-pill">
                                    <i class="bi bi-envelope me-1"></i> {{ $client->email ?? 'Sin email cargado' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PIE DE PÁGINA -->
    <footer class="py-4 text-center text-muted small border-top bg-white mt-5">
        <div class="container">
            <p class="mb-0">© {{ date('Y') }} {{ $empresa->nombre_comercial }} • Plataforma de Gestión Médica & Odontológica</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
