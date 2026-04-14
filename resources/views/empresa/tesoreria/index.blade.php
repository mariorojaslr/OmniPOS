@extends('layouts.empresa')

@section('page_title', 'Consolidado de Tesorería')

@section('content')
<div class="container-fluid">

    {{-- ════════════════════════════════════════════════════════
        DASHBOARD DE LIQUIDEZ (KPIs SUPERIORES)
    ════════════════════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">
        <!-- TOTAL GENERAL (LIQUIDEZ TOTAL) -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-primary text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-uppercase x-small fw-bold opacity-75">Liquidez Total</span>
                        <i class="bi bi-wallet2 fs-4"></i>
                    </div>
                    <h2 class="fw-bold mb-0">${{ number_format($metricas['total_general'], 2, ',', '.') }}</h2>
                    <p class="x-small mb-0 opacity-75 mt-1">Suma de todas las fuentes</p>
                </div>
            </div>
        </div>

        <!-- TOTAL BANCOS -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-uppercase x-small fw-bold text-muted">Total Bancos</span>
                        <i class="bi bi-bank text-info fs-4"></i>
                    </div>
                    <h2 class="fw-bold mb-0 text-dark">${{ number_format($metricas['total_bancos'], 2, ',', '.') }}</h2>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-info" style="width: {{ $metricas['total_general'] > 0 ? ($metricas['total_bancos'] / $metricas['total_general'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOTAL BILLETERAS -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-uppercase x-small fw-bold text-muted">Billeteras Virtuales</span>
                        <i class="bi bi-phone text-primary fs-4"></i>
                    </div>
                    <h2 class="fw-bold mb-0 text-dark">${{ number_format($metricas['total_billeteras'], 2, ',', '.') }}</h2>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-primary" style="width: {{ $metricas['total_general'] > 0 ? ($metricas['total_billeteras'] / $metricas['total_general'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOTAL EFECTIVO -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white border-start border-4 border-success">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-uppercase x-small fw-bold text-muted">Efectivo (Cajas)</span>
                        <i class="bi bi-cash-stack text-success fs-4"></i>
                    </div>
                    <h2 class="fw-bold mb-0 text-dark">${{ number_format($metricas['total_efectivo'], 2, ',', '.') }}</h2>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: {{ $metricas['total_general'] > 0 ? ($metricas['total_efectivo'] / $metricas['total_general'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- COLUMNA IZQUIERDA: GESTIÓN DE CUENTAS --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-transparent border-0 p-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">Mis Cuentas y Cajas</h5>
                    <button class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalNuevaCuenta">
                        <i class="bi bi-plus-circle me-1"></i> Nueva
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($cuentas as $c)
                        <div class="list-group-item p-4 border-0 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex gap-3">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 45px; height: 45px;">
                                        @if($c->tipo == 'banco') <i class="bi bi-bank fs-5"></i> 
                                        @elseif($c->tipo == 'billetera_digital') <i class="bi bi-phone fs-5"></i> 
                                        @else <i class="bi bi-cash-stack fs-5"></i> @endif
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">{{ $c->nombre }}</h6>
                                        <span class="x-small text-muted text-uppercase fw-bold">{{ str_replace('_', ' ', $c->tipo) }}</span>
                                        @if($c->cbu_cvu)<div class="x-small text-muted mt-1 font-monospace">{{ $c->cbu_cvu }}</div>@endif
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h5 class="fw-bold mb-0 text-dark">${{ number_format($c->saldo_actual, 2, ',', '.') }}</h5>
                                    <span class="badge bg-light text-muted rounded-pill x-small border">{{ $c->movimientos_count }} movs.</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-5 text-center text-muted small">
                            No hay cuentas configuradas para este filtro.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: SÁBANA DE CONTROL (MOVIMIENTOS GLOBALES) --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 p-4">
                    <h5 class="fw-bold mb-0 text-dark">Sábana de Control (Auditoría Global)</h5>
                    <p class="text-muted x-small mb-0">Últimos movimientos liquidados en todas las fuentes.</p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light border-0">
                                <tr>
                                    <th class="ps-4 text-muted x-small text-uppercase border-0">Fecha</th>
                                    <th class="text-muted x-small text-uppercase border-0">Fuente</th>
                                    <th class="text-muted x-small text-uppercase border-0">Concepto</th>
                                    <th class="text-end pe-4 text-muted x-small text-uppercase border-0">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="border-0">
                                @forelse($movimientos as $m)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex flex-column lh-1">
                                            <span class="small fw-bold text-dark">{{ $m->fecha->format('d/m/Y') }}</span>
                                            <span class="x-small text-muted">{{ $m->fecha->format('H:i') }} hs</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border rounded-pill x-small">{{ $m->cuenta->nombre }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column lh-1">
                                            <span class="small text-dark fw-medium">{{ $m->descripcion }}</span>
                                            <span class="x-small text-muted text-uppercase">{{ $m->tipo }}</span>
                                        </div>
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
                <div class="card-footer bg-transparent border-0 p-4 text-center">
                    <a href="{{ route('empresa.reportes.panel') }}" class="btn btn-light btn-sm fw-bold rounded-pill border px-4">
                        VER REPORTE DE MOVIMIENTOS COMPLETO
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL NUEVA CUENTA --}}
<div class="modal fade" id="modalNuevaCuenta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white p-4 border-0">
                <h5 class="modal-title fw-bold">Configurar Nueva Fuente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('empresa.tesoreria.cuentas.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small text-muted fw-bold text-uppercase mb-1">Nombre Descriptivo</label>
                        <input type="text" name="nombre" class="form-control border-0 bg-light rounded-pill px-3" placeholder="Ej: Santander Río, Efectivo Central, Mercado Pago" required>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted fw-bold text-uppercase mb-1">Tipo de Fuente</label>
                        <select name="tipo" class="form-select border-0 bg-light rounded-pill px-3" required>
                            <option value="caja">💵 Caja / Efectivo</option>
                            <option value="banco">🏦 Banco</option>
                            <option value="billetera_digital">📱 Billetera Digital</option>
                            <option value="tarjeta_credito">💳 Tarjeta de Crédito</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted fw-bold text-uppercase mb-1">CBU / CVU / Alias</label>
                        <input type="text" name="cbu_cvu" class="form-control border-0 bg-light rounded-pill px-3" placeholder="Opcional">
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted fw-bold text-uppercase mb-1">Saldo Inicial</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-light rounded-start-pill ps-3">$</span>
                            <input type="number" name="saldo_inicial" step="0.01" class="form-control border-0 bg-light rounded-end-pill pe-3" value="0.00" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm">GUARDAR FUENTE DE DINERO</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .x-small { font-size: 0.7rem; }
</style>
@endsection
