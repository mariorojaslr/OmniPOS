@extends('layouts.empresa')

@section('content')

<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 text-center overflow-hidden" 
             style="border-radius: 20px; border-bottom: 8px solid {{ $asistenciaActiva ? '#dc3545' : '#28a745' }};">
            
            <div class="card-header bg-white py-4 border-0">
                <img src="{{ $empresa->config && $empresa->config->logo ? $empresa->config->logo_url : asset('images/logo_premium.png') }}" 
                     alt="Logo" style="max-height: 70px; max-width: 100%;">
                <h5 class="fw-bold text-uppercase mt-3 mb-0" style="letter-spacing: 1px;">Control Horario — {{ $empresa->nombre_comercial }}</h5>
            </div>

            <div class="card-body py-5 px-4 bg-light">
                
                <h6 class="text-muted mb-1">¡Hola, <span class="fw-bold text-dark">{{ $user->name }}</span>! 👋</h6>
                <p class="small text-muted mb-4">¿Qué acción deseas realizar en este momento?</p>

                @if(!$asistenciaActiva)
                    <div class="card bg-white p-4 shadow-sm mb-4 border-0">
                        <i class="bi bi-clock-history fs-1 text-success mb-2"></i>
                        <h4 class="fw-bold text-success mb-1">REGISTRAR ENTRADA</h4>
                        <p class="small text-muted mb-4">Inicia tu jornada laboral ahora mismo.</p>

                        <form action="{{ route('empresa.personal.checkin') }}" method="POST">
                            @csrf
                            <input type="hidden" name="vuelto_inicial" value="0">
                            <button type="submit" class="btn btn-success w-100 py-3 fw-bold shadow">
                                📲 MARCAR ENTRADA
                            </button>
                        </form>
                    </div>
                @else
                    <div class="card bg-white p-4 shadow-sm mb-4 border-0">
                        <i class="bi bi-stopwatch-fill fs-1 text-danger mb-2"></i>
                        <h4 class="fw-bold text-danger mb-1">REGISTRAR SALIDA</h4>
                        <p class="small text-muted mb-0">Has estado trabajando desde las <span class="fw-bold px-2 py-1 bg-light rounded text-dark">{{ $asistenciaActiva->entrada->format('H:i') }} hs</span></p>
                        <p class="small text-muted mb-4">¿Es momento de finalizar el día?</p>

                        <form action="{{ route('empresa.personal.checkout') }}" method="POST">
                            @csrf
                            <input type="hidden" name="vuelto_final" value="0">
                            <button type="submit" class="btn btn-danger w-100 py-3 fw-bold shadow">
                                🏃‍♂️ MARCAR SALIDA
                            </button>
                        </form>
                    </div>
                @endif

                <p class="small text-muted">Asegúrate de estar en el lugar de trabajo correcto antes de marcar.</p>
                
            </div>
            
            <div class="card-footer bg-white py-3">
                <a href="{{ route('dashboard') }}" class="btn btn-link text-decoration-none text-muted">
                    Ir al panel de control
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
