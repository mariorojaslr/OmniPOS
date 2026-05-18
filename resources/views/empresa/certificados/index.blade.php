@extends('layouts.empresa')

@php
    $config = auth()->user()->empresa?->config;
    $modoOscuro = ($config?->theme === 'dark');
    $colorPrimario = $config?->color_primario ?? '#0d6efd';
@endphp

@section('content')
<style>
    .premium-form-card {
        background: {{ $modoOscuro ? 'rgba(11, 17, 32, 0.8)' : '#ffffff' }};
        backdrop-filter: blur(15px);
        border: 1px solid {{ $modoOscuro ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.08)' }} !important;
        border-radius: 24px;
        box-shadow: 0 20px 45px rgba(0, 0, 0, {{ $modoOscuro ? '0.4' : '0.06' }});
        transition: all 0.3s ease;
    }
    
    .premium-input {
        background: {{ $modoOscuro ? 'rgba(255,255,255,0.02)' : '#f8fafc' }} !important;
        border: 1px solid {{ $modoOscuro ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.12)' }} !important;
        color: {{ $modoOscuro ? '#f8fafc' : '#1e293b' }} !important;
        border-radius: 14px !important;
        padding: 12px 18px !important;
        font-size: 0.95rem !important;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }
    
    .premium-input:focus {
        border-color: var(--color-primario) !important;
        box-shadow: 0 0 15px {{ $colorPrimario }}35 !important;
        background: {{ $modoOscuro ? 'rgba(255,255,255,0.04)' : '#ffffff' }} !important;
    }
    
    .table th {
        color: {{ $modoOscuro ? '#94a3b8' : '#64748b' }} !important;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.8px;
        background: transparent !important;
    }
    
    .table td {
        color: {{ $modoOscuro ? '#f1f5f9' : '#1e293b' }} !important;
        background: transparent !important;
    }

    .table tr {
        border-bottom: 1px solid {{ $modoOscuro ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)' }} !important;
    }
</style>

<div class="container-fluid px-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="mb-1 fw-extrabold" style="color: {{ $modoOscuro ? '#ffffff' : '#1e293b' }};">📜 Certificados Médicos Oficiales</h4>
            <p class="small mb-0 {{ $modoOscuro ? 'text-muted' : 'text-secondary' }}">Constancias de aptitud, licencias y certificados firmados digitalmente con validación QR.</p>
        </div>
        <div>
            <a href="{{ route('empresa.certificados.create') }}" class="btn py-2 px-4 rounded-pill fw-bold text-white shadow-sm border-0" style="background: linear-gradient(135deg, var(--color-primario), #10b981);">
                <i class="bi bi-plus-circle me-2"></i> Emitir Certificado Oficial
            </a>
        </div>
    </div>

    {{-- ALERTA --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4" role="alert" style="background: {{ $modoOscuro ? 'rgba(16, 185, 129, 0.15)' : '#ecfdf5' }}; color: #10b981;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- BUSCADOR --}}
    <div class="card premium-form-card mb-4">
        <div class="card-body p-3">
            <form action="{{ route('empresa.certificados.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-9 col-sm-8">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-0 text-muted pe-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" value="{{ $search }}" class="form-control premium-input shadow-none w-100" placeholder="Buscar por paciente, DNI, o código de verificación QR...">
                    </div>
                </div>
                <div class="col-md-3 col-sm-4">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-pill shadow-sm">
                        Buscar Certificados
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLA DE CERTIFICADOS --}}
    <div class="card premium-form-card overflow-hidden mb-5">
        <div class="card-body p-0">
            @if($certificates->isEmpty())
                <div class="text-center py-5 my-4 text-muted">
                    <i class="bi bi-patch-check display-3 opacity-25 d-block mb-3"></i>
                    No se encontraron certificados médicos oficiales emitidos en este periodo.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0" style="background: transparent;">
                        <thead>
                            <tr class="border-bottom">
                                <th class="px-4 py-3 small fw-bold">Paciente</th>
                                <th class="py-3 small fw-bold">Tipo</th>
                                <th class="py-3 small fw-bold">Médico Firmante</th>
                                <th class="py-3 small fw-bold">Código QR</th>
                                <th class="py-3 small fw-bold">Emisión</th>
                                <th class="py-3 small fw-bold">Vence</th>
                                <th class="py-3 small fw-bold text-center">Auditoría</th>
                                <th class="px-4 py-3 small fw-bold text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($certificates as $c)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold">{{ $c->patient?->name ?? 'Paciente Desconocido' }}</div>
                                        <small class="text-muted">DNI: {{ $c->patient?->document ?? 'Sin DNI' }}</small>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge px-3 py-2 fw-bold rounded-pill" style="background: {{ $modoOscuro ? 'rgba(59, 130, 246, 0.15)' : '#e0f2fe' }}; color: #3b82f6;">
                                            {{ $c->tipo }}
                                        </span>
                                    </td>
                                    <td class="py-3 font-semibold">
                                        {{ $c->doctor?->name ?? 'Médico Asignado' }}
                                        <div class="x-small text-muted">M.P. {{ $c->doctor?->profesionalConfig?->matricula ?? 'Pendiente' }}</div>
                                    </td>
                                    <td class="py-3">
                                        <code class="text-success fw-bold">{{ $c->codigo_validacion }}</code>
                                    </td>
                                    <td class="py-3 small text-muted">{{ $c->created_at->format('d/m/Y H:i') }} hs</td>
                                    <td class="py-3 small text-muted">{{ $c->vigencia_hasta ? $c->vigencia_hasta->format('d/m/Y') : 'Permanente' }}</td>
                                    <td class="py-3 text-center">
                                        <span class="badge px-3 py-2 fw-bold rounded-pill" style="background: {{ $modoOscuro ? 'rgba(16, 185, 129, 0.15)' : '#d1fae5' }}; color: #10b981;">
                                            <i class="bi bi-shield-check me-1"></i> Verificado QR
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('empresa.certificados.print', $c->id) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Imprimir Certificado con QR" style="border-radius: 8px;">
                                                <i class="bi bi-printer-fill me-1"></i> Imprimir
                                            </a>
                                            <a href="{{ route('certificados.public-validate', $c->codigo_validacion) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Verificar Página Pública QR" style="border-radius: 8px;">
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
                <div class="card-footer bg-transparent py-3 border-0">
                    {{ $certificates->appends(['search' => $search])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
