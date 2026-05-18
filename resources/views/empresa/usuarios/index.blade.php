@extends('layouts.empresa')

@php
    $empresa = auth()->user()->empresa;
    $config = $empresa?->config;
@endphp

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Usuarios de la empresa</h2>
        <p class="text-muted small">Gestión integral de empleados y puntos de fichaje.</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('empresa.personal.asistencia.qr') }}" class="btn btn-dark shadow-sm border-2">
            📲 QR ASISTENCIA
        </a>
        <a href="{{ route('empresa.usuarios.create') }}" class="btn btn-primary shadow-sm">
            + Nuevo Usuario
        </a>
    </div>
</div>


{{-- ======================================================
FILTRO USUARIOS
====================================================== --}}
<div class="mb-3">

    <a href="{{ route('empresa.usuarios.index', ['estado'=>'activos']) }}"
       class="btn btn-sm {{ $estado=='activos' ? 'btn-primary' : 'btn-outline-primary' }}">
        Activos
    </a>

    <a href="{{ route('empresa.usuarios.index', ['estado'=>'inactivos']) }}"
       class="btn btn-sm {{ $estado=='inactivos' ? 'btn-secondary' : 'btn-outline-secondary' }}">
        Inactivos
    </a>

    <a href="{{ route('empresa.usuarios.index', ['estado'=>'todos']) }}"
       class="btn btn-sm {{ $estado=='todos' ? 'btn-dark' : 'btn-outline-dark' }}">
        Todos
    </a>

</div>

