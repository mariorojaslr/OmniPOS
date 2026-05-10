@extends('layouts.empresa')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Configuración Profesional: {{ $user->name }}</h2>
            <p class="text-muted small mb-0">Define el esquema de pagos y portal individual.</p>
        </div>
        <a href="{{ route('empresa.usuarios.index') }}" class="btn btn-light border rounded-pill px-3">VOLVER</a>
    </div>

    <form action="{{ route('empresa.usuarios.config-profesional.update', $user->id) }}" method="POST">
        @csrf
        
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row g-4">
            {{-- REMUNERACIÓN --}}
            <div class="col-md-7">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-4">Esquema de Remuneración</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Tipo de Contratación</label>
                            <select name="tipo_contrato" class="form-select">
                                <option value="fijo" {{ $config->tipo_contrato == 'fijo' ? 'selected' : '' }}>Sueldo Fijo (Standard)</option>
                                <option value="comision" {{ $config->tipo_contrato == 'comision' ? 'selected' : '' }}>Solo Comisión (Por Trabajo)</option>
                                <option value="mixto" {{ $config->tipo_contrato == 'mixto' ? 'selected' : '' }}>Mixto (Sueldo + Comisión)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Sueldo Base ($)</label>
                            <input type="number" name="sueldo_base" value="{{ $config->sueldo_base }}" step="0.01" class="form-control">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Tipo de Comisión General</label>
                            <select name="tipo_comision" class="form-select">
                                <option value="porcentaje" {{ $config->tipo_comision == 'porcentaje' ? 'selected' : '' }}>Porcentaje %</option>
                                <option value="fijo" {{ $config->tipo_comision == 'fijo' ? 'selected' : '' }}>Monto Fijo $</option>
                            </select>
                            <small class="text-muted x-small">Se aplica si el servicio no tiene comisión propia.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Valor Comisión</label>
                            <input type="number" name="valor_comision" value="{{ $config->valor_comision }}" step="0.01" class="form-control">
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="fw-bold mb-3">Especialidades Asignadas</h6>
                    <div class="row mb-4">
                        @foreach($servicios->groupBy('categoria') as $cat => $items)
                            <div class="col-md-6 mb-3">
                                <p class="small fw-bold text-primary mb-2 text-uppercase">{{ $cat }}</p>
                                @foreach($items as $s)
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" name="especialidades[]" value="{{ $s->id }}" 
                                            id="serv_{{ $s->id }}" {{ in_array($s->id, (array)$config->especialidades) ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="serv_{{ $s->id }}">
                                            {{ $s->nombre }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    <h6 class="fw-bold mb-3 mt-4">Acuerdos Particulares por Servicio</h6>
                    <p class="text-muted x-small">Define comisiones específicas si difieren de la general. Deja el valor vacío para usar la general.</p>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless align-middle">
                            <thead>
                                <tr class="text-muted x-small">
                                    <th>SERVICIO</th>
                                    <th width="120">TIPO</th>
                                    <th width="120">VALOR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($servicios as $s)
                                    @php $acuerdo = $acuerdos[$s->id] ?? null; @endphp
                                    <tr class="border-bottom border-light">
                                        <td>
                                            <span class="small fw-medium text-dark">{{ $s->nombre }}</span>
                                        </td>
                                        <td>
                                            <select name="acuerdos[{{ $s->id }}][tipo]" class="form-select form-select-sm border-0 bg-light">
                                                <option value="porcentaje" {{ ($acuerdo && $acuerdo->tipo_comision == 'porcentaje') ? 'selected' : '' }}>% (Porc)</option>
                                                <option value="monto_fijo" {{ ($acuerdo && $acuerdo->tipo_comision == 'monto_fijo') ? 'selected' : '' }}>$ (Fijo)</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="acuerdos[{{ $s->id }}][valor]" value="{{ $acuerdo ? (float)$acuerdo->valor : '' }}" step="0.01" placeholder="General" class="form-control form-control-sm border-0 bg-light">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- PORTAL INDIVIDUAL --}}
            <div class="col-md-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-primary bg-opacity-10">
                    <h5 class="fw-bold mb-2">Portal del Profesional</h5>
                    <p class="text-muted x-small mb-4">Esta URL es privada para el empleado. Puede ver sus turnos y liquidaciones desde el celular.</p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Token de Acceso</label>
                        <input type="text" name="token_portal" value="{{ $config->token_portal }}" class="form-control bg-white border-0 shadow-sm mb-2" readonly>
                        <button type="button" class="btn btn-sm btn-primary w-100 rounded-pill" onclick="copiarPortal()">
                            <i class="bi bi-link-45deg me-1"></i> COPIAR LINK DEL PORTAL
                        </button>
                    </div>

                    <div class="alert alert-info border-0 rounded-4 x-small mb-0">
                        <i class="bi bi-info-circle me-1"></i> <strong>Tip Profesional:</strong> El empleado puede guardar este link como "Acceso Directo" en su pantalla de inicio para que funcione como una App.
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-dark w-100 py-3 rounded-pill fw-bold shadow">
                        <i class="bi bi-save me-2"></i> GUARDAR CAMBIOS
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function copiarPortal() {
        const url = "{{ url('/portal/profesional') }}/{{ $config->token_portal }}";
        navigator.clipboard.writeText(url).then(() => {
            alert('¡Link copiado al portapapeles!');
        });
    }
</script>
@endsection
