@extends('layouts.empresa')

@section('content')
<div class="container px-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
        <div>
            <h4 class="mb-1 text-primary fw-bold">🤝 Gestión de Convenios B2B y Entidades Externas</h4>
            <p class="text-muted small mb-0">Conecta MedPlus con municipalidades, centros de licencias de conducir y entes de control para verificación de certificados médicos por DNI.</p>
        </div>
        <div>
            <a href="{{ route('empresa.hospitalization.dashboard') }}" class="btn btn-outline-secondary fw-bold shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> Volver al Mapa de Camas
            </a>
        </div>
    </div>

    {{-- ALERTAS --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- COLUMNA IZQUIERDA: REGISTRAR NUEVO CONVENIO --}}
        <div class="col-lg-5">
            <div class="card shadow border-0 bg-white rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-plus-circle-fill me-2 text-success"></i>Nuevo Convenio / Agencia</h5>
                    
                    <form action="{{ route('empresa.hospitalization.convenios.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Nombre del Ente Público / Privado</label>
                            <input type="text" name="nombre" class="form-control shadow-sm" placeholder="Ej: Municipalidad de Córdoba - Licencias" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">CUIT / Identificador Fiscal (Opcional)</label>
                            <input type="text" name="cuit" class="form-control shadow-sm" placeholder="Ej: 30-12345678-9">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Prácticas / Servicios Requeridos</label>
                            <select name="servicios_requeridos[]" class="form-select shadow-sm" style="min-height: 120px;" multiple required>
                                @foreach($servicios as $srv)
                                    <option value="{{ $srv->id }}">{{ $srv->nombre }} ({{ $srv->categoria }})</option>
                                @endforeach
                            </select>
                            <span class="x-small text-muted d-block mt-1" style="font-size: 0.75rem;">Presiona Ctrl (o Cmd) para seleccionar múltiples prácticas.</span>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success text-white fw-bold w-100 rounded-pill shadow-sm">
                                <i class="bi bi-shield-check"></i> Registrar y Crear Enlace Seguro
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow border-0 bg-light rounded-4 mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-info-circle text-primary me-2"></i>¿Cómo funciona?</h6>
                    <p class="text-muted small mb-0">
                        Al registrar una Municipalidad, el sistema genera automáticamente un **Enlace Seguro Único**. 
                        El personal de la municipalidad no necesita contraseñas; ingresando a este enlace tendrán acceso instantáneo al **Portal OLED de Búsqueda por DNI**, validando la vigencia y aptitud del solicitante en segundos.
                    </p>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: LISTADO DE CONVENIOS Y ENLACES --}}
        <div class="col-lg-7">
            <div class="card shadow border-0 bg-white rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-link-45deg me-2 text-primary"></i>Enlaces de Convenio Activos</h5>
                    
                    @if($conventions->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-people display-4 opacity-25 d-block mb-3"></i>
                            No hay convenios ni agencias de control registradas todavía.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border">
                                <thead class="table-light">
                                    <tr>
                                        <th class="small fw-bold py-3">Agencia</th>
                                        <th class="small fw-bold py-3">Enlace de Consulta</th>
                                        <th class="small fw-bold py-3 text-center">Estado</th>
                                        <th class="small fw-bold py-3 text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($conventions as $conv)
                                        @php
                                            $portalUrl = route('portal.agencia.dashboard', $conv->token_acceso);
                                        @endphp
                                        <tr>
                                            <td class="py-3">
                                                <div class="fw-bold text-dark">{{ $conv->nombre }}</div>
                                                <span class="x-small text-muted font-monospace d-block">{{ $conv->cuit ?? 'Sin CUIT' }}</span>
                                                @if(!empty($conv->servicios_requeridos))
                                                    @php
                                                        $srvNames = \App\Models\Servicio::whereIn('id', (array) $conv->servicios_requeridos)->pluck('nombre')->toArray();
                                                    @endphp
                                                    <div class="mt-1 d-flex flex-wrap gap-1">
                                                        @foreach($srvNames as $sname)
                                                            <span class="badge bg-light text-secondary border x-small py-1 px-2" style="font-size: 0.65rem;">{{ $sname }}</span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="py-3">
                                                <div class="input-group input-group-sm" style="max-width: 250px;">
                                                    <input type="text" id="url-{{ $conv->id }}" class="form-control bg-light text-muted border-secondary small" value="{{ $portalUrl }}" readonly>
                                                    <button onclick="copyToClipboard({{ $conv->id }})" class="btn btn-primary" title="Copiar Enlace">
                                                        <i class="bi bi-clipboard"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="py-3 text-center">
                                                <span class="badge {{ $conv->activo ? 'bg-success' : 'bg-danger' }} rounded-pill small">
                                                    {{ $conv->activo ? 'Activo' : 'Pausado' }}
                                                </span>
                                            </td>
                                            <td class="py-3 text-end">
                                                <form action="{{ route('empresa.hospitalization.convenios.toggle', $conv->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $conv->activo ? 'btn-outline-danger' : 'btn-outline-success' }} fw-bold rounded-pill px-3">
                                                        {{ $conv->activo ? 'Pausar' : 'Activar' }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(id) {
        const inputEl = document.getElementById('url-' + id);
        inputEl.select();
        inputEl.setSelectionRange(0, 99999); // Para móviles
        navigator.clipboard.writeText(inputEl.value);

        // Notificar al usuario mediante un toast elegante
        Swal.fire({
            title: '¡Enlace Copiado!',
            text: 'Enlace seguro copiado al portapapeles. Listo para enviar al ente de control.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false,
            background: '#0f172a',
            color: '#f3f4f6'
        });
    }
</script>
@endsection
