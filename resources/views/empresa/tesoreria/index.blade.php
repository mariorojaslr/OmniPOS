@extends('layouts.empresa')

@section('page_title', 'Consolidado de Tesorería')

@section('content')
<div class="container-fluid py-4">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Tesorería & Finanzas</h2>
            <p class="text-muted small mb-0">Gestión consolidada de todas las fuentes de dinero.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaCuenta">
                <i class="bi bi-plus-circle me-1"></i> Nueva Cuenta / Caja
            </button>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════
        DASHBOARD DE LIQUIDEZ (KPIs SUPERIORES)
    ════════════════════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">
        <!-- TOTAL GENERAL (LIQUIDEZ TOTAL) -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-dark text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-uppercase x-small fw-bold opacity-75">Liquidez Total</span>
                        <div class="bg-primary p-2 rounded-3"><i class="bi bi-wallet2 fs-5"></i></div>
                    </div>
                    <h2 class="fw-bold mb-0">${{ number_format($metricas['total_general'], 2, ',', '.') }}</h2>
                    <p class="x-small mb-0 opacity-50 mt-1 ls-1">CAPITAL DISPONIBLE</p>
                </div>
            </div>
        </div>

        <!-- TOTAL BANCOS -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white border-bottom border-info border-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-uppercase x-small fw-bold text-muted">Bancos</span>
                        <i class="bi bi-bank text-info fs-4"></i>
                    </div>
                    <h2 class="fw-bold mb-0 text-dark">${{ number_format($metricas['total_bancos'], 2, ',', '.') }}</h2>
                    <div class="x-small text-muted mt-1 fw-bold">{{ $cuentas->where('tipo', 'banco')->count() }} entidades</div>
                </div>
            </div>
        </div>

        <!-- TOTAL BILLETERAS -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white border-bottom border-primary border-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-uppercase x-small fw-bold text-muted">Billeteras Virtuales</span>
                        <i class="bi bi-phone text-primary fs-4"></i>
                    </div>
                    <h2 class="fw-bold mb-0 text-dark">${{ number_format($metricas['total_billeteras'], 2, ',', '.') }}</h2>
                    <div class="x-small text-muted mt-1 fw-bold">Mercado Pago & Otros</div>
                </div>
            </div>
        </div>

        <!-- TOTAL EFECTIVO -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white border-bottom border-success border-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-uppercase x-small fw-bold text-muted">Efectivo</span>
                        <i class="bi bi-cash-stack text-success fs-4"></i>
                    </div>
                    <h2 class="fw-bold mb-0 text-dark">${{ number_format($metricas['total_efectivo'], 2, ',', '.') }}</h2>
                    <div class="x-small text-muted mt-1 fw-bold">Cajas en sucursales</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- COLUMNA IZQUIERDA: GESTIÓN DE CUENTAS --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-0 text-dark">Mis Cuentas & Fuentes</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @php
                            $tipos = [
                                'banco' => ['titulo' => 'Bancos', 'icon' => 'bank', 'color' => 'info'],
                                'billetera_digital' => ['titulo' => 'Billeteras Virtuales', 'icon' => 'phone', 'color' => 'primary'],
                                'caja' => ['titulo' => 'Cajas & Efectivo', 'icon' => 'cash-stack', 'color' => 'success']
                            ];
                        @endphp

                        @foreach($tipos as $key => $info)
                            @php $cuentasSegmento = $cuentas->where('tipo', $key); @endphp
                            @if($cuentasSegmento->count() > 0)
                                <div class="bg-light px-4 py-2 border-top border-bottom x-small fw-bold text-muted text-uppercase ls-1">
                                    <i class="bi bi-{{ $info['icon'] }} me-1"></i> {{ $info['titulo'] }}
                                </div>
                                @foreach($cuentasSegmento as $c)
                                <div class="list-group-item p-4 border-0 border-bottom hover-bg transition-all">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex gap-3 align-items-center">
                                            <div class="bg-{{ $info['color'] }} bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center text-{{ $info['color'] }}" style="width: 45px; height: 45px;">
                                                <i class="bi bi-{{ $info['icon'] }} fs-5"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">{{ $c->nombre }}</h6>
                                                <div class="x-small text-muted">{{ $c->numero_cuenta ?? 'Sin datos' }}</div>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-3 align-items-center">
                                            <div class="text-end">
                                                <h5 class="fw-bold mb-0 text-dark">${{ number_format($c->saldo_actual, 2, ',', '.') }}</h5>
                                                <span class="badge bg-light text-muted rounded-pill x-small border px-2 fw-medium">{{ $c->movimientos_count }} movs.</span>
                                            </div>
                                            <button class="btn btn-sm btn-outline-secondary border-0 p-1" 
                                                    onclick="abrirModalEditar('{{ $c->id }}', '{{ $c->nombre }}', '{{ $c->numero_cuenta }}', '{{ $c->cbu_cvu }}')"
                                                    title="Editar Alias/Datos">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        @endforeach
                        
                        @if($cuentas->count() == 0)
                            <div class="p-5 text-center text-muted small">No hay fuentes configuradas.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: SÁBANA DE CONTROL (MOVIMIENTOS GLOBALES) --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Sábana de Control</h5>
                        <p class="text-muted x-small mb-0">Auditoría global de fondos liquidados.</p>
                    </div>
                    <a href="{{ route('empresa.reportes.caja_diaria') }}" class="btn btn-sm btn-light border rounded-pill px-3">
                        <i class="bi bi-collection me-1"></i> Auditoría Diaria
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="x-small fw-bold text-muted text-uppercase ls-1">
                                    <th class="ps-4 py-3">Fecha / Hora</th>
                                    <th>Fuente</th>
                                    <th>Concepto</th>
                                    <th class="text-end pe-4">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($movimientos as $m)
                                <tr>
                                    <td class="ps-4">
                                        <div class="small fw-bold text-dark">{{ $m->fecha->format('d/m/Y') }}</div>
                                        <div class="x-small text-muted fw-medium">{{ $m->fecha->format('H:i') }} hs</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border rounded-pill x-small px-3 py-1 fw-bold">{{ $m->cuenta->nombre }}</span>
                                    </td>
                                    <td>
                                        <div class="small text-dark fw-medium">{{ $m->concepto }}</div>
                                        @if($m->tipo == 'ingreso')
                                            <span class="x-small text-success fw-bold text-uppercase">Entrada <i class="bi bi-arrow-up-right"></i></span>
                                        @else
                                            <span class="x-small text-danger fw-bold text-uppercase">Salida <i class="bi bi-arrow-down-left"></i></span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <h6 class="fw-bold mb-0 {{ $m->tipo == 'ingreso' ? 'text-success' : 'text-danger' }}">
                                            {{ $m->tipo == 'ingreso' ? '+' : '-' }} ${{ number_format($m->monto, 2, ',', '.') }}
                                        </h6>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted small">No se registran movimientos recientes.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-4 text-center">
                    <a href="{{ route('empresa.reportes.panel') }}" class="btn btn-outline-primary btn-sm fw-bold rounded-pill px-4">
                        <i class="bi bi-file-earmark-bar-graph me-1"></i> VER DASHBOARD COMPLETO
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL NUEVA CUENTA --}}
<div class="modal fade" id="modalNuevaCuenta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white p-4 border-0">
                <h5 class="modal-title fw-bold">Configurar Nueva Fuente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('empresa.tesoreria.cuentas.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-light">
                    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                        <div class="mb-3">
                            <label class="small text-muted fw-bold text-uppercase mb-1 ls-1">Nombre Descriptivo</label>
                            <input type="text" name="nombre" class="form-control border-0 bg-light rounded-pill px-3 fw-bold" placeholder="Ej: Santander Río, Mercado Pago" required>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted fw-bold text-uppercase mb-1 ls-1">Tipo de Fuente</label>
                            <select name="tipo" class="form-select border-0 bg-light rounded-pill px-3 fw-bold" required>
                                <option value="caja">💵 Caja / Efectivo</option>
                                <option value="banco">🏦 Banco</option>
                                <option value="billetera_digital">📱 Billetera Digital</option>
                                <option value="tarjeta_credito">💳 Tarjeta de Crédito</option>
                            </select>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-3">
                        <div class="mb-3">
                            <label class="small text-muted fw-bold text-uppercase mb-1 ls-1">CBU / CVU / Alias</label>
                            <input type="text" name="cbu_cvu" class="form-control border-0 bg-light rounded-pill px-3 font-monospace" placeholder="Opcional">
                        </div>
                        <div class="mb-0">
                            <label class="small text-muted fw-bold text-uppercase mb-1 ls-1">Saldo Inicial</label>
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light rounded-start-pill ps-3 fw-bold">$</span>
                                <input type="number" name="saldo_inicial" step="0.01" class="form-control border-0 bg-light rounded-end-pill pe-3 fw-bold fs-4 text-primary" value="0.00" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm scale-up">
                        CREAR FUENTE DE FONDOS <i class="bi bi-check-circle ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDITAR CUENTA --}}
<div class="modal fade" id="modalEditarCuenta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white p-4 border-0">
                <h5 class="modal-title fw-bold">Editar Fuente / Alias</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditarCuenta" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 bg-light">
                    <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                        <div class="mb-3">
                            <label class="small text-muted fw-bold text-uppercase mb-1 ls-1">Alias / Nombre</label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control border-0 bg-light rounded-pill px-3 fw-bold" required>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted fw-bold text-uppercase mb-1 ls-1">Número de Cuenta</label>
                            <input type="text" name="numero_cuenta" id="edit_numero" class="form-control border-0 bg-light rounded-pill px-3 font-monospace">
                        </div>
                        <div class="mb-0">
                            <label class="small text-muted fw-bold text-uppercase mb-1 ls-1">CBU / CVU</label>
                            <input type="text" name="cbu_cvu" id="edit_cbu" class="form-control border-0 bg-light rounded-pill px-3 font-monospace">
                        </div>
                    </div>
                    <p class="small text-muted text-center px-3 mt-2">
                        <i class="bi bi-info-circle me-1"></i> El saldo no puede editarse manualmente por seguridad; debe registrar un movimiento de ajuste.
                    </p>
                </div>
                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm scale-up">
                        GUARDAR CAMBIOS <i class="bi bi-floppy ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function abrirModalEditar(id, nombre, numero, cbu) {
        let form = document.getElementById('formEditarCuenta');
        form.action = `/empresa/tesoreria/cuentas/${id}`;
        
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_numero').value = numero;
        document.getElementById('edit_cbu').value = cbu;

        let modal = new bootstrap.Modal(document.getElementById('modalEditarCuenta'));
        modal.show();
    }
</script>
@endpush

<style>
    .x-small { font-size: 0.65rem; }
    .ls-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
    .hover-bg:hover { background: #f8f9ff !important; }
    .scale-up { transition: transform 0.2s; }
    .scale-up:hover { transform: scale(1.02); }
</style>
@endsection
