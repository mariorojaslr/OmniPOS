@extends('layouts.empresa')

@section('styles')
<style>
    .qr-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .qr-display {
        background: #fff;
        padding: 2rem;
        border-radius: 18px;
        box-shadow: inset 0 0 15px rgba(0,0,0,0.05);
        display: inline-block;
    }
    .instruction-card {
        background: #fff;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 15px;
        height: 100%;
        transition: 0.3s;
    }
    .instruction-card:hover { transform: translateY(-5px); }

    /* Estilos de Impresión Optimizados (15x15cm max) */
    @media print {
        header, nav, .btn, .instruction-card, .alert, .sidebar, footer, .no-print {
            display: none !important;
        }
        body {
            background: white !important;
            margin: 0;
            padding: 0;
        }
        .printable-area {
            display: block !important;
            width: 150mm !important; /* Aproximadamente 15x15cm */
            height: 150mm !important;
            margin: 20mm auto;
            text-align: center;
            border: 2px dashed #eee;
            padding: 10mm;
        }
        .qr-display-print {
            display: block !important;
            margin: 0 auto;
        }
        .qr-display-print svg {
            width: 120mm !important;
            height: 120mm !important;
        }
        .print-title {
            font-size: 24pt !important;
            font-weight: bold !important;
            margin-top: 10mm;
            color: #000;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="qr-card p-5 text-center mb-5">
                <h5 class="text-uppercase fw-bold text-primary mb-1">Punto de Marcación Oficial</h5>
                <p class="text-muted mb-4 small">Imprimí este código para que tus empleados registren su jornada.</p>
                
                <div class="qr-display mb-4">
                    {!! QrCode::size(200)->generate($urlResitro) !!}
                </div>

                <h2 class="fw-extrabold mb-1">{{ $empresa->nombre }}</h2>
                <p class="text-muted mb-4">Control de Asistencia Digital</p>

                <div class="d-flex justify-content-center gap-3 no-print">
                    <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="bi bi-printer-fill me-2"></i> IMPRIMIR CARTEL
                    </button>
                    <a href="{{ $urlResitro }}" target="_blank" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-phone me-2"></i> PROBAR LINK
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- AREA OCULTA PARA IMPRESIÓN (SOLO SE VE AL IMPRIMIR) --}}
    <div class="printable-area d-none text-center">
        <div class="qr-display-print">
            {!! QrCode::size(400)->generate($urlResitro) !!}
        </div>
        <div class="print-title text-uppercase">{{ $empresa->nombre }}</div>
        <div style="font-size: 14pt; margin-top: 5mm; color: #666;">FICHADA DIGITAL POR QR</div>
    </div>

    <!-- Instrucciones Proporcionadas -->
    <div class="row g-4 no-print">
        <div class="col-md-6">
            <div class="instruction-card p-4">
                <h6 class="fw-bold mb-3">🛠️ ¿Cómo funciona?</h6>
                <ul class="list-unstyled mb-0 small text-muted" style="line-height: 1.8;">
                    <li><i class="bi bi-1-circle-fill text-primary me-2"></i> El empleado escanea desde su celular.</li>
                    <li><i class="bi bi-2-circle-fill text-primary me-2"></i> El sistema identifica si es entrada o salida.</li>
                    <li><i class="bi bi-3-circle-fill text-primary me-2"></i> El registro impacta al instante en reportes.</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="instruction-card p-4">
                <h6 class="fw-bold mb-3">💡 Consejos de Instalación</h6>
                <ul class="list-unstyled mb-0 small text-muted" style="line-height: 1.8;">
                    <li><i class="bi bi-check-lg text-success me-2"></i> Pegá el cartel a la altura visual promedio.</li>
                    <li><i class="bi bi-check-lg text-success me-2"></i> Asegurá buena luz (evitá reflejos en el papel).</li>
                    <li><i class="bi bi-check-lg text-success me-2"></i> Este tamaño (15x15) es ideal para recepciones.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

