@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">

    {{-- CABECERA Y KPIs --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Cartera de Cheques</h2>
            <p class="text-muted small mb-0">Gestión centralizada de valores recibidos y emitidos</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-light border px-3 rounded-pill" onclick="window.print()">
                <i class="fas fa-print me-1"></i> Imprimir Listado
            </button>
        </div>
    </div>

    <div class="row g-2 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                <div class="card-body p-3">
                    <span class="text-uppercase x-small fw-bold opacity-75 d-block mb-1">En Cartera</span>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="fw-bold mb-0">${{ number_format($stats['en_cartera_monto'], 2, ',', '.') }}</h3>
                        <span class="opacity-75 x-small fw-bold">{{ $stats['en_cartera_count'] }} Ch.</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <span class="text-uppercase x-small fw-bold text-muted d-block mb-1">Próximos a Vencer (7d)</span>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="fw-bold mb-0 text-warning">${{ number_format($stats['proximos_vencer'], 2, ',', '.') }}</h3>
                        <i class="fas fa-calendar-check text-warning opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <span class="text-uppercase x-small fw-bold text-muted d-block mb-1">Propios Emitidos</span>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="fw-bold mb-0 text-danger">${{ number_format($stats['propios_emitidos'], 2, ',', '.') }}</h3>
                        <i class="fas fa-paper-plane text-danger opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-dark text-white">
                <div class="card-body p-3">
                    <span class="text-uppercase x-small fw-bold opacity-75 d-block mb-1">Total Movilizado</span>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="fw-bold mb-0">${{ number_format($stats['en_cartera_monto'] + $stats['propios_emitidos'], 2, ',', '.') }}</h3>
                        <i class="fas fa-sync text-white opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTROS --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="x-small fw-bold text-muted text-uppercase mb-1">Tipo</label>
                    <select name="tipo" class="form-select form-select-sm border-0 bg-light rounded-pill">
                        <option value="">Todos</option>
                        <option value="tercero" {{ request('tipo') == 'tercero' ? 'selected' : '' }}>De Terceros (Clientes)</option>
                        <option value="propio" {{ request('tipo') == 'propio' ? 'selected' : '' }}>Propios (Emitidos)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="x-small fw-bold text-muted text-uppercase mb-1">Estado</label>
                    <select name="estado" class="form-select form-select-sm border-0 bg-light rounded-pill">
                        <option value="">Todos</option>
                        <option value="en_cartera" {{ request('estado') == 'en_cartera' ? 'selected' : '' }}>En Cartera</option>
                        <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>Entregado / Emitido</option>
                        <option value="depositado" {{ request('estado') == 'depositado' ? 'selected' : '' }}>Depositado</option>
                        <option value="cobrado" {{ request('estado') == 'cobrado' ? 'selected' : '' }}>Cobrado / Pagado</option>
                        <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="x-small fw-bold text-muted text-uppercase mb-1">Desde Vto</label>
                    <input type="date" name="desde" class="form-control form-control-sm border-0 bg-light rounded-pill" value="{{ request('desde') }}">
                </div>
                <div class="col-md-2">
                    <label class="x-small fw-bold text-muted text-uppercase mb-1">Hasta Vto</label>
                    <input type="date" name="hasta" class="form-control form-control-sm border-0 bg-light rounded-pill" value="{{ request('hasta') }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-dark px-4 rounded-pill fw-bold">Filtrar</button>
                    @if(request()->anyFilled(['tipo', 'estado', 'desde', 'hasta']))
                        <a href="{{ route('empresa.tesoreria.cheques.index') }}" class="btn btn-sm btn-outline-secondary px-3 rounded-pill">Limpiar</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- LISTADO --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="x-small fw-bold text-muted text-uppercase">
                        <th class="ps-4">Vencimiento</th>
                        <th>Número</th>
                        <th>Banco / Emisor</th>
                        <th>Tipo</th>
                        <th>Origen / Destino</th>
                        <th class="text-end">Monto</th>
                        <th class="text-center">Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cheques as $c)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold {{ $c->fecha_pago->isPast() && $c->estado == 'en_cartera' ? 'text-danger' : 'text-dark' }}">
                                {{ $c->fecha_pago->format('d/m/Y') }}
                            </span>
                            @if($c->fecha_pago->isPast() && $c->estado == 'en_cartera')
                                <i class="fas fa-exclamation-circle text-danger ms-1" title="Vencido"></i>
                            @endif
                        </td>
                        <td class="fw-bold">#{{ $c->numero }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $c->banco }}</div>
                            <div class="x-small text-muted text-uppercase">{{ $c->emisor }}</div>
                        </td>
                        <td>
                            @if($c->tipo == 'propio')
                                <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-2">PROPIO</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2">TERCERO</span>
                            @endif
                        </td>
                        <td>
                            @if($c->tipo == 'tercero')
                                <small class="text-muted d-block">De: <span class="text-dark fw-bold">{{ $c->client->name ?? 'Cliente' }}</span></small>
                                @if($c->supplier_id)
                                    <small class="text-muted d-block mt-1">Entregado a: <span class="text-primary fw-bold">{{ $c->supplier->name }}</span></small>
                                @endif
                            @else
                                <small class="text-muted d-block">A: <span class="text-danger fw-bold">{{ $c->supplier->name ?? 'Proveedor' }}</span></small>
                                <small class="text-muted x-small">{{ $c->chequera->numero_cuenta ?? '' }}</small>
                            @endif
                        </td>
                        <td class="text-end fw-bold text-dark fs-6">
                            ${{ number_format($c->monto, 2, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @php
                                $badgeClass = match($c->estado) {
                                    'en_cartera' => 'bg-success',
                                    'depositado' => 'bg-primary',
                                    'entregado'  => 'bg-info',
                                    'cobrado'    => 'bg-dark',
                                    'rechazado'  => 'bg-danger',
                                    'anulado'    => 'bg-secondary',
                                    default      => 'bg-light text-dark'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} rounded-pill px-3 py-1 scale-up">
                                {{ strtoupper(str_replace('_', ' ', $c->estado)) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border rounded-pill px-3 dropdown-toggle shadow-none" data-bs-toggle="dropdown">
                                    Gestionar
                                </button>
                                <ul class="dropdown-menu shadow border-0 dropdown-menu-end">
                                    @if($c->estado == 'en_cartera' || ($c->tipo == 'propio' && $c->estado == 'entregado'))
                                        <li>
                                            <button type="button" class="dropdown-item fw-bold text-success" 
                                                onclick="openStatusModal({{ $c->id }}, 'cobrado', '{{ $c->tipo == 'tercero' ? 'Cobrar / Efectivizar' : 'Marcar como Pagado (Débito)' }}')">
                                                <i class="fas fa-money-bill-wave me-2"></i> 
                                                {{ $c->tipo == 'tercero' ? 'Marcar como Cobrado' : 'Confirmar Débito Bancario' }}
                                            </button>
                                        </li>
                                    @endif

                                    @if($c->estado == 'en_cartera' && $c->tipo == 'tercero')
                                        <li>
                                            <button type="button" class="dropdown-item" 
                                                onclick="openStatusModal({{ $c->id }}, 'depositado', 'Depositar en Banco')">
                                                <i class="fas fa-university me-2 text-primary"></i> Marcar como Depositado
                                            </button>
                                        </li>
                                    @endif

                                    @if($c->estado != 'rechazado' && $c->estado != 'anulado')
                                        <li>
                                            <form action="{{ route('empresa.tesoreria.cheques.status', $c->id) }}" method="POST" onsubmit="return confirm('¿Marcar este cheque como RECHAZADO?')">
                                                @csrf <input type="hidden" name="estado" value="rechazado">
                                                <button type="submit" class="dropdown-item text-danger small"><i class="fas fa-times-circle me-2"></i> Marcar como RECHAZADO</button>
                                            </form>
                                        </li>
                                    @endif

                                    @if($c->estado == 'en_cartera' || ($c->tipo == 'propio' && $c->estado == 'entregado'))
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('empresa.tesoreria.cheques.status', $c->id) }}" method="POST" onsubmit="return confirm('¿Anular este cheque?')">
                                                @csrf <input type="hidden" name="estado" value="anulado">
                                                <button type="submit" class="dropdown-item small"><i class="fas fa-ban me-2"></i> Anular Cheque</button>
                                            </form>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-money-check fa-3x text-muted opacity-25 mb-3"></i>
                            <h5 class="text-muted">No se encontraron cheques con los filtros aplicados</h5>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($cheques->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $cheques->links() }}
        </div>
        @endif
    </div>
</div>

{{-- MODAL PARA CAMBIO DE ESTADO (CON CONCILIACIÓN) --}}
<div class="modal fade" id="modalStatusCheque" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white p-3 border-0">
                <h6 class="modal-title fw-bold" id="statusModalTitle">Actualizar Estado</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formStatusCheque" method="POST">
                @csrf
                <input type="hidden" name="estado" id="inputNuevoEstado">
                <div class="modal-body p-4">
                    <div id="divCuentaSeleccion" style="display:none;">
                        <label class="small fw-bold text-muted mb-2 d-block text-uppercase">Seleccionar Cuenta para el Movimiento</label>
                        <select name="cuenta_id" class="form-select border-0 bg-light rounded-pill px-3 mb-3" required id="selectCuentaStatus">
                            <option value="">-- Seleccionar Cuenta --</option>
                            @foreach($cuentas as $cuenta)
                                <option value="{{ $cuenta->id }}">{{ $cuenta->nombre }} (${{ number_format($cuenta->saldo_actual, 2, ',', '.') }})</option>
                            @endforeach
                        </select>
                        <p class="x-small text-muted mb-0"><i class="fas fa-info-circle me-1"></i> Al confirmar, se generará un movimiento de caja automático.</p>
                    </div>
                    <div id="divConfirmacionSimple" style="display:none;">
                        <p class="mb-0 text-center py-2">¿Estás seguro de cambiar el estado de este cheque?</p>
                    </div>
                </div>
                <div class="modal-footer p-3 border-0">
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm">CONFIRMAR</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openStatusModal(chequeId, nuevoEstado, titulo) {
    const form = document.getElementById('formStatusCheque');
    form.action = `/empresa/tesoreria/cheques/${chequeId}/status`;
    document.getElementById('inputNuevoEstado').value = nuevoEstado;
    document.getElementById('statusModalTitle').innerText = titulo;

    const divCuenta = document.getElementById('divCuentaSeleccion');
    const divSimple = document.getElementById('divConfirmacionSimple');
    const selectCuenta = document.getElementById('selectCuentaStatus');

    if (nuevoEstado === 'cobrado' || nuevoEstado === 'depositado') {
        divCuenta.style.display = 'block';
        divSimple.style.display = 'none';
        selectCuenta.required = true;
    } else {
        divCuenta.style.display = 'none';
        divSimple.style.display = 'block';
        selectCuenta.required = false;
    }

    const modal = new bootstrap.Modal(document.getElementById('modalStatusCheque'));
    modal.show();
}
</script>

<style>
    .x-small { font-size: 0.7rem; }
    .scale-up:hover { transform: scale(1.05); cursor: default; }
    @media print {
        .navbar, .btn, form, th:last-child, td:last-child { display: none !important; }
        .card { box-shadow: none !important; border: 1px solid #eee !important; }
    }
</style>
@endsection
