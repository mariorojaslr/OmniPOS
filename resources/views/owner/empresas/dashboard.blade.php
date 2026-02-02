@extends('layouts.app')

@section('content')
<h2 class="mb-4">Dashboard Empresa</h2>

<div class="row g-4">

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Estado</h6>
                <h4 class="fw-bold text-success">Activa</h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Vencimiento</h6>
                <h4 class="fw-bold">
                    {{ auth()->user()->empresa?->fecha_vencimiento
                        ? auth()->user()->empresa->fecha_vencimiento->format('d/m/Y')
                        : 'Sin definir' }}
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Sistema</h6>
                <p class="mb-0">Todo funcionando correctamente</p>
            </div>
        </div>
    </div>

</div>

<hr class="my-5">

<div class="alert alert-secondary">
    🚀 Nuevas funciones estarán disponibles pronto.
</div>
@endsection
