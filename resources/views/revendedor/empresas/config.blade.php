@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold text-white mb-0">Parametrización: {{ $empresa->nombre_comercial }}</h2>
            <p class="text-white opacity-50">Activa o desactiva las funciones contratadas por el cliente.</p>
        </div>
        <a href="{{ route('revendedor.dashboard') }}" class="btn btn-outline-light rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i> Volver al Panel
        </a>
    </div>

    <form action="{{ route('revendedor.empresas.update_config', $empresa->id) }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="glass-card p-5">
                    <h5 class="fw-bold mb-4 border-bottom border-white border-opacity-10 pb-3 text-primary">Módulos del Sistema</h5>
                    
                    <div class="row">
                        {{-- AFIP --}}
                        <div class="col-md-4 mb-4">
                            <div class="form-check form-switch p-3 border rounded">
                                <input class="form-check-input ms-0" type="checkbox" name="mod_afip" id="mod_afip" {{ $config->mod_afip ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold ms-2" for="mod_afip">
                                    <i class="bi bi-file-earmark-check text-primary me-1"></i> Facturación Electrónica (AFIP)
                                </label>
                            </div>
                        </div>

                        {{-- Pagos --}}
                        <div class="col-md-4 mb-4">
                            <div class="form-check form-switch p-3 border rounded">
                                <input class="form-check-input ms-0" type="checkbox" name="mod_pagos" id="mod_pagos" {{ $config->mod_pagos ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold ms-2" for="mod_pagos">
                                    <i class="bi bi-credit-card text-success me-1"></i> Métodos de Pago (Tarjetas/QR)
                                </label>
                            </div>
                        </div>

                        {{-- Backups --}}
                        <div class="col-md-4 mb-4">
                            <div class="form-check form-switch p-3 border rounded">
                                <input class="form-check-input ms-0" type="checkbox" name="mod_backups" id="mod_backups" {{ $config->mod_backups ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold ms-2" for="mod_backups">
                                    <i class="bi bi-cloud-arrow-up text-info me-1"></i> Resguardo de Datos (Backups)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-4">
                        {{-- Ventas --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1">Módulo de Ventas / POS</h6>
                                <p class="small opacity-50 mb-0">Habilita el punto de venta y catálogo online.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" name="mod_ventas" {{ $config->mod_ventas ? 'checked' : '' }}>
                            </div>
                        </div>

                        {{-- Tesorería --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1">Gestión de Tesorería (Bancos)</h6>
                                <p class="small opacity-50 mb-0">Habilita el manejo de cuentas bancarias y flujo de caja.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" name="mod_tesoreria" {{ $config->mod_tesoreria ? 'checked' : '' }}>
                            </div>
                        </div>

                        {{-- Logística --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1">Módulo de Logística / GPS</h6>
                                <p class="small opacity-50 mb-0">Control de rutas, entregas y rastreo.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" name="mod_logistica" {{ $config->mod_logistica ? 'checked' : '' }}>
                            </div>
                        </div>

                        {{-- Compras --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1">Gestión de Abasto / Compras</h6>
                                <p class="small opacity-50 mb-0">Control de stock y órdenes de compra a proveedores.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" name="mod_compras" {{ $config->mod_compras ? 'checked' : '' }}>
                            </div>
                        </div>

                        <hr class="border-white border-opacity-10 my-2">

                        {{-- Médico / Turnos --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1">Módulo Médico / Turnos</h6>
                                <p class="small opacity-50 mb-0">Habilita la agenda de turnos y liquidaciones.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" name="mod_turnos" {{ $config->mod_turnos ? 'checked' : '' }}>
                            </div>
                        </div>

                        {{-- Afiliados --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1">Gestión de Afiliados (Plan Medplus)</h6>
                                <p class="small opacity-50 mb-0">Para clínicas con plan de salud propio.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" name="mod_afiliados" {{ $config->mod_afiliados ? 'checked' : '' }}>
                            </div>
                        </div>

                        {{-- HCE --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1">Historia Clínica Electrónica (HCE)</h6>
                                <p class="small opacity-50 mb-0">Registro clínico detallado de atenciones.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" name="mod_hce" {{ $config->mod_hce ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="glass-card p-4 mb-4">
                    <h5 class="fw-bold mb-3">Resumen del Paquete</h5>
                    <div class="p-3 bg-white bg-opacity-5 rounded-4 border border-white border-opacity-10">
                        <div class="small opacity-50">Empresa</div>
                        <div class="fw-bold mb-2">{{ $empresa->nombre_comercial }}</div>
                        
                        <div class="small opacity-50">Plan Base</div>
                        <div class="fw-bold mb-2">{{ $empresa->plan->name ?? 'Profesional' }}</div>

                        <div class="small opacity-50">Precio Acordado</div>
                        <div class="fw-bold text-success">$ {{ number_format($empresa->custom_price ?? ($empresa->plan->price ?? 0), 0) }} / mes</div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold shadow" style="border-radius: 16px; height: 65px;">
                        <i class="bi bi-save me-2"></i> GUARDAR CONFIGURACIÓN
                    </button>
                    <p class="text-center small opacity-50 mt-3">Al guardar, los cambios se aplicarán instantáneamente al dashboard del cliente.</p>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 24px;
    color: white;
}
.form-check-input {
    cursor: pointer;
}
.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>
@endsection
