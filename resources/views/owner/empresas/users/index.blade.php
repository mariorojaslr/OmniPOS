@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Usuarios de {{ $empresa->nombre_comercial }}</h4>

        <a href="{{ url('owner/empresas/' . $empresa->id . '/users/create') }}"
           class="btn btn-primary">
            + Nuevo usuario
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="ps-4">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-primary text-capitalize">
                                {{ $user->role === 'empresa' ? 'Administrador' : ($user->role === 'usuario' ? 'Empleado/Cajero' : $user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $user->activo ? 'bg-success' : 'bg-secondary' }}">
                                {{ $user->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-end pe-4">

                            <form method="POST"
                                  action="{{ url('owner/empresas/' . $empresa->id . '/users/' . $user->id . '/toggle') }}"
                                  class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-outline-warning">
                                    {{ $user->activo ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>

                            <form method="POST"
                                  action="{{ url('owner/empresas/' . $empresa->id . '/users/' . $user->id . '/reset-password') }}"
                                  class="d-inline ms-1">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-outline-danger">
                                    Reset pass
                                </button>
                            </form>

                            <a href="{{ url('owner/mimetizar/empresa/' . $empresa->id . '/usuario/' . $user->id) }}"
                               class="btn btn-sm btn-outline-info ms-1"
                               title="Entrar al sistema como este usuario">
                                Entrar
                            </a>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Sin usuarios
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>
@endsection
