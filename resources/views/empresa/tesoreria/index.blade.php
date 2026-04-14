@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Gestión de Tesorería</h2>
            <p class="text-muted small mb-0">Administración de bancos, cajas y flujo de dinero.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('empresa.tesoreria.proyeccion') }}" class="btn btn-outline-dark px-4 fw-bold rounded-pill shadow-sm">
                <i class="fas fa-chart-line me-1"></i> Proyección
            </a>
            <button class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaCuenta">
                <i class="fas fa-plus-circle me-1"></i> Nueva Cuenta
            </button>
            <button class="btn btn-dark px-4 fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTransferencia">
                <i class="fas fa-exchange-alt me-1"></i> Transferir
            </button>
        </div>
    </div>

    {{-- CARDS DE RESUMEN --}}
    <div class="row g-2 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary text-white">
                <div class="card-body p-3">
                    <span class="text-uppercase x-small fw-bold opacity-75 d-block mb-1">Total en Cuentas</span>
                    <h3 class="fw-bold mb-0">${{ number_format($totalEnCuentas, 2, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3">
                    <span class="text-uppercase x-small fw-bold text-muted d-block mb-1">Cuentas Activas</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $cuentas->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3">
                    <span class="text-uppercase x-small fw-bold text-muted d-block mb-1">Cartera de Cheques</span>
                    <a href="{{ route('empresa.tesoreria.cheques.index') }}" class="text-decoration-none h3 fw-bold mb-0 text-primary d-block">Ver Cartera</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- LISTADO DE CUENTAS (IZQUIERDA) --}}
        <div class="col-md-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-vault me-2 text-primary"></i> Mis Cuentas</h5>
            @foreach($cuentas as $cuenta)
                <div class="card border-0 shadow-sm rounded-4 mb-2 overflow-hidden hover-card">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle bg-light p-3 me-3 text-primary">
                            @if($cuenta->tipo == 'caja') <i class="fas fa-cash-register"></i>
                            @elseif($cuenta->tipo == 'banco') <i class="fas fa-university"></i>
                            @elseif($cuenta->tipo == 'billetera_digital') <i class="fas fa-wallet"></i>
                            @else <i class="fas fa-credit-card"></i> @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-0 text-dark">{{ $cuenta->nombre }}</h6>
                            <span class="x-small text-muted">{{ ucfirst(str_replace('_', ' ', $cuenta->tipo)) }}</span>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-bold mb-0 {{ $cuenta->saldo_actual >= 0 ? 'text-success' : 'text-danger' }}">
                                ${{ number_format($cuenta->saldo_actual, 2, ',', '.') }}
                            </h5>
                            <span class="x-small text-muted">{{ $cuenta->movimientos_count }} movs.</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ÚLTIMOS MOVIMIENTOS (DERECHA) --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-history me-2 text-primary"></i> Libro Diario Reciente</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="x-small fw-bold text-muted text-uppercase text-nowrap">
                                <th class="ps-4">Fecha / Cuenta</th>
                                <th>Concepto</th>
                                <th class="text-end">Ingreso (+)</th>
                                <th class="text-end">Egreso (-)</th>
                                <th class="text-center pe-4">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movimientos as $m)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $m->fecha->format('d/m/Y') }}</div>
                                        <div class="x-small text-primary fw-bold">{{ $m->cuenta->nombre }}</div>
                                    </td>
                                    <td>
                                        <div class="small fw-semibold text-dark">{{ $m->concepto }}</div>
                                        <div class="x-small text-muted">{{ $m->categoria ?: 'Sin categoría' }}</div>
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        {{ $m->tipo == 'ingreso' ? '$' . number_format($m->monto, 2, ',', '.') : '-' }}
                                    </td>
                                    <td class="text-end fw-bold text-danger">
                                        {{ $m->tipo == 'egreso' ? '$' . number_format($m->monto, 2, ',', '.') : '-' }}
                                    </td>
                                    <td class="text-center pe-4">
                                        @if($m->conciliado)
                                            <span class="badge bg-success-soft text-success rounded-pill x-small"><i class="fas fa-check-circle me-1"></i> Conciliado</span>
                                        @else
                                            <span class="badge bg-warning-soft text-warning rounded-pill x-small"><i class="fas fa-clock me-1"></i> Abierto</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-5 text-center text-muted">No hay movimientos registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
                <h5 class="modal-title fw-bold">Nueva Cuenta Financiera</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('empresa.tesoreria.cuentas.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Nombre de la Cuenta</label>
                        <input type="text" name="nombre" class="form-control border-0 bg-light rounded-pill px-3" placeholder="Ej: Banco Galicia, Caja Chica..." required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted mb-1">Tipo</label>
                            <select name="tipo" class="form-select border-0 bg-light rounded-pill px-3" required>
                                <option value="caja">💵 Caja / Efectivo</option>
                                <option value="banco">🏦 Banco</option>
                                <option value="billetera_digital">📱 Billetera Digital</option>
                                <option value="tarjeta_credito">💳 Tarjeta de Crédito</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted mb-1">Saldo Inicial</label>
                            <input type="number" step="0.01" name="saldo_inicial" class="form-control border-0 bg-light rounded-pill px-3" value="0.00">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Número de Cuenta / CBU / CVU</label>
                        <input type="text" name="cbu_cvu" class="form-control border-0 bg-light rounded-pill px-3" placeholder="Opcional">
                    </div>
                </div>
                <div class="modal-footer p-4 pt-0 border-0">
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">CREAR CUENTA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .x-small { font-size: 0.7rem; }
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
    .bg-warning-soft { background-color: rgba(255, 193, 7, 0.1); }
    .hover-card { transition: all 0.2s ease; cursor: pointer; }
    .hover-card:hover { transform: translateX(5px); background-color: #f8f9fa; }
</style>
@endsection
