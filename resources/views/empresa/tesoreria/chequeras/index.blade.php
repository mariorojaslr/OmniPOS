@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Chequeras Propias</h2>
            <p class="text-muted small mb-0">Gestión de bancos y rangos de numeración para emisión de cheques</p>
        </div>
        <button class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaChequera">
            <i class="fas fa-plus me-1"></i> Nueva Chequera
        </button>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <h6 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Errores encontrados:</h6>
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        @forelse($chequeras as $c)
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 {{ !$c->activo ? 'opacity-75 bg-light' : '' }}">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                            <i class="fas fa-university fa-lg fa-fw"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-circle p-1" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v text-muted"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                <li>
                                    <form action="{{ route('empresa.tesoreria.chequeras.update', $c->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="activo" value="{{ $c->activo ? 0 : 1 }}">
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas {{ $c->activo ? 'fa-ban text-danger' : 'fa-check text-success' }} me-2"></i>
                                            {{ $c->activo ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                </li>
                                @if($c->emitidos_count == 0)
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('empresa.tesoreria.chequeras.destroy', $c->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta chequera?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-trash-alt me-2"></i> Eliminar
                                        </button>
                                    </form>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark mb-1">
                        {{ $c->banco }}
                        @if($c->tipo === 'echeck')
                            <span class="badge bg-purple text-purple bg-opacity-10 x-small rounded-pill ms-1">E-CHECK</span>
                        @else
                            <span class="badge bg-info text-info bg-opacity-10 x-small rounded-pill ms-1">FÍSICA</span>
                        @endif
                    </h5>
                    <p class="text-muted small mb-3">
                        <i class="fas fa-id-card-alt me-1"></i> {{ $c->numero_cuenta }} 
                        <span class="mx-1">·</span> 
                        <span class="text-uppercase">{{ str_replace('_', ' ', $c->tipo_cuenta) }}</span>
                    </p>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between x-small fw-bold text-uppercase mb-1">
                            <span class="text-muted">Uso de Chequera</span>
                            <span class="{{ $c->agotada ? 'text-danger' : 'text-primary' }}">{{ $c->porcentaje_uso }}%</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 8px;">
                            <div class="progress-bar {{ $c->agotada ? 'bg-danger' : 'bg-primary' }}" role="progressbar" style="width: {{ $c->porcentaje_uso }}%"></div>
                        </div>
                    </div>

                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <div class="bg-light p-2 rounded-3">
                                <span class="d-block x-small text-muted text-uppercase fw-bold">Rango</span>
                                <span class="fw-bold text-dark">{{ $c->desde }}-{{ $c->hasta }}</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light p-2 rounded-3">
                                <span class="d-block x-small text-muted text-uppercase fw-bold">Próximo</span>
                                <span class="fw-bold text-primary">#{{ $c->proximo_numero }}</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light p-2 rounded-3">
                                <span class="d-block x-small text-muted text-uppercase fw-bold">Libres</span>
                                <span class="fw-bold text-success">{{ $c->disponibles }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="bg-light d-inline-block rounded-circle p-5 mb-3">
                <i class="fas fa-book-open fa-3x text-muted opacity-25"></i>
            </div>
            <h4 class="text-muted">No tienes chequeras configuradas</h4>
            <p class="text-muted small">Crea tu primera chequera para empezar a emitir pagos con cheques propios.</p>
            <button class="btn btn-primary px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalNuevaChequera">
                Empezar ahora
            </button>
        </div>
        @endforelse
    </div>
</div>

{{-- MODAL NUEVA CHEQUERA --}}
<div class="modal fade" id="modalNuevaChequera" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 p-4 bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i> Configurar Nueva Chequera</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('empresa.tesoreria.chequeras.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="small text-uppercase fw-bold text-muted mb-1">Tipo de Chequera</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo" id="tipo_fisica" value="fisica" checked onchange="toggleRangeInputs(this.value)">
                                    <label class="form-check-label small fw-bold" for="tipo_fisica">Física (Talonario)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo" id="tipo_echeck" value="echeck" onchange="toggleRangeInputs(this.value)">
                                    <label class="form-check-label small fw-bold" for="tipo_echeck">E-Check (Electrónica)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label class="small text-uppercase fw-bold text-muted mb-1">Banco / Entidad</label>
                            <input type="text" name="banco" class="form-control border-0 bg-light rounded-pill px-3" placeholder="Ej: Banco Galicia" required>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-uppercase fw-bold text-muted mb-1">Sucursal</label>
                            <input type="text" name="sucursal" class="form-control border-0 bg-light rounded-pill px-3" placeholder="Opcional">
                        </div>
                        <div class="col-md-6">
                            <label class="small text-uppercase fw-bold text-muted mb-1">CBU / Nro. Cuenta</label>
                            <input type="text" name="numero_cuenta" class="form-control border-0 bg-light rounded-pill px-3" placeholder="Nro de cuenta o CBU" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-uppercase fw-bold text-muted mb-1">Tipo de Cuenta</label>
                            <select name="tipo_cuenta" class="form-select border-0 bg-light rounded-pill px-3">
                                <option value="cuenta_corriente">Cuenta Corriente</option>
                                <option value="caja_ahorro">Caja de Ahorro</option>
                            </select>
                        </div>
                        <div id="range_inputs" class="row gx-2 mt-3">
                            <div class="col-md-4">
                                <label class="small text-uppercase fw-bold text-muted mb-1 text-nowrap">Desde N°</label>
                                <input type="number" name="desde" id="input_desde" class="form-control border-0 bg-light rounded-pill px-3 text-center" value="1">
                            </div>
                            <div class="col-md-4">
                                <label class="small text-uppercase fw-bold text-muted mb-1 text-nowrap">Hasta N°</label>
                                <input type="number" name="hasta" id="input_hasta" class="form-control border-0 bg-light rounded-pill px-3 text-center" placeholder="50">
                            </div>
                            <div class="col-md-4">
                                <label class="small text-uppercase fw-bold text-muted mb-1 text-nowrap">Iniciar en</label>
                                <input type="number" name="proximo_numero" class="form-control border-0 bg-light rounded-pill px-3 text-center" placeholder="1">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="small text-uppercase fw-bold text-muted mb-1">Notas Internas</label>
                            <textarea name="notas" class="form-control border-0 bg-light rounded-4 px-3" rows="2" placeholder="Opcional..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm">
                        Guardar Chequera
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function toggleRangeInputs(tipo) {
        const div = document.getElementById('range_inputs');
        const desde = document.getElementById('input_desde');
        const hasta = document.getElementById('input_hasta');
        
        if (tipo === 'echeck') {
            div.style.display = 'none';
            desde.removeAttribute('required');
            hasta.removeAttribute('required');
        } else {
            div.style.display = 'flex';
            desde.setAttribute('required', 'required');
            hasta.setAttribute('required', 'required');
        }
    }
</script>
@endsection

<style>
    .letter-spaced { letter-spacing: 0.05em; }
    .x-small { font-size: 0.7rem; }
    .bg-purple { background-color: #6f42c1 !important; }
    .text-purple { color: #6f42c1 !important; }
</style>
@endsection
