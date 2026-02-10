@extends('layouts.empresa')

@section('content')

<div class="mb-4">
    <h2 class="fw-bold">Desempeño del usuario</h2>
    <p class="text-muted mb-0">
        {{ $usuario->name }} — Panel de evaluación del empleado
    </p>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <div class="alert alert-info mb-4">
            Este módulo todavía no está activo.
            Aquí se mostrará el análisis completo del desempeño del empleado.
        </div>

        <div class="row g-4">

            {{-- HORAS TRABAJADAS --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Horas trabajadas</h6>
                        <h4 class="fw-bold">--</h4>
                        <span class="badge bg-secondary">Próximamente</span>
                    </div>
                </div>
            </div>

            {{-- ASISTENCIA --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Asistencia mensual</h6>
                        <h4 class="fw-bold">--</h4>
                        <span class="badge bg-secondary">Próximamente</span>
                    </div>
                </div>
            </div>

            {{-- TOTAL VENTAS --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Total ventas</h6>
                        <h4 class="fw-bold">--</h4>
                        <span class="badge bg-secondary">Próximamente</span>
                    </div>
                </div>
            </div>

            {{-- FALTANTES DE CAJA --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Faltantes de caja</h6>
                        <h4 class="fw-bold">--</h4>
                        <span class="badge bg-secondary">Próximamente</span>
                    </div>
                </div>
            </div>

        </div>

        <hr class="my-4">

        <div class="row g-4">

            {{-- RENDIMIENTO --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Rendimiento del empleado</h6>
                        <p class="mb-0">
                            Aquí se mostrará un análisis del rendimiento basado en ventas,
                            asistencia, caja y productividad.
                        </p>
                        <span class="badge bg-secondary">Próximamente</span>
                    </div>
                </div>
            </div>

            {{-- HISTORIAL --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Historial del empleado</h6>
                        <p class="mb-0">
                            Se registrarán movimientos, cambios de estado,
                            ventas realizadas y evolución del desempeño.
                        </p>
                        <span class="badge bg-secondary">Próximamente</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-4">
            <a href="{{ route('empresa.usuarios.index') }}"
               class="btn btn-outline-secondary">
                ← Volver a usuarios
            </a>
        </div>

    </div>
</div>

@endsection
