@extends('layouts.empresa')

@section('header')
    Gestión de Asistencia por QR
@endsection

@section('content')
    <div class="row m-0 p-0">
        <div class="col-12 m-0 p-0">
            <div class="card bg-black border-secondary shadow-lg rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-dark border-bottom border-secondary py-3">
                    <h5 class="card-title text-white mb-0 mt-2">Punto de Marcación Oficial</h5>
                    <p class="text-secondary small mb-0 mt-1">Imprime este código y colócalo en un lugar visible para tus empleados.</p>
                </div>
                <div class="card-body text-center py-5">
                    <div class="qr-container bg-white d-inline-block p-4 rounded-4 shadow-sm mb-4">
                        {!! QrCode::size(250)->generate($urlResitro) !!}
                    </div>
                    
                    <h3 class="text-white fw-bold mb-2">{{ $empresa->nombre }}</h3>
                    <p class="text-muted mb-4">Escanea para registrar entrada o salida</p>

                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                        <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 py-2 me-md-2">
                             <span class="ms-1">🖨️ Imprimir Cartel QR</span>
                        </button>
                        <a href="{{ $urlResitro }}" target="_blank" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                            <span class="ms-1">🌐 Probar Escaneo (Link)</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Instrucciones -->
            <div class="row m-0 p-0 mt-4">
                <div class="col-md-6 mb-4">
                    <div class="card bg-dark border-secondary h-100 rounded-4 shadow">
                        <div class="card-body">
                            <h6 class="text-white fw-bold mb-3">🛠️ ¿Cómo funciona?</h6>
                            <p class="text-secondary small">1. El empleado escanea este código con la cámara de su móvil.</p>
                            <p class="text-secondary small">2. Si ya ingresó sus datos, el sistema le pedirá confirmar su entrada o salida.</p>
                            <p class="text-secondary small">3. El registro queda guardado con hora exacta en el Panel de Rendimiento.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card bg-dark border-secondary h-100 rounded-4 shadow">
                        <div class="card-body">
                            <h6 class="text-white fw-bold mb-3">🔔 Recomendaciones</h6>
                            <p class="text-secondary small">✅ Colócalo a la altura de los ojos.</p>
                            <p class="text-secondary small">✅ Asegúrate de tener buena iluminación.</p>
                            <p class="text-secondary small">✅ Puedes imprimirlo en tamaño A4 o más pequeño.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estilos de Impresión -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .qr-container, .qr-container *, .text-center h3 {
                visibility: visible;
            }
            .qr-container {
                position: absolute;
                left: 50%;
                top: 40%;
                transform: translate(-50%, -50%);
                padding: 0 !important;
                border: none !important;
            }
            .text-center h3 {
                position: absolute;
                left: 0;
                right: 0;
                top: 70%;
                font-size: 40px !important;
                color: black !important;
            }
            .qr-container svg {
                width: 500px !important;
                height: 500px !important;
            }
        }
    </style>
@endsection
