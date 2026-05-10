@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="text-white fw-bold">Gestión de Facturación Central</h2>
            <a href="{{ route('owner.facturacion.create') }}" class="btn btn-primary px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i> Registrar Pago Manual
            </a>
        </div>
    </div>

    <!-- KPIs Superiores -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-dark border-secondary shadow-lg">
                <div class="card-body">
                    <h6 class="text-secondary text-uppercase small fw-bold">Empresas Activas</h6>
                    <h3 class="text-white mb-0">{{ $empresas->where('activo', true)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-dark border-secondary shadow-lg">
                <div class="card-body">
                    <h6 class="text-secondary text-uppercase small fw-bold">Vencidas / Morosas</h6>
                    <h3 class="text-danger mb-0">{{ $empresas->where('dias_para_vencer', '<', 0)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-dark border-secondary shadow-lg">
                <div class="card-body">
                    <h6 class="text-secondary text-uppercase small fw-bold">Por vencer (7 días)</h6>
                    <h3 class="text-warning mb-0">{{ $empresas->whereBetween('dias_para_vencer', [0, 7])->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-dark border-secondary shadow-lg">
                <div class="card-body">
                    <h6 class="text-secondary text-uppercase small fw-bold">Recaudación Total</h6>
                    <h3 class="text-success mb-0">${{ number_format($empresas->sum('monto_total_pagado'), 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Listado por Empresa (Acordeón) -->
    <div class="card bg-dark border-secondary shadow-lg">
        <div class="card-header bg-transparent border-secondary py-3">
            <h5 class="text-white mb-0"><i class="fas fa-building me-2"></i> Estado de Clientes SaaS</h5>
        </div>
        <div class="card-body p-0">
            <div class="accordion accordion-flush" id="accordionFacturacion">
                @foreach($empresas as $empresa)
                <div class="accordion-item bg-transparent border-bottom border-secondary">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed bg-transparent text-white py-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $empresa->id }}">
                            <div class="d-flex w-100 justify-content-between align-items-center pe-4">
                                <div>
                                    <span class="fw-bold h5 mb-0">{{ $empresa->nombre_comercial }}</span>
                                    <div class="small text-secondary mt-1">
                                        Plan: {{ $empresa->plan->name ?? 'N/A' }} | 
                                        Cuit: {{ $empresa->cuit ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    @if($empresa->dias_para_vencer < 0)
                                        <span class="badge bg-danger">VENCIDA (hace {{ $empresa->vencimiento_human }})</span>
                                    @elseif($empresa->dias_para_vencer <= 7)
                                        <span class="badge bg-warning text-dark">PRÓXIMO VENCIMIENTO (en {{ $empresa->vencimiento_human }})</span>
                                    @else
                                        <span class="badge bg-success">AL DÍA (faltan {{ $empresa->vencimiento_human }})</span>
                                    @endif
                                    <div class="mt-2 fw-bold text-white">
                                        Total Pagado: ${{ number_format($empresa->monto_total_pagado, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="collapse{{ $empresa->id }}" class="accordion-collapse collapse" data-bs-parent="#accordionFacturacion">
                        <div class="accordion-body bg-black bg-opacity-25 py-4">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="text-primary text-uppercase small fw-bold mb-3">Acciones de Gestión</h6>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('owner.notifications.send') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
                                            <input type="hidden" name="title" value="Aviso de Vencimiento de Suscripción">
                                            <input type="hidden" name="type" value="vencimiento">
                                            <input type="hidden" name="message" value="Tu suscripción a MultiPOS está próxima a vencer. Por favor, regulariza tu situación para evitar interrupciones en el servicio.">
                                            <button type="submit" class="btn btn-outline-warning btn-sm">
                                                <i class="fas fa-bell me-1"></i> Notificar Vencimiento
                                            </button>
                                        </form>
                                        <a href="{{ route('owner.empresas.edit', $empresa->id) }}" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-edit me-1"></i> Editar Empresa
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <h6 class="text-secondary text-uppercase small fw-bold mb-1">Última Notificación Enviada</h6>
                                    <p class="text-white mb-0">
                                        {{ $empresa->ultima_notificacion_vencimiento ? $empresa->ultima_notificacion_vencimiento->format('d/m/Y H:i') : 'Nunca enviada' }}
                                    </p>
                                </div>
                            </div>

                            <h6 class="text-white fw-bold mb-3">Historial de Pagos</h6>
                            <div class="table-responsive">
                                <table class="table table-dark table-hover border-secondary">
                                    <thead class="text-secondary">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Plan</th>
                                            <th>Método</th>
                                            <th>Monto</th>
                                            <th>Comprobante</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($empresa->pagos as $pago)
                                        <tr>
                                            <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                                            <td><span class="badge bg-secondary">{{ $pago->plan->name ?? 'Básico' }}</span></td>
                                            <td>{{ $pago->metodo }}</td>
                                            <td class="fw-bold text-success">${{ number_format($pago->monto, 0, ',', '.') }}</td>
                                            <td>{{ $pago->nro_comprobante ?: '-' }}</td>
                                            <td>
                                                <span class="badge rounded-pill {{ $pago->estado === 'aprobado' ? 'bg-success bg-opacity-25 text-success border border-success' : 'bg-warning bg-opacity-25 text-warning border border-warning' }}">
                                                    {{ strtoupper($pago->estado) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-secondary">
                                                <i class="fas fa-info-circle me-2"></i> No se registran pagos históricos para esta empresa.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .accordion-button::after {
        filter: invert(1);
    }
    .accordion-button:not(.collapsed) {
        background-color: rgba(255, 255, 255, 0.05) !important;
        box-shadow: none;
    }
    .badge {
        font-weight: 600;
        letter-spacing: 0.5px;
    }
</style>
@endsection
