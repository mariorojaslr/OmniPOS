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
                        @if($usuario->role === 'usuario')
                            <span class="badge bg-primary-subtle text-primary border-primary">
                                {{ ucfirst($usuario->sub_role ?? 'cajero') }}
                            </span>
                        @else
                            <span class="badge bg-dark">{{ ucfirst($usuario->role) }}</span>
                        @endif
                    </td>


                    {{-- =========================================
                    ACCIONES
                    ========================================== --}}
                    <td>

                        {{-- ACTIVAR / DESACTIVAR --}}
                        <form method="POST"
                              action="{{ route('empresa.usuarios.toggle', $usuario->id) }}"
                              class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm btn-outline-warning">
                                {{ $usuario->activo ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>

                        {{-- RESET PASSWORD --}}
                        <form method="POST"
                              action="{{ route('empresa.usuarios.reset', $usuario->id) }}"
                              class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm btn-outline-danger">
                                Reset pass
                            </button>
                        </form>

                        {{-- DESEMPEÑO --}}
                        <a href="{{ route('empresa.usuarios.desempeno', $usuario->id) }}"
                           class="btn btn-sm btn-outline-info">
                            Desempeño
                        </a>

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
