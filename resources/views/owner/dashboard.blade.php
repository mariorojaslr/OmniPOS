@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- Encabezado --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            Dashboard Owner
        </h2>
        <p class="text-muted mb-0">
            Panel general del sistema
        </p>
    </div>

    {{-- Cards --}}
    <div class="row g-4">

        {{-- Empresas totales --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <div class="text-muted small">Empresas totales</div>
                    <div class="fs-2 fw-bold text-primary">
                        {{ $empresasCount }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Usuarios de empresas --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 border-start border-4 border-success">
                <div class="card-body">
                    <div class="text-muted small">Usuarios de empresas</div>
                    <div class="fs-2 fw-bold text-success">
                        {{ $usuariosCount }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Estado del sistema --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 border-start border-4 border-secondary">
                <div class="card-body">
                    <div class="text-muted small">Estado del sistema</div>
                    <div class="fs-5 fw-bold text-secondary">
                        Operativo
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Acciones --}}
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold">Acciones disponibles</h5>

                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('owner.empresas.index') }}"
                           class="btn btn-outline-primary">
                            Gestionar empresas
                        </a>
                    </div>

                    <small class="text-muted d-block mt-3">
                        Acciones disponibles para el administrador del sistema
                    </small>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