@if(session('ok'))
    <div class="alert alert-success">
        {{ session('ok') }}
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">

        <table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr class="x-small text-muted text-uppercase">
                    <th class="ps-4">Personal</th>
                    <th>Estado</th>
                    <th>Tipo / Rol</th>
                    <th class="text-center" width="80">Editar</th>
                    <th class="text-center" width="80">Switch</th>
                    <th class="text-center" width="80">Clave</th>
                    <th class="text-center" width="100">Reporte</th>
                    @if($config->mod_turnos)
                        <th class="text-center border-start bg-primary bg-opacity-10 text-primary" width="120">
                            <i class="bi bi-calendar-check me-1"></i> Turnos
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody>

                @forelse($usuarios as $usuario)
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold text-dark">{{ $usuario->name }}</div>
                        <div class="x-small text-muted">{{ $usuario->email }}</div>
                    </td>

                    <td>
                        @if($usuario->activo)
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">ACTIVO</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">OFF</span>
                        @endif
                    </td>

                    <td>
                        @if($usuario->role === 'empresa')
                            <span class="small fw-bold text-dark"><i class="bi bi-shield-lock me-1"></i> ADMIN / DIRECTOR</span>
                        @else
                            @php
                                $subRoleLabels = [
                                    'cajero' => '🩺 Profesional Médico',
                                    'operativo' => '🏥 Enfermería / Triaje',
                                    'empleado' => '🏢 Recepción / Admisión'
                                ];
                                $label = $subRoleLabels[$usuario->sub_role] ?? 'Personal Asistencial';
                            @endphp
                            <span class="small fw-bold text-primary text-uppercase">{{ $label }}</span>
                        @endif
                    </td>

                    {{-- COLUMNAS DE ACCIÓN INDIVIDUALES --}}
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-light border-0 text-dark" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $usuario->id }}" title="Editar Datos">
                            <i class="bi bi-pencil-square fs-6"></i>
                        </button>
                    </td>

                    <td class="text-center">
                        <form method="POST" action="{{ route('empresa.usuarios.toggle', $usuario->id) }}" class="d-inline m-0">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm {{ $usuario->activo ? 'text-warning' : 'text-success' }} border-0">
                                <i class="bi bi-power fs-5"></i>
                            </button>
                        </form>
                    </td>

                    <td class="text-center">
                        <form method="POST" action="{{ route('empresa.usuarios.reset', $usuario->id) }}" class="d-inline m-0">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm text-danger border-0" title="Resetear Contraseña">
                                <i class="bi bi-key-fill"></i>
                            </button>
                        </form>
                    </td>

                    <td class="text-center">
                        <a href="{{ route('empresa.usuarios.desempeno', $usuario->id) }}" class="btn btn-sm text-info border-0" title="Ver Desempeño">
                            <i class="bi bi-bar-chart-fill"></i>
                        </a>
                    </td>

                    {{-- SECCIÓN MODULAR DE TURNOS --}}
                    @if($config->mod_turnos)
                        <td class="text-center border-start bg-primary bg-opacity-10">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('empresa.usuarios.config-profesional.edit', $usuario->id) }}" 
                                   class="btn btn-sm btn-primary rounded-pill px-2 shadow-sm" title="Configurar Pagos">
                                    <i class="bi bi-wallet2"></i>
                                </a>
                                @if($usuario->profesionalConfig)
                                   <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2" 
                                       onclick="copiarLinkRapido('{{ url('/portal/profesional') }}/{{ $usuario->profesionalConfig->token_portal }}')" 
                                       title="Copiar Link del Portal">
                                       <i class="bi bi-link-45deg"></i>
                                   </button>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>

                        {{-- ========================================================
                           MODAL DE EDICIÓN DE FACULTADES Y DATOS
                           ======================================================== --}}
                        <div class="modal fade" id="modalEdit{{ $usuario->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg bg-white overflow-hidden" style="border-radius: 12px; color: #1e293b !important;">
                                    <div class="modal-header bg-dark text-white py-3 border-0">
                                        <h5 class="modal-title fw-bold">⚙️ Gestionar Personal: {{ $usuario->name }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('empresa.usuarios.update', $usuario->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body p-4 text-start">
                                            
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-primary">Nombre del Personal</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $usuario->name }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-primary">Correo Electrónico</label>
                                                    <input type="email" name="email" class="form-control" value="{{ $usuario->email }}" required>
                                                </div>
                                            </div>

                                            <hr class="my-4 opacity-10">

                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-dark">Nivel de Acceso</label>
                                                <select name="role" class="form-select border-2" required id="roleSelectEdit{{ $usuario->id }}" onchange="toggleSubRoleEdit({{ $usuario->id }})">
                                                    <option value="usuario" {{ $usuario->role === 'usuario' ? 'selected' : '' }}>👤 Personal Médico & Asistencial (Consultorios, Guardia, Recepción)</option>
                                                    <option value="empresa" {{ $usuario->role === 'empresa' ? 'selected' : '' }}>🛡️ Director Médico / Administrador (Acceso Total)</option>
                                                </select>
                                                <small class="text-muted small d-block mt-1">Los directores médicos pueden ver reportes globales, liquidaciones y gestionar todo el personal.</small>
                                            </div>

                                            <div class="mb-4" id="subRoleContainerEdit{{ $usuario->id }}">
                                                <label class="form-label fw-bold small text-info">Especialidad / Rol del Personal</label>
                                                <select name="sub_role" class="form-select border-2">
                                                    <option value="cajero" {{ $usuario->sub_role === 'cajero' ? 'selected' : '' }}>🩺 Profesional Médico (Médicos, Especialistas, Odontólogos)</option>
                                                    <option value="operativo" {{ $usuario->sub_role === 'operativo' ? 'selected' : '' }}>🏥 Personal de Enfermería (Portal de Enfermería y Triaje)</option>
                                                    <option value="empleado" {{ $usuario->sub_role === 'empleado' ? 'selected' : '' }}>🏢 Personal de Recepción (Admisión, Cajas y Llamado de Sala)</option>
                                                </select>
                                                <small class="text-muted small d-block mt-1">Define qué herramientas asistenciales y paneles verá en su día a día.</small>
                                            </div>

                                            <div class="bg-light p-3 rounded-3">
                                                <h6 class="fw-bold mb-3 small text-uppercase text-dark ls-1">Facultades y Permisos Médicos</h6>

                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" name="can_register_expenses" id="flexSwitchExpenses{{ $usuario->id }}" {{ $usuario->can_register_expenses ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold text-dark" for="flexSwitchExpenses{{ $usuario->id }}">🔐 Habilitar historial clínico digital (HCE) & recetas</label>
                                                    <small class="text-muted d-block small">Permite registrar evoluciones médicas, emitir recetas electrónicas y firmar certificados con validación QR.</small>
                                                </div>

                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" name="can_manage_purchases" id="flexSwitchPurchases{{ $usuario->id }}" {{ $usuario->can_manage_purchases ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold text-dark" for="flexSwitchPurchases{{ $usuario->id }}">🏥 Habilitar gestión de internación & mapa de camas</label>
                                                    <small class="text-muted d-block small">Habilita el acceso para asignar pacientes a salas de internación, camas calientes/frías y el dossier de enfermería.</small>
                                                </div>

                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" name="can_sell" id="flexSwitchSell{{ $usuario->id }}" {{ $usuario->can_sell ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold text-dark" for="flexSwitchSell{{ $usuario->id }}">💰 Habilitar facultad de cobro de co-seguros & copagos</label>
                                                    <small class="text-muted d-block small">Faculta al usuario para cobrar copagos de obras sociales, emitir comprobantes de pago y auditoría de cajas.</small>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer bg-light border-0">
                                            <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-dark px-4 fw-bold shadow">GUARDAR AJUSTES 💾</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        No hay usuarios cargados
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>

    </div>
</div>

@endsection

@section('scripts')
<script>
function toggleSubRoleEdit(userId) {
    const role = document.getElementById('roleSelectEdit' + userId).value;
    const container = document.getElementById('subRoleContainerEdit' + userId);
    if (container) {
        container.style.opacity = (role === 'empresa') ? '0.4' : '1';
        container.querySelector('select').disabled = (role === 'empresa');
    }
}

function copiarLinkRapido(url) {
    navigator.clipboard.writeText(url).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Link copiado',
            text: 'Ya puedes enviarlo por WhatsApp al profesional',
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    });
}

// Inicializar todos los modales al cargar
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[id^="roleSelectEdit"]').forEach(function(sel) {
        const userId = sel.id.replace('roleSelectEdit', '');
        toggleSubRoleEdit(userId);
    });
});
</script>
@endsection
