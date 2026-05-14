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

            <div class="mt-3">
                <a href="{{ route('demo.mode') }}" class="btn btn-outline-warning w-100 fw-bold py-2" style="border-radius: 12px; border-width: 2px">
                    ✨ ACCESO DEMO (PRUEBA)
                </a>
            </div>

        </form>

        <div class="auth-links">
            <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
        </div>

    </div>

</div>

@endsection
