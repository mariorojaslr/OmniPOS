@extends('layouts.app')

@section('content')

<div class="container" style="max-width:520px">

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <h4 class="mb-3">Cambiar contraseña</h4>
            <p class="text-muted mb-4">
                Ingresá tu contraseña actual y luego la nueva.
            </p>

            @if (session('status') === 'password-updated')
                <div class="alert alert-success">
                    ✔ Contraseña actualizada correctamente
                </div>
            @endif

            {{-- 👇 IMPORTANTE: action /password --}}
            <form method="POST" action="/password">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Contraseña actual</label>
                    <input type="password" name="current_password"
                           class="form-control" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nueva contraseña</label>
                    <input type="password" name="password"
                           class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation"
                           class="form-control" required>
                </div>

                <button class="btn btn-success w-100">
                    Guardar nueva contraseña
                </button>

            </form>

        </div>
    </div>

</div>

@endsection
