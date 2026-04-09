@extends('layouts.empresa')

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
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Estado</th>
                    <th>Tipo</th> {{-- NUEVA COLUMNA --}}
                    <th width="360">Acciones</th>
                </tr>
            </thead>
            <tbody>

                @forelse($usuarios as $usuario)
                <tr>
                    <td class="fw-semibold">{{ $usuario->name }}</td>
                    <td>{{ $usuario->email }}</td>

                    {{-- =========================================
                    ESTADO
                    ========================================== --}}
                    <td>
                        @if($usuario->activo)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-secondary">Inactivo</span>
                        @endif
                    </td>

                    {{-- =========================================
                    TIPO DE USUARIO (NUEVO)
                    ========================================== --}}
                    <td>
                        @if($usuario->role === 'empresa')
                            <span class="badge bg-dark text-white fw-bold">
                                <i class="bi bi-shield-fill me-1"></i> Administrador
                            </span>
                        @elseif($usuario->role === 'usuario')
                            @php
                                $subRoleLabel = match($usuario->sub_role ?? 'cajero') {
                                    'cajero'    => '🖥️ Cajero',
                                    'operativo' => '🏭 Operativo',
                                    'empleado'  => '🏗️ Campo',
                                    default     => ucfirst($usuario->sub_role ?? 'cajero'),
                                };
                            @endphp
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary fw-bold">
                                {{ $subRoleLabel }}
                            </span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($usuario->role) }}</span>
                        @endif
                    </td>


                    {{-- =========================================
                    ACCIONES
                    ========================================== --}}
                    <td class="text-end pe-3">
                        <div class="d-flex justify-content-end align-items-center gap-1" style="white-space: nowrap;">
                            {{-- EDITAR (MODAL) --}}
                            <button type="button" class="btn btn-sm btn-dark fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $usuario->id }}">
                                <i class="bi bi-pencil-square"></i> Editar
                            </button>

                            {{-- ACTIVAR / DESACTIVAR --}}
                            <form method="POST" action="{{ route('empresa.usuarios.toggle', $usuario->id) }}" class="d-inline m-0">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-warning fw-bold">
                                    {{ $usuario->activo ? 'Apagar' : 'Prender' }}
                                </button>
                            </form>

                            {{-- RESET PASSWORD --}}
                            <form method="POST" action="{{ route('empresa.usuarios.reset', $usuario->id) }}" class="d-inline m-0">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-danger fw-bold">
                                    Reset
                                </button>
                            </form>

                            {{-- DESEMPEÑO --}}
                            <a href="{{ route('empresa.usuarios.desempeno', $usuario->id) }}" class="btn btn-sm btn-outline-info fw-bold">
                                Desempeño
                            </a>
                        </div>

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
                                                    <option value="usuario" {{ $usuario->role === 'usuario' ? 'selected' : '' }}>👤 Personal Operativo (POS, Caja, Fichaje)</option>
                                                    <option value="empresa" {{ $usuario->role === 'empresa' ? 'selected' : '' }}>🛡️ Administrador (Acceso total + Reportes)</option>
                                                </select>
                                                <small class="text-muted small d-block mt-1">Los administradores pueden ver reportes financieros y gestionar usuarios.</small>
                                            </div>

                                            <div class="mb-4" id="subRoleContainerEdit{{ $usuario->id }}">
                                                <label class="form-label fw-bold small text-info">Especialidad del Personal</label>
                                                <select name="sub_role" class="form-select border-2">
                                                    <option value="cajero" {{ $usuario->sub_role === 'cajero' ? 'selected' : '' }}>🖥️ Cajero (Usa POS y maneja Caja)</option>
                                                    <option value="operativo" {{ $usuario->sub_role === 'operativo' ? 'selected' : '' }}>🏭 Operativo (Fábrica / Taller / Depósito)</option>
                                                    <option value="empleado" {{ $usuario->sub_role === 'empleado' ? 'selected' : '' }}>🏗️ Empleado de Campo (Solo fichaje)</option>
                                                </select>
                                                <small class="text-muted small d-block mt-1">Define qué funciones verá el usuario en su panel diario.</small>
                                            </div>

                                            <div class="bg-light p-3 rounded-3">
                                                <h6 class="fw-bold mb-3 small text-uppercase text-dark ls-1">Facultades Especiales (Delegar Poder)</h6>

                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" name="can_register_expenses" id="flexSwitchExpenses{{ $usuario->id }}" {{ $usuario->can_register_expenses ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold text-dark" for="flexSwitchExpenses{{ $usuario->id }}">🔓 Habilitar registro de gastos en campo</label>
                                                    <small class="text-muted d-block small">Si se activa, el usuario tendrá acceso a la "App" rápida de gastos desde su celular.</small>
                                                </div>

                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" name="can_manage_purchases" id="flexSwitchPurchases{{ $usuario->id }}" {{ $usuario->can_manage_purchases ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold text-dark" for="flexSwitchPurchases{{ $usuario->id }}">🛒 Habilitar gestión de compras</label>
                                                    <small class="text-muted d-block small">Permite al usuario ver el historial de compras y registrar nuevos ingresos.</small>
                                                </div>

                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" name="can_sell" id="flexSwitchSell{{ $usuario->id }}" {{ $usuario->can_sell ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold text-dark" for="flexSwitchSell{{ $usuario->id }}">💰 Habilitar facultad de cobro/venta</label>
                                                    <small class="text-muted d-block small">Facultad específica para operar y cerrar transacciones financieras.</small>
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

// Inicializar todos los modales al cargar
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[id^="roleSelectEdit"]').forEach(function(sel) {
        const userId = sel.id.replace('roleSelectEdit', '');
        toggleSubRoleEdit(userId);
    });
});
</script>
@endsection
