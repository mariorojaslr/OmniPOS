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
