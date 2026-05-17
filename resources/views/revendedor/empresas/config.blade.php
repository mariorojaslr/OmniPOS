@extends('layouts.reseller')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-800 text-white mb-0">Parametrización: {{ $empresa->nombre_comercial }}</h2>
            <p class="text-white opacity-50">Activa o desactiva las funciones contratadas por el cliente.</p>
        </div>
        <a href="{{ route('revendedor.dashboard') }}" class="btn btn-outline-info rounded-pill px-4 fw-bold">
            <i class="bi bi-arrow-left me-2"></i> Volver al Panel
        </a>
    </div>

    <form action="{{ route('revendedor.empresas.update_config', $empresa->slug) }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="glass-card p-5" style="background: #0f172a; border: 1px solid rgba(255,255,255,0.1);">
                    <h5 class="fw-bold mb-4 border-bottom border-white border-opacity-10 pb-3 text-info text-uppercase small" style="letter-spacing: 2px;">Módulos del Sistema</h5>
                    
                    <div class="row">
                        {{-- AFIP --}}
                        <div class="col-md-4 mb-4">
                            <div class="p-3 border border-secondary rounded-4" style="background: #1e293b;">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="mod_afip" id="mod_afip" {{ $config->mod_afip ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-white ms-2 small" for="mod_afip">
                                        Facturación AFIP
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Pagos --}}
                        <div class="col-md-4 mb-4">
                            <div class="p-3 border border-secondary rounded-4" style="background: #1e293b;">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="mod_pagos" id="mod_pagos" {{ $config->mod_pagos ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-white ms-2 small" for="mod_pagos">
                                        Métodos de Pago
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Backups --}}
                        <div class="col-md-4 mb-4">
                            <div class="p-3 border border-secondary rounded-4" style="background: #1e293b;">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="mod_backups" id="mod_backups" {{ $config->mod_backups ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-white ms-2 small" for="mod_backups">
                                        Resguardo Datos
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-3 mt-3">
                        {{-- Ventas --}}
                        <div class="d-flex justify-content-between align-items-center p-3 rounded-4 border border-white border-opacity-10" style="background: #1e293b;">
                            <div>
                                <h6 class="fw-bold mb-1 text-white">Módulo de Ventas / POS</h6>
                                <p class="small opacity-75 mb-0 text-white">Habilita el punto de venta y catálogo online.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" name="mod_ventas" {{ $config->mod_ventas ? 'checked' : '' }}>
                            </div>
                        </div>

                        {{-- Tesorería --}}
                        <div class="d-flex justify-content-between align-items-center p-3 rounded-4 border border-white border-opacity-10" style="background: #1e293b;">
                            <div>
                                <h6 class="fw-bold mb-1 text-white">Gestión de Tesorería (Bancos)</h6>
                                <p class="small opacity-75 mb-0 text-white">Habilita el manejo de cuentas bancarias y flujo de caja.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" name="mod_tesoreria" {{ $config->mod_tesoreria ? 'checked' : '' }}>
                            </div>
                        </div>

                        <hr class="border-white border-opacity-10 my-2">

                        {{-- Médico / Turnos --}}
                        <div class="d-flex justify-content-between align-items-center p-3 rounded-4 border border-info border-opacity-20" style="background: rgba(13, 202, 240, 0.05);">
                            <div>
                                <h6 class="fw-bold mb-1 text-info">Módulo Médico / Turnos</h6>
                                <p class="small opacity-75 mb-0 text-white">Habilita la agenda de turnos y liquidaciones.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" name="mod_turnos" {{ $config->mod_turnos ? 'checked' : '' }}>
                            </div>
                        </div>

                        {{-- Afiliados --}}
                        <div class="d-flex justify-content-between align-items-center p-3 rounded-4 border border-info border-opacity-20" style="background: rgba(13, 202, 240, 0.05);">
                            <div>
                                <h6 class="fw-bold mb-1 text-info">Gestión de Afiliados (Plan Medplus)</h6>
                                <p class="small opacity-75 mb-0 text-white">Para clínicas con plan de salud propio.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" name="mod_afiliados" {{ $config->mod_afiliados ? 'checked' : '' }}>
                            </div>
                        </div>

                        {{-- HCE --}}
                        <div class="d-flex justify-content-between align-items-center p-3 rounded-4 border border-info border-opacity-20" style="background: rgba(13, 202, 240, 0.05);">
                            <div>
                                <h6 class="fw-bold mb-1 text-info">Historia Clínica Electrónica (HCE)</h6>
                                <p class="small opacity-75 mb-0 text-white">Registro clínico detallado de atenciones.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" name="mod_hce" {{ $config->mod_hce ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="glass-card p-4 mb-4" style="background: #0f172a; border: 1px solid rgba(255,255,255,0.1);">
                    <h5 class="fw-bold mb-3 text-white small text-uppercase" style="letter-spacing: 1px;">Resumen del Paquete</h5>
                    <div class="p-3 rounded-4 border border-white border-opacity-10" style="background: #1e293b;">
                        <div class="small opacity-50 text-white">Empresa</div>
                        <div class="fw-bold mb-2 text-white fs-5">{{ $empresa->nombre_comercial }}</div>
                        
                        <div class="small opacity-50 text-white">Plan Base</div>
                        <div class="fw-bold mb-2 text-white">{{ $empresa->plan->name ?? 'Profesional' }} (Mínimo: ${{ number_format($empresa->plan->price ?? 0, 0) }})</div>

                        <div class="small opacity-50 text-white border-top border-white border-opacity-10 pt-2 mt-2">Precio Mensual Acordado ($)</div>
                        <input type="number" name="custom_price" class="form-control bg-dark text-white border-secondary mt-1 fw-bold text-success fs-5" value="{{ old('custom_price', $empresa->custom_price ?? ($empresa->plan->price ?? 0)) }}" min="{{ $empresa->plan->price ?? 0 }}" required>
                        <small class="text-muted" style="font-size: 0.65rem; color: #a1a1aa !important;">* Mínimo requerido por contrato: ${{ number_format($empresa->plan->price ?? 0, 0) }}</small>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary shadow-lg d-flex align-items-center justify-content-center gap-2" style="border-radius: 16px; height: 70px; border: none; background: linear-gradient(135deg, #0d6efd, #0043a8);">
                        <i class="bi bi-shield-check fs-3"></i>
                        <span class="fw-bold fs-5">GUARDAR CONFIGURACIÓN</span>
                    </button>
                    <p class="text-center small opacity-50 mt-3 text-white px-3">Los cambios se aplicarán instantáneamente.</p>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    body { background-color: #000 !important; }
    .glass-card {
        border-radius: 24px;
    }
    .form-check-input { cursor: pointer; }
    .form-check-input:checked {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
    }
</style>
@endsection
