@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h4 class="fw-bold mb-0">Registrar Pago Manual</h4>
        <small class="text-muted">Extiende la suscripción de una empresa acreditando un pago</small>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <form method="POST" action="{{ route('owner.facturacion.store') }}">
                @csrf

                <div class="row g-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Empresa *</label>
                        <select name="empresa_id" class="form-select rounded-3" required id="empresaSelect">
                            <option value="">Seleccione una empresa</option>
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}" data-plan="{{ $empresa->plan ? $empresa->plan->price : 0 }}">
                                    {{ $empresa->nombre_comercial }} (Vencimiento: {{ $empresa->fecha_vencimiento ? \Carbon\Carbon::parse($empresa->fecha_vencimiento)->format('d/m/Y') : 'Nunca' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Fecha de Pago *</label>
                        <input type="date" name="fecha_pago" class="form-control rounded-3" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Monto ($) *</label>
                        <input type="number" step="0.01" name="monto" id="montoInput" class="form-control rounded-3" value="0.00" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Método *</label>
                        <select name="metodo" class="form-select rounded-3" required>
                            <option value="transferencia">Transferencia Bancaria</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="bonificacion">Bonificación / Cortesia</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Estado del Pago *</label>
                        <select name="estado" class="form-select rounded-3" required>
                            <option value="aprobado">Aprobado (Acreditar Mes)</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="rechazado">Rechazado</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Comprobante (Opcional)</label>
                    <input type="text" name="nro_comprobante" class="form-control rounded-3" placeholder="Ej: TRF-4819283">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Notas RemitoInterno</label>
                    <textarea name="notas" class="form-control rounded-3" rows="3" placeholder="Detalles extra del pago o bonificación..."></textarea>
                </div>

                <div class="alert alert-info border-info d-flex align-items-center rounded-3 bg-info-subtle text-info-emphasis">
                    <i class="me-3 fs-3" style="font-style: normal;">💡</i>
                    <div>
                        Registrar un pago <strong>Aprobado</strong> extenderá la suscripción de la empresa en <strong>30 días</strong> desde su fecha de vencimiento actual. Si la empresa estaba suspendida, volverá al estado <strong>Activa</strong>.
                    </div>
                </div>

                <div class="mt-4 d-flex gap-3">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        Registrar y Acreditar
                    </button>
                    <a href="{{ route('owner.facturacion.index') }}" class="btn btn-light border rounded-pill px-4 text-secondary">
                        Volver
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const empresaSelect = document.getElementById('empresaSelect');
        const montoInput = document.getElementById('montoInput');
        
        empresaSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                montoInput.value = selectedOption.getAttribute('data-plan') || "0.00";
            } else {
                montoInput.value = "0.00";
            }
        });
    });
</script>
@endsection
