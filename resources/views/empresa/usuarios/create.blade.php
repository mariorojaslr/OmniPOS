@extends('layouts.empresa')

@section('content')

<h2 class="fw-bold mb-4">Nuevo usuario</h2>

@if($errors->any())
<div class="alert alert-danger">
    {{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('empresa.usuarios.store') }}">
    @csrf

    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- Nombre --}}
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            {{-- Nivel de Acceso --}}
            <div class="mb-4">
                <label class="form-label fw-bold text-primary">Nivel de Acceso</label>
                <select name="role" class="form-select border-primary shadow-sm" id="roleSelect" onchange="toggleSubRole()">
                    <option value="usuario" selected>Personal Operativo (Control de jornada y POS)</option>
                    <option value="empresa">Administrador (Acceso total y reportes)</option>
                </select>
                <div class="form-text">Los administradores pueden ver reportes fincancieros y gestionar a otros usuarios.</div>
            </div>

            {{-- Tipo de Usuario / Responsabilidad (Solo para Personal Operativo) --}}
            <div id="subRoleContainer" class="mb-3">
                <label class="form-label fw-bold">Especialidad del Personal</label>
                <select name="sub_role" class="form-select border-info">
                    <option value="cajero">Cajero (Usa POS y maneja Caja)</option>
                    <option value="empleado">Empleado de Campo / Obra (Solo ficha Asistencia)</option>
                </select>
                <div class="form-text">Define qué funciones verá el usuario en su panel diario.</div>
            </div>

            <script>
                function toggleSubRole() {
                    const role = document.getElementById('roleSelect').value;
                    const container = document.getElementById('subRoleContainer');
                    if (role === 'empresa') {
                        container.style.display = 'none';
                    } else {
                        container.style.display = 'block';
                    }
                }
            </script>

            {{-- Password opcional --}}
            <div class="mb-3">
                <label class="form-label">Contraseña (opcional)</label>
                <input type="text" name="password" class="form-control"
                       placeholder="Si se deja vacío → se genera automática">
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('empresa.usuarios.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>

                <button class="btn btn-primary">
                    Crear usuario
                </button>
            </div>

        </div>
    </div>
</form>

@endsection
