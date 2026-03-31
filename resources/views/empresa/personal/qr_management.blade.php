@extends('layouts.empresa')
@php use SimpleSoftwareIO\QrCode\Facades\QrCode; @endphp

@section('content')

<div class="container py-4">
    <div class="row justify-content-center text-center">
        <div class="col-md-8">
            <h1 class="fw-bold mb-2">Punto de Control de Asistencia QR 📲</h1>
            <p class="text-muted">Imprime este código y colócalo en el ingreso de tu obra o local para que los empleados registren su entrada y salida.</p>

            <div class="card shadow-lg border-0 mt-4 overflow-hidden" style="border-radius: 20px;">
                <div class="card-header bg-dark text-white py-3">
                    <span class="fw-bold text-uppercase" style="letter-spacing: 2px;">{{ $empresa->nombre_comercial }} — CONTROL HORARIO</span>
                </div>
                <div class="card-body bg-white py-5">
                    
                    {{-- Generación de QR --}}
                    <div class="d-inline-block p-4 border rounded shadow-sm bg-light mb-4">
                        {!! QrCode::size(300)->gradient(0, 0, 0, 100, 100, 100, 'radial')->generate($urlResitro) !!}
                    </div>

                    <h4 class="fw-bold text-primary">ESCANEAR PARA FICHAR</h4>
                    <p class="small text-muted mb-4">Escanea con la cámara de tu celular para registrar ingreso o egreso.</p>
                    
                    <div class="alert alert-info border-0 shadow-sm d-inline-block px-4 py-2">
                        <i class="bi bi-link-45deg me-1"></i>
                        <small>{{ $urlResitro }}</small>
                    </div>
                </div>
                <div class="card-footer bg-light py-3 border-0">
                    <button onclick="window.print()" class="btn btn-dark fw-bold px-4 shadow">
                        🖨️ IMPRIMIR QR DE CONTROL
                    </button>
                    <a href="{{ route('empresa.usuarios.index') }}" class="btn btn-link text-muted">
                        Volver a Personal
                    </a>
                </div>
            </div>

            <div class="mt-5 row text-start">
                <div class="col-md-4">
                    <div class="p-3 bg-white rounded shadow-sm border-top border-primary border-4">
                        <h6 class="fw-bold mb-2">1. Colocar en obra</h6>
                        <p class="small text-muted mb-0">Imprime el código en un lugar visible y protegido del clima.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-white rounded shadow-sm border-top border-success border-4">
                        <h6 class="fw-bold mb-2">2. Instruir al personal</h6>
                        <p class="small text-muted mb-0">Al entrar y salir deben escanearlo con su smartphone.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-white rounded shadow-sm border-top border-warning border-4">
                        <h6 class="fw-bold mb-2">3. Medir desempeño</h6>
                        <p class="small text-muted mb-0">Revisa las horas trabajadas en el reporte de rendimiento.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    @media print {
        body * { visibility: hidden; }
        .card, .card * { visibility: visible; }
        .card { position: absolute; left: 0; top: 0; width: 100%; }
        .card-footer { display: none; }
    }
</style>
@endsection
