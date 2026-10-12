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
                    {{-- PERFIL CLÍNICO Y ESPECIALIDAD (SWITCHABLE) --}}
                    @php
                        $cfg = $empresa->config;
                        $tipoClinicaActual = $cfg?->tipo_clinica ?? 'clinica_general';
                        $isOdontoActual = $cfg?->isOdontologica() ?? false;
                    @endphp
                    <hr class="my-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-hospital me-2 text-primary"></i>Perfil y Especialidad de la Clínica (Switchable)</h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill fw-bold">SaaS Multi-Especialidad</span>
                    </div>

                    <div class="card border-primary border-opacity-25 bg-light p-3 rounded-4 mb-4">
                        <div class="row g-3">
                            <div class="col-md-7 mb-2">
                                <label class="form-label fw-bold">Especialidad Principal del Centro Médico</label>
                                <select name="tipo_clinica" id="owner_tipo_clinica" class="form-select border-primary fw-semibold shadow-sm" onchange="actualizarPerfilClinicoOwner(this.value)">
                                    <option value="clinica_general" {{ $tipoClinicaActual === 'clinica_general' ? 'selected' : '' }}>🩺 Clínica Médica General / Policonsultorios</option>
                                    <option value="clinica_odontologica" {{ $tipoClinicaActual === 'clinica_odontologica' ? 'selected' : '' }}>🦷 Clínica Odontológica / Centro Dental</option>
                                    <option value="unidad_coronaria" {{ $tipoClinicaActual === 'unidad_coronaria' ? 'selected' : '' }}>❤️ Unidad Coronaria / Cardiología</option>
                                    <option value="centro_rehabilitacion" {{ $tipoClinicaActual === 'centro_rehabilitacion' ? 'selected' : '' }}>🏃‍♂️ Centro de Rehabilitación / Kinesiología</option>
                                </select>
                                <small class="text-muted d-block mt-1">Configura automáticamente las herramientas de la plataforma según la especialidad del cliente.</small>
                            </div>

                            <div class="col-md-5 mb-2">
                                <label class="form-label fw-bold">Denominación de Espacios</label>
                                <select name="terminologia_espacio" id="owner_terminologia_espacio" class="form-select shadow-sm">
                                    <option value="consultorio" {{ ($cfg?->terminologia_espacio ?? 'consultorio') === 'consultorio' ? 'selected' : '' }}>Consultorio Dental / Médico</option>
                                    <option value="sillon" {{ ($cfg?->terminologia_espacio ?? '') === 'sillon' ? 'selected' : '' }}>Sillón Odontológico</option>
                                    <option value="box" {{ ($cfg?->terminologia_espacio ?? '') === 'box' ? 'selected' : '' }}>Box de Atención</option>
                                    <option value="sala" {{ ($cfg?->terminologia_espacio ?? '') === 'sala' ? 'selected' : '' }}>Sala de Tratamiento</option>
                                </select>
                            </div>

                            <div class="col-12"><hr class="my-1 border-secondary border-opacity-25"></div>

                            {{-- SWITCH ODONTOGRAMA --}}
                            <div class="col-md-6">
                                <div class="form-check form-switch p-2 ps-5 rounded-3 bg-white border shadow-sm">
                                    <input class="form-check-input ms-n4" type="checkbox" name="mod_odontograma" id="owner_mod_odontograma" value="1" {{ ($cfg?->mod_odontograma || $tipoClinicaActual === 'clinica_odontologica') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-dark" for="owner_mod_odontograma">
                                        🦷 Odontograma Interactivo (HCE)
                                    </label>
                                    <small class="text-muted d-block">Habilita el odontograma digital interactivo con caras y piezas dentales en las historias clínicas.</small>
                                </div>
                            </div>

                            {{-- SWITCH INTERNACIÓN --}}
                            <div class="col-md-6" id="contenedor_internacion">
                                <div class="form-check form-switch p-2 ps-5 rounded-3 bg-white border shadow-sm">
                                    <input class="form-check-input ms-n4" type="checkbox" name="mod_internaciones" id="owner_mod_internaciones" value="1" {{ ($cfg?->mod_internaciones ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-dark" for="owner_mod_internaciones">
                                        🏥 Módulo de Internación (Camas/Habitaciones)
                                    </label>
                                    <small class="text-muted d-block" id="texto_aviso_internacion">Control de camas y hospitalización. (No recomendado en odontología).</small>
                                </div>
                            </div>

                            {{-- SWITCH HCE Y TURNOS --}}
                            <div class="col-md-6">
                                <div class="form-check form-switch p-2 ps-5 rounded-3 bg-white border shadow-sm">
                                    <input class="form-check-input ms-n4" type="checkbox" name="mod_hce" id="owner_mod_hce" value="1" {{ ($cfg?->mod_hce ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-dark" for="owner_mod_hce">
                                        🩺 Historia Clínica Electrónica (HCE)
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch p-2 ps-5 rounded-3 bg-white border shadow-sm">
                                    <input class="form-check-input ms-n4" type="checkbox" name="mod_turnos" id="owner_mod_turnos" value="1" {{ ($cfg?->mod_turnos ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-dark" for="owner_mod_turnos">
                                        📅 Turnos & Agenda Web Online
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        function actualizarPerfilClinicoOwner(tipo) {
                            const switchOdonto = document.getElementById('owner_mod_odontograma');
                            const switchInternacion = document.getElementById('owner_mod_internaciones');
                            const selectEspacio = document.getElementById('owner_terminologia_espacio');
                            const avisoInternacion = document.getElementById('texto_aviso_internacion');

                            if (tipo === 'clinica_odontologica') {
                                switchOdonto.checked = true;
                                switchInternacion.checked = false; // Desactivar internación
                                avisoInternacion.innerText = '⚠️ Desactivado por defecto: las clínicas dentales no disponen de internación hospitalaria.';
                                avisoInternacion.classList.add('text-warning');
                                selectEspacio.value = 'consultorio';
                            } else {
                                if (tipo !== 'clinica_odontologica') {
                                    switchOdonto.checked = false;
                                }
                                avisoInternacion.innerText = 'Control de camas y hospitalización médica.';
                                avisoInternacion.classList.remove('text-warning');
                            }
                        }
                    </script>
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
