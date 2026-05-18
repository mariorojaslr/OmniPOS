@extends('layouts.empresa')

@section('content')
<div class="container py-4" style="max-width: 760px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Nuevo Usuario</h2>
            <p class="text-muted small mb-0">Cree un nuevo miembro del equipo para esta empresa.</p>
        </div>
        <a href="{{ route('empresa.usuarios.index') }}" class="btn btn-light border fw-bold shadow-sm px-4">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4">
            <i class="bi bi-exclamation-octagon-fill fs-4 me-3"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('empresa.usuarios.store') }}">
        @csrf

        <div class="card border-0 shadow-sm bg-white overflow-hidden">

            {{-- DATOS BÁSICOS --}}
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0 text-dark text-uppercase">
                    <i class="bi bi-person-fill me-2 opacity-50"></i> Datos del Usuario
                </h6>
            </div>
            <div class="card-body p-4">

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-1">Nombre completo <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="Ej: Juan Pérez" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-1">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="usuario@empresa.com" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-1">Contraseña</label>
                        <input type="text" name="password" class="form-control" placeholder="Si se deja vacío → se genera automática">
                        <small class="text-muted">Mínimo 6 caracteres. Si no escribe, se genera una aleatoria.</small>
                    </div>
                </div>

                <hr class="my-4">

                {{-- NIVEL DE ACCESO --}}
                <h6 class="fw-bold text-uppercase text-muted mb-3 small">
                    <i class="bi bi-shield-lock me-1"></i> Nivel de Acceso y Perfil
                </h6>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-1">Nivel de Acceso <span class="text-danger">*</span></label>
                        <select name="role" id="roleSelect" class="form-select" onchange="toggleSubRole()">
                            <option value="usuario" {{ old('role') == 'usuario' ? 'selected' : '' }}>
                                👤 Personal Médico & Asistencial (Consultorios, Guardia, Recepción)
                            </option>
                            <option value="empresa" {{ old('role') == 'empresa' ? 'selected' : '' }}>
                                🛡️ Director Médico / Administrador (Acceso Total)
                            </option>
                        </select>
                        <small class="text-muted">Los directores médicos pueden ver reportes globales, liquidaciones y gestionar todo el personal.</small>
                    </div>

                    <div class="col-md-6" id="subRoleContainer">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-1">Especialidad / Rol del Personal</label>
                        <select name="sub_role" class="form-select">
                            <option value="cajero" {{ old('sub_role') == 'cajero' ? 'selected' : '' }}>
                                🩺 Profesional Médico (Médicos, Especialistas, Odontólogos)
                            </option>
                            <option value="operativo" {{ old('sub_role') == 'operativo' ? 'selected' : '' }}>
                                🏥 Personal de Enfermería (Portal de Enfermería y Triaje)
                            </option>
                            <option value="empleado" {{ old('sub_role') == 'empleado' ? 'selected' : '' }}>
                                🏢 Personal de Recepción (Admisión, Cajas y Llamado de Sala)
                            </option>
                        </select>
                        <small class="text-muted">Define qué herramientas asistenciales y paneles verá en su día a día.</small>
                    </div>
                </div>

                <hr class="my-4">

                {{-- FACULTADES ESPECIALES --}}
                <h6 class="fw-bold text-uppercase text-muted mb-3 small">
                    <i class="bi bi-toggles me-1"></i> Facultades y Permisos Médicos
                </h6>

                <div class="bg-light rounded-3 p-4">

                    <div class="form-check form-switch mb-3 d-flex align-items-start gap-3">
                        <input class="form-check-input mt-1" type="checkbox" name="can_register_expenses"
                               id="canRegisterExpenses" value="1" {{ old('can_register_expenses') ? 'checked' : '' }}
                               style="transform: scale(1.3);">
                        <div>
                            <label class="form-check-label fw-bold text-dark" for="canRegisterExpenses">
                                🔐 Habilitar historial clínico digital (HCE) & recetas
                            </label>
                            <div class="text-muted small">Permite registrar evoluciones médicas, emitir recetas electrónicas y firmar certificados con validación QR.</div>
                        </div>
                    </div>

                    <div class="form-check form-switch mb-3 d-flex align-items-start gap-3">
                        <input class="form-check-input mt-1" type="checkbox" name="can_manage_purchases"
                               id="canManagePurchases" value="1" {{ old('can_manage_purchases') ? 'checked' : '' }}
                               style="transform: scale(1.3);">
                        <div>
                            <label class="form-check-label fw-bold text-dark" for="canManagePurchases">
                                🏥 Habilitar gestión de internación & mapa de camas
                            </label>
                            <div class="text-muted small">Habilita el acceso para asignar pacientes a salas de internación, camas calientes/frías y el dossier de enfermería.</div>
                        </div>
                    </div>

                    <div class="form-check form-switch mb-0 d-flex align-items-start gap-3">
                        <input class="form-check-input mt-1" type="checkbox" name="can_sell"
                               id="canSell" value="1" {{ old('can_sell', '1') ? 'checked' : '' }}
                               style="transform: scale(1.3);">
                        <div>
                            <label class="form-check-label fw-bold text-dark" for="canSell">
                                💰 Habilitar facultad de cobro de co-seguros & copagos
                            </label>
                            <div class="text-muted small">Faculta al usuario para cobrar copagos de obras sociales, emitir comprobantes de pago y auditoría de cajas.</div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3 px-4">
                <a href="{{ route('empresa.usuarios.index') }}" class="btn btn-light border fw-bold px-4">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary fw-bold shadow-sm px-5 py-2" style="font-size: 1rem;">
                    <i class="bi bi-person-plus-fill me-2"></i> CREAR USUARIO
                </button>
            </div>

        </div>

    </form>

</div>

<script>
function toggleSubRole() {
    const role = document.getElementById('roleSelect').value;
    const container = document.getElementById('subRoleContainer');
    container.style.opacity = (role === 'empresa') ? '0.4' : '1';
    container.querySelector('select').disabled = (role === 'empresa');
}
// Inicializar al cargar
toggleSubRole();
</script>

@endsection
