@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-header">
            <h5 class="mb-0">
                Nuevo usuario para {{ $empresa->nombre }}
            </h5>
        </div>

        <div class="card-body">
            <form method="POST"
                  action="{{ route('owner.empresas.users.store', $empresa) }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Contraseña
                        <small class="text-muted">(opcional)</small>
                    </label>
                    <input type="text"
                           name="password"
                           class="form-control"
                           placeholder="Dejar vacío para generar automática">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('owner.empresas.users.index', $empresa) }}"
                       class="btn btn-outline-secondary">
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-success">
                        Crear usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
