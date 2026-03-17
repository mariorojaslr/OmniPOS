@extends('layouts.guest')

@section('content')

<div class="auth-container">

    <div class="auth-card">

        {{-- LOGO MULTIPOS --}}
        <div class="auth-logo">
            <img src="{{ asset('images/promo/logo_principal.png') }}" alt="MultiPOS Logo">
        </div>

        <h4 class="auth-title">Crear cuenta</h4>

        @if($errors->any())
            <div class="auth-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <input type="text" name="name" class="form-control auth-input" placeholder="Nombre" required>
            </div>

            <div class="mb-3">
                <input type="email" name="email" class="form-control auth-input" placeholder="Email" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control auth-input" placeholder="Contraseña" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password_confirmation" class="form-control auth-input" placeholder="Confirmar contraseña" required>
            </div>

            <button type="submit" class="auth-btn">
                Registrarse
            </button>

        </form>

        <div class="auth-links">
            <a href="{{ route('login') }}">¿Ya tienes cuenta? Iniciar sesión</a>
        </div>

    </div>

</div>

@endsection
