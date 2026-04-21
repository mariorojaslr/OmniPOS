@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">

    {{-- =========================================================
        BANNER DE CONFIRMACIÓN DE VERSIÓN (Si ves esto, el deploy funcionó)
    ========================================================== --}}
    <div class="alert alert-info border-0 shadow-sm mb-4 d-flex align-items-center" style="background: linear-gradient(45deg, #0d6efd, #0099ff); color: white;">
        <i class="fas fa-check-circle me-3 fs-4"></i>
        <div>
            <h6 class="mb-0 fw-bold text-white">✅ SISTEMA REPARADO: VERSIÓN 404-FIX v2.0 ACTIVA</h6>
            <small class="text-white-50">Rutas blindadas con findOrFail(ID). Auto-reparación activa.</small>
        </div>
    </div>

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
                        <th class="ps-4">Empresa</th>
                        <th>Plan</th>
                        <th class="text-center">Artículos</th>
                        <th class="text-center">Clientes</th>
                        <th class="text-center">Ventas</th>
                        <th class="text-center">Multimedia</th>
                        <th>Vencimiento</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>

                {{-- Cuerpo --}}
                <tbody>
                @forelse ($empresas as $empresa)

                    @php
                        $estado = $empresa->estadoLabel();
                        $limiteProductos = $empresa->getLimiteProductos();
                        $sobrepasado = $limiteProductos > 0 && $empresa->products_count > $limiteProductos;
                    @endphp

                    <tr>
                        {{-- Empresa --}}
                        <td class="ps-4">
                            <div class="fw-bold">{{ $empresa->nombre_comercial }}</div>
                            <small class="text-muted">{{ $empresa->email }}</small>
                        </td>

                        {{-- Plan --}}
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ $empresa->plan?->name ?? '-' }}
                            </span>
                        </td>

                        {{-- Artículos --}}
                        <td class="text-center">
                            <span class="fw-bold {{ $sobrepasado ? 'text-danger' : 'text-primary' }}">
                                {{ $empresa->products_count }}
                            </span>
                            <span class="text-muted small">/ {{ $limiteProductos ?: '∞' }}</span>
                        </td>

                        {{-- Clientes --}}
                        <td class="text-center">
                            <span class="fw-bold">{{ $empresa->clients_count }}</span>
                        </td>

                        {{-- Ventas --}}
                        <td class="text-center">
                            <span class="fw-bold text-success">{{ $empresa->ventas_count }}</span>
                        </td>

                        {{-- Multimedia --}}
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-images me-1"></i> {{ $empresa->product_images_count }}
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
                            @if($empresa->is_bonificated)
                                <span class="badge bg-purple text-white mb-1"><i class="bi bi-gift me-1"></i> BONIFICADO</span>
                            @endif
                            <span class="badge
                                {{ $estado === 'Activa'
                                    ? 'bg-success'
                                    : ($estado === 'Vencida'
                                        ? 'bg-warning text-dark'
                                        : 'bg-secondary') }} d-block">
                                {{ $estado }}
                            </span>
                            <div class="mt-1 small fw-bold text-muted">
                                Pactado: ${{ number_format($empresa->getPrecioMensual(), 0, ',', '.') }}
                            </div>
                        </td>

                        {{-- Acciones --}}
                        <td class="text-end pe-4">

                            {{-- Usuarios de la empresa (usa ID explícito, NO slug) --}}
                            <a href="{{ url('owner/empresas/' . $empresa->id . '/users') }}"
                               class="btn btn-sm btn-outline-primary">
                                Usuarios
                            </a>

                            {{-- Entrar como usuario (Omnisciencia) --}}
                            @php 
                                $admin = $empresa->users()->where('role', 'empresa')->where('activo', 1)->first() 
                                      ?? $empresa->users()->where('activo', 1)->first(); 
                            @endphp
                            
                            @if($admin)
                                <a href="{{ url('owner/mimetizar/empresa/' . $empresa->id . '/usuario/' . $admin->id) }}"
                                   class="btn btn-sm btn-primary ms-1 fw-bold" title="Entrar como Administrador ({{ $admin->name }})">
                                    <i class="bi bi-box-arrow-in-right"></i> ENTRAR
                                </a>
                            @else
                                <button class="btn btn-sm btn-secondary ms-1 disabled" title="No hay administrador activo">
                                    <i class="bi bi-slash-circle"></i> SIN ADMIN
                                </button>
                            @endif

                            {{-- Editar empresa (usa ID explícito, NO slug) --}}
                            <a href="{{ url('owner/empresas/' . $empresa->id . '/edit') }}"
                               class="btn btn-sm btn-dark ms-1">
                                Editar
                            </a>

                            {{-- Renovar solo si está vencida o inactiva --}}
                            @if ($estado !== 'Activa')
                                <form method="POST"
                                      action="{{ url('owner/empresas/' . $empresa->id . '/renovar') }}"
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

        {{-- =====================================================
            Paginación + Selector de densidad
        ====================================================== --}}
        @if ($empresas->total() > 0)
            <div class="card-footer bg-white border-top py-3">
                <div class="row align-items-center">
                    {{-- Selector de cantidad --}}
                    <div class="col-md-6 mb-3 mb-md-0">
                        <form action="{{ route('owner.empresas.index') }}" method="GET" class="d-flex align-items-center">
                            <label for="per_page" class="me-2 text-muted small">Mostrar:</label>
                            <select name="per_page" id="per_page" 
                                    class="form-select form-select-sm" 
                                    style="width: auto;" 
                                    onchange="this.form.submit()">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 renglones</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 renglones</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 renglones</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 renglones</option>
                            </select>
                            <span class="ms-2 text-muted small">de {{ $empresas->total() }} empresas</span>
                        </form>
                    </div>

                    {{-- Navegación --}}
                    <div class="col-md-6">
                        <div class="d-flex justify-content-md-end justify-content-center">
                            {!! $empresas->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
