@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-6">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">
                Editar empresa
            </div>

            <div class="card-body">
                <form method="POST"
                      action="{{ url('owner/empresas/' . $empresa->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nombre comercial</label>
                        <input type="text"
                               name="nombre_comercial"
                               class="form-control"
                               value="{{ $empresa->nombre_comercial }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Razón social</label>
                        <input type="text"
                               name="razon_social"
                               class="form-control"
                               value="{{ $empresa->razon_social }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ $empresa->email }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text"
                               name="telefono"
                               class="form-control"
                               value="{{ $empresa->telefono }}">
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold text-primary">Plan de Suscripción</label>
                            <select name="plan_id" class="form-select border-primary bg-primary bg-opacity-10 text-primary fw-semibold">
                                <option value="">Sin Plan asignado</option>
                                @foreach($planes as $plan)
                                    <option value="{{ $plan->id }}" {{ $empresa->plan_id == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name ?? $plan->nombre }} ({{ env('APP_CURRENCY', '$') }}{{ number_format((float)($plan->price ?? $plan->precio ?? 0), 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Estado del Servicio</label>
                            <select name="status" class="form-select">
                                <option value="activa" {{ $empresa->status == 'activa' ? 'selected' : '' }}>🟢 Activa (Normal)</option>
                                <option value="mora" {{ $empresa->status == 'mora' ? 'selected' : '' }}>🟡 En Mora (Deuda)</option>
                                <option value="suspendida" {{ $empresa->status == 'suspendida' ? 'selected' : '' }}>🔴 Suspendida (Cortado)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Fecha de corte</label>
                            <input type="date"
                                   title="Día en el que el sistema automático le suspenderá la cuenta si no paga."
                                   name="fecha_vencimiento"
                                   class="form-control"
                                   value="{{ optional($empresa->fecha_vencimiento)->format('Y-m-d') }}">
                        </div>
                    </div>

                    {{-- ACUERDOS ESPECIALES --}}
                    <hr class="my-4">
                    <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-gift me-2"></i>Acuerdos Especiales / Trato de Amigo</h6>
                    <div class="row bg-light p-3 rounded-3 mb-4 mx-0 border">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Precio Personalizado (Mensual)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="custom_price" class="form-control" value="{{ $empresa->custom_price }}" placeholder="Ej: 40000">
                            </div>
                            <small class="text-muted">Vacío = Usa precio de plan (${{ number_format($empresa->plan?->price ?? 0, 0, ',', '.') }})</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Límite de Artículos</label>
                            <input type="number" name="custom_max_products" class="form-control" value="{{ $empresa->custom_max_products }}" placeholder="Ej: 1000">
                            <small class="text-muted">Vacío = Usa límite de plan ({{ $empresa->plan?->max_products ?? '∞' }})</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fin de Período de Gracia</label>
                            <input type="date" name="grace_period_until" class="form-control" value="{{ optional($empresa->grace_period_until)->format('Y-m-d') }}">
                            <small class="text-muted d-block mt-1">Fecha hasta la cual la empresa usa el sistema gratis en modo prueba.</small>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_bonificated" id="is_bonificated" value="1" {{ $empresa->is_bonificated ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-success" for="is_bonificated">Empresa BONIFICADA (Cortesía)</label>
                            </div>
                            <small class="text-muted">Si se activa, el sistema NUNCA la bloqueará por falta de pago.</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('owner.empresas.index') }}"
                           class="btn btn-outline-secondary">
                            Cancelar
                        </a>

                        <button class="btn btn-primary">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
