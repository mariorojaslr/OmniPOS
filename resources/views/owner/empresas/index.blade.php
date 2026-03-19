@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- =========================================================
        Encabezado + acción principal
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Empresas</h3>

        <a href="{{ route('owner.empresas.create') }}"
           class="btn btn-primary">
            + Nueva empresa
        </a>
    </div>

    {{-- =========================================================
        Tarjeta contenedora
    ========================================================== --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            {{-- =====================================================
                Tabla de empresas
            ====================================================== --}}
            <table class="table table-hover mb-0 align-middle">

                {{-- Cabecera --}}
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Plan</th>
                        <th>Vencimiento</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>

                {{-- Cuerpo --}}
                <tbody>
                @forelse ($empresas as $empresa)

                    @php
                        // Estado centralizado desde el modelo Empresa
                        // Activa | Vencida | Inactiva
                        $estado = $empresa->estadoLabel();
                    @endphp

                    <tr>
                        {{-- ID --}}
                        <td class="ps-4">
                            <span class="badge bg-light text-dark">#{{ $empresa->id }}</span>
                        </td>

                        {{-- Nombre comercial --}}
                        <td class="fw-semibold">
                            {{ $empresa->nombre_comercial }}
                        </td>

                        {{-- Email --}}
                        <td>
                            {{ $empresa->email ?? '—' }}
                        </td>

                        {{-- Plan --}}
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ $empresa->plan->name ?? '-' }}
                            </span>
                        </td>

                        {{-- Fecha de vencimiento --}}
                        <td>
                            {{ $empresa->fecha_vencimiento
                                ? $empresa->fecha_vencimiento->format('d/m/Y')
                                : 'Sin definir' }}
                        </td>

                        {{-- Estado visual --}}
                        <td>
                            <span class="badge
                                {{ $estado === 'Activa'
                                    ? 'bg-success'
                                    : ($estado === 'Vencida'
                                        ? 'bg-warning text-dark'
                                        : 'bg-secondary') }}">
                                {{ $estado }}
                            </span>
                        </td>

                        {{-- Acciones --}}
                        <td class="text-end pe-4">

                            {{-- Usuarios de la empresa --}}
                            <a href="{{ route('owner.empresas.users.index', $empresa) }}"
                               class="btn btn-sm btn-outline-primary">
                                Usuarios
                            </a>

                            {{-- Editar empresa --}}
                            <a href="{{ route('owner.empresas.edit', $empresa) }}"
                               class="btn btn-sm btn-outline-secondary ms-1">
                                Editar
                            </a>

                            {{-- Renovar solo si está vencida o inactiva --}}
                            @if ($estado !== 'Activa')
                                <form method="POST"
                                      action="{{ route('owner.empresas.renovar', $empresa) }}"
                                      class="d-inline ms-1">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-success">
                                        Renovar
                                    </button>
                                </form>
                            @endif

                        </td>
                    </tr>

                @empty
                    {{-- Estado vacío --}}
                    <tr>
                        <td colspan="5"
                            class="text-center text-muted py-4">
                            No hay empresas creadas todavía
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>
@endsection
