@extends('layouts.admin')

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-6">

        <div class="card shadow border-0">
            <div class="card-header bg-white fw-bold">
                Nueva empresa
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('owner.empresas.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nombre comercial</label>
                        <input type="text" name="nombre_comercial" class="form-control @error('nombre_comercial') is-invalid @enderror" value="{{ old('nombre_comercial') }}" required>
                        @error('nombre_comercial')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Plan de Suscripción</label>
                            <select name="plan_id" class="form-select">
                                <option value="">Sin Plan asignado</option>
                                @foreach($planes as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }} ({{ env('APP_CURRENCY', '$') }} {{ number_format((float)$plan->price, 2) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Fecha primer vencimiento</label>
                            <input type="date" name="fecha_vencimiento" class="form-control" title="Cuándo se vence la primera cuota o el plan gratis.">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('owner.empresas.index') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Crear empresa
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
