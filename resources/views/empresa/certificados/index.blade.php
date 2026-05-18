@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
        <div>
            <h4 class="mb-1 text-primary fw-bold">📜 Certificados Médicos Oficiales</h4>
            <p class="text-muted small mb-0">Emisión de constancias de aptitud psicofísica, licencias médicas y certificados escolares firmados digitalmente con verificación QR.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('empresa.certificados.create') }}" class="btn btn-success fw-bold shadow-sm">
                <i class="bi bi-plus-circle me-2"></i> Emitir Certificado Oficial
            </a>
        </div>
    </div>

    {{-- ALERTA --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- BUSCADOR --}}
    <div class="card shadow-sm border-0 mb-4 bg-white rounded-3">
        <div class="card-body py-3">
            <form action="{{ route('empresa.certificados.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-9 col-sm-8">
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" value="{{ $search }}" class="form-control border-start-0 shadow-none" placeholder="Buscar por paciente, DNI, o código de verificación QR...">
                    </div>
                </div>
                <div class="col-md-3 col-sm-4">
                    <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill">
                        Buscar Certificados
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLA DE CERTIFICADOS --}}
    <div class="card shadow border-0 bg-white rounded-4 overflow-hidden mb-5">
        <div class="card-body p-0">
            @if($certificates->isEmpty())
                <div class="text-center py-5 my-4 text-muted">
                    <i class="bi bi-patch-check display-3 opacity-25 d-block mb-3"></i>
                    No se encontraron certificados médicos oficiales emitidos en este periodo.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light border-bottom">
                            <tr>
                                <th class="px-4 py-3 small fw-bold">Paciente</th>
                                <th class="py-3 small fw-bold">Tipo de Certificado</th>
                                <th class="py-3 small fw-bold">Médico Firmante</th>
                                <th class="py-3 small fw-bold">Código Validación QR</th>
                                <th class="py-3 small fw-bold">Fecha de Emisión</th>
                                <th class="py-3 small fw-bold">Validez hasta</th>
                                <th class="py-3 small fw-bold text-center">Estado Auditoría</th>
                                <th class="px-4 py-3 small fw-bold text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($certificates as $c)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold text-dark">{{ $c->patient?->name ?? 'Paciente Desconocido' }}</div>
                                        <small class="text-muted">DNI: {{ $c->patient?->document ?? 'Sin DNI' }}</small>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 fw-bold rounded-pill">
                                            {{ $c->tipo }}
                                        </span>
                                    </td>
                                    <td class="py-3 font-semibold text-dark">
                                        {{ $c->doctor?->name }}
                                        <div class="x-small text-muted">M.P. {{ $c->doctor?->profesionalConfig?->matricula ?? 'Pendiente' }}</div>
                                    </td>
                                    <td class="py-3">
                                        <code class="text-success fw-bold">{{ $c->codigo_validacion }}</code>
                                    </td>
                                    <td class="py-3 small text-muted">{{ $c->created_at->format('d/m/Y H:i') }} hs</td>
                                    <td class="py-3 small text-muted">{{ $c->vigencia_hasta ? $c->vigencia_hasta->format('d/m/Y') : 'Permanente' }}</td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 fw-bold rounded-pill">
                                            <i class="bi bi-shield-check me-1"></i> Verificado QR
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('certificados.print', $c->id) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Imprimir Certificado con QR">
                                                <i class="bi bi-printer-fill me-1"></i> Imprimir
                                            </a>
                                            <a href="{{ route('certificados.public-validate', $c->codigo_validacion) }}" target="_blank" class="btn btn-sm btn-light" title="Verificar Página Pública QR">
                                                <i class="bi bi-qr-code-scan"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="card-footer bg-white py-3 border-0">
                    {{ $certificates->appends(['search' => $search])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
