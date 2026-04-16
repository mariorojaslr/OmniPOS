@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">

    {{-- CABECERA --}}
    <div class="mb-5">
        <a href="{{ route('empresa.gps.index') }}" class="text-decoration-none text-muted small mb-3 d-block">
            <i class="bi bi-arrow-left me-1"></i> Volver a Utilidades GPS
        </a>
        <h2 class="fw-bold text-white mb-1">Smart Route: Recorrido Ideal 🚚</h2>
        <p class="text-muted">Seleccioná los puntos de visita y optimizá tu jornada.</p>
    </div>

    <div class="row g-4">
        
        {{-- CONFIGURACIÓN --}}
        <div class="col-md-5">
            <div class="card bg-dark border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-white bg-opacity-5 border-0 p-4">
                    <h5 class="mb-0 text-white fw-bold">1. Selección de Paradas</h5>
                </div>
                <div class="card-body p-4 text-white">
                    <div class="mb-4">
                        <label class="form-label text-muted small text-uppercase">Agregar parada (Cliente / Proveedor)</label>
                        <div class="input-group">
                            <input type="text" id="searchEntity" class="form-control bg-black border-white border-opacity-10 text-white" placeholder="Escribí para buscar...">
                            <button class="btn btn-primary" type="button"><i class="bi bi-plus-lg"></i></button>
                        </div>
                    </div>

                    <div id="routeList" class="mb-4">
                        {{-- Ejemplo de parada --}}
                        <div class="alert bg-white bg-opacity-5 border-0 d-flex justify-content-between align-items-center mb-2 p-3">
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-primary rounded-circle p-2">1</span>
                                <div>
                                    <div class="fw-bold fs-6">Distribuidora Norte</div>
                                    <div class="x-small text-muted">📍 8GV2+M9 (Plus Code)</div>
                                </div>
                            </div>
                            <button class="btn btn-link text-danger p-0"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>

                    <button class="btn btn-warning w-100 py-3 fw-bold rounded-4 shadow-lg mb-3">
                        <i class="bi bi-cpu me-2"></i> CALCULAR RUTA ÓPTIMA
                    </button>
                </div>
            </div>
        </div>

        {{-- RESULTADO Y MAPA --}}
        <div class="col-md-7">
            <div class="card bg-dark border-0 shadow-lg rounded-4 overflow-hidden h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-5">
                    <div class="text-center opacity-25 mb-4">
                        <i class="bi bi-map fs-1 text-white"></i>
                    </div>
                    <h5 class="text-muted fw-bold">Mapa de Recorrido</h5>
                    <p class="text-muted small text-center px-5">Una vez generada la ruta, aquí aparecerá el mapa interactivo y el botón para enviar el link al conductor.</p>
                </div>
                
                {{-- BOTÓN COMPARTIR (HIDDEN BY DEFAULT) --}}
                <div class="p-4 bg-white bg-opacity-5 border-top border-white border-opacity-5 d-none">
                    <button class="btn btn-success w-100 py-3 fw-bold rounded-4">
                        <i class="bi bi-whatsapp me-2"></i> ENVIAR RUTA AL CHOFER
                    </button>
                </div>
            </div>
        </div>

    </div>

</div>

<style>
    body { background-color: #0b0b0b !important; }
    .form-control:focus {
        background-color: #000 !important;
        border-color: var(--color-primario) !important;
        box-shadow: none;
    }
</style>
@endsection
