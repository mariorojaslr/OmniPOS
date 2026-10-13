@extends('layouts.guest')

@section('content')

<div class="auth-container">

    <div class="auth-card">

        {{-- LOGO OMNIPOS --}}
        <div class="auth-logo">
            <img src="{{ asset('images/logo_omnipos.png') }}" alt="OmniPOS Logo">
        </div>

        <h4 class="auth-title">Iniciar sesión</h4>

        @if($errors->any())
            <div class="auth-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <input type="email" name="email" class="form-control auth-input" placeholder="Email" required autofocus>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control auth-input" placeholder="Contraseña" required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" class="form-check-input">
                <label class="form-check-label">Recordarme</label>
            </div>

            <button type="submit" class="auth-btn">
                Ingresar
            </button>

            <div class="mt-3 d-flex flex-column gap-2">
                <a href="{{ route('demo.direct-login', 'dental') }}" class="btn btn-outline-info w-100 fw-bold py-2 text-start d-flex align-items-center justify-content-between shadow-sm" style="border-radius: 12px; border-width: 2px;">
                    <span>🦷 Demo Clínica Dental</span>
                    <span class="badge bg-info text-dark">1-CLIC</span>
                </a>
                <a href="{{ route('demo.direct-login', 'owner') }}" class="btn btn-outline-warning w-100 fw-bold py-2 text-start d-flex align-items-center justify-content-between shadow-sm" style="border-radius: 12px; border-width: 2px;">
                    <span>👑 Panel Dueño (Owner)</span>
                    <span class="badge bg-warning text-dark">1-CLIC</span>
                </a>
            </div>

            <div class="mt-3 p-2 rounded-3 bg-dark bg-opacity-50 border border-secondary border-opacity-25 small text-muted text-start" style="font-size: 0.78rem;">
                <div class="fw-bold text-white mb-1">🔑 Credenciales de Prueba:</div>
                <div class="text-white-50">Clínica: <strong class="text-info">dental@poolhub.com</strong></div>
                <div class="text-white-50">Dueño: <strong class="text-warning">admin@poolhub.com</strong></div>
                <div class="text-white-50">Contraseña: <strong class="text-white">demo1234</strong></div>
            </div>
        </form>

        <div class="auth-links">
            <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
        </div>

    </div>

</div>

@endsection
