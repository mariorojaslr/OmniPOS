@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="fs-2">🤖</span>
                <h2 class="fw-extrabold mb-0" style="color: {{ $modoOscuro ? '#ffffff' : '#1e293b' }};">
                    Sector de Inteligencia Artificial
                </h2>
                <span class="badge bg-purple text-white px-3 py-1 rounded-pill fw-bold shadow-sm" style="background: linear-gradient(135deg, #a855f7, #6366f1);">
                    <i class="bi bi-stars me-1"></i> Asistencia & Migración Clínica
                </span>
            </div>
            <p class="text-muted mb-0 mt-1">
                Herramientas impulsadas por IA para migrar pacientes de software antiguos e interpretar fichas físicas sin carga manual.
            </p>
        </div>
    </div>

    <!-- TARJETAS DE LOS DOS MÓDULOS DE IA -->
    <div class="row g-4 mb-4">
        <!-- MÓDULO 1: CARGA DE PACIENTES CON IA -->
        <div class="col-lg-6 col-md-12">
            <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden" style="background: {{ $modoOscuro ? 'rgba(15, 23, 42, 0.7)' : '#ffffff' }}; border: 1px solid {{ $modoOscuro ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)' }} !important;">
                <div class="p-4 p-md-5 d-flex flex-column h-100 position-relative">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="p-3 rounded-4 shadow-sm" style="background: rgba(14, 165, 233, 0.1); color: #0284c7;">
                            <i class="bi bi-file-earmark-spreadsheet-fill fs-2"></i>
                        </div>
                        <span class="badge bg-info bg-opacity-10 text-info fw-bold px-3 py-2 rounded-pill">
                            NLP & Mapeo Semántico
                        </span>
                    </div>

                    <h4 class="fw-bold mb-2 {{ $modoOscuro ? 'text-white' : 'text-dark' }}">
                        1. Carga de Pacientes con Inteligencia Artificial
                    </h4>
                    <p class="text-secondary small mb-4 flex-grow-1">
                        Sube cualquier archivo Excel (.xlsx, .xls) o CSV exportado del software anterior o planilla que usaba la clínica. La IA detecta automáticamente los nombres de columnas sin importar cómo estén escritas (DNI, Cédula, Tel, Paciente, Celular, Obra Social) y los estructura en el sistema sin pérdida de datos.
                    </p>

                    <div class="p-3 rounded-3 mb-4" style="background: {{ $modoOscuro ? 'rgba(255,255,255,0.02)' : '#f8fafc' }}; border: 1px dashed {{ $modoOscuro ? 'rgba(255,255,255,0.1)' : '#cbd5e1' }};">
                        <div class="d-flex align-items-center justify-content-between small text-muted">
                            <span><i class="bi bi-check2-circle text-success me-1"></i> Formatos aceptados:</span>
                            <span class="fw-bold">Excel (.xlsx), CSV, TXT</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between small text-muted mt-2">
                            <span><i class="bi bi-check2-circle text-success me-1"></i> Pacientes en la base:</span>
                            <span class="fw-bold text-dark {{ $modoOscuro ? 'text-white' : '' }}">{{ $totalPacientes }} registrados</span>
                        </div>
                    </div>

                    <a href="{{ route('empresa.ai.import-patients') }}" class="btn btn-primary rounded-pill py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-cloud-arrow-up-fill fs-5"></i> Iniciar Importación Asistida con IA
                    </a>
                </div>
            </div>
        </div>

        <!-- MÓDULO 2: ESCANEO DE ODONTOGRAMAS MANUALES CON IA -->
        <div class="col-lg-6 col-md-12">
            <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden" style="background: {{ $modoOscuro ? 'rgba(15, 23, 42, 0.7)' : '#ffffff' }}; border: 1px solid {{ $modoOscuro ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)' }} !important;">
                <div class="p-4 p-md-5 d-flex flex-column h-100 position-relative">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="p-3 rounded-4 shadow-sm" style="background: rgba(245, 158, 11, 0.1); color: #d97706;">
                            <i class="bi bi-camera-fill fs-2"></i>
                        </div>
                        <span class="badge bg-warning bg-opacity-10 text-warning fw-bold px-3 py-2 rounded-pill">
                            Computer Vision
                        </span>
                    </div>

                    <h4 class="fw-bold mb-2 {{ $modoOscuro ? 'text-white' : 'text-dark' }}">
                        2. Escaneo de Odontogramas Anteriores Manuales
                    </h4>
                    <p class="text-secondary small mb-4 flex-grow-1">
                        Toma una fotografía o escanea el odontograma impreso o en papel que tenía el cliente en su ficha física antigua. La IA analiza el diagrama visual, reconoce las cruces de extracciones, sombreados de caries, marcas de coronas y obturaciones, y genera de forma automática el odontograma interactivo asignado al paciente.
                    </p>

                    <div class="p-3 rounded-3 mb-4" style="background: {{ $modoOscuro ? 'rgba(255,255,255,0.02)' : '#f8fafc' }}; border: 1px dashed {{ $modoOscuro ? 'rgba(255,255,255,0.1)' : '#cbd5e1' }};">
                        <div class="d-flex align-items-center justify-content-between small text-muted">
                            <span><i class="bi bi-check2-circle text-success me-1"></i> Entrada soportada:</span>
                            <span class="fw-bold">Cámara de móvil, Foto JPG, PNG, PDF</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between small text-muted mt-2">
                            <span><i class="bi bi-check2-circle text-success me-1"></i> Historias clínicas con IA:</span>
                            <span class="fw-bold text-dark {{ $modoOscuro ? 'text-white' : '' }}">{{ $totalOdontogramas }} registros</span>
                        </div>
                    </div>

                    <a href="{{ route('empresa.ai.scan-odontogram') }}" class="btn btn-warning text-dark rounded-pill py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-camera-reels-fill fs-5"></i> Escanear y Digitalizar Ficha Antigua
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
