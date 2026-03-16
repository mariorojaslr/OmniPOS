@extends(auth()->user()->role === 'owner' ? 'layouts.app' : 'layouts.empresa')

@section('content')

<div class="container" style="max-width:520px; margin-top: 50px;">

    <div class="card shadow-sm border-0 {{ auth()->user()->role === 'owner' ? 'glass-card' : '' }}">
        <div class="card-body p-4">

            <h4 class="mb-3 fw-bold">🔐 Cambiar contraseña</h4>
            <p class="text-muted mb-4">
                Ingresá tu contraseña actual y luego la nueva para mantener tu cuenta segura.
            </p>

            @if (session('status') === 'password-updated')
                <div class="alert alert-success border-0 shadow-sm mb-4">
                    ✔ Contraseña actualizada correctamente
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
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
