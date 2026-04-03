@extends('layouts.empresa')

@section('content')

<style>
    body { background-color: #000 !important; color: #fff !important; }
    .vault-card {
        background: rgba(20, 20, 25, 0.85);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        transition: transform 0.3s ease;
    }
    .vault-card:hover { transform: translateY(-5px); border-color: rgba(255, 255, 255, 0.3); }
    .btn-vault {
        background: #16a34a;
        color: white !important;
        font-weight: 800;
        border-radius: 12px;
        padding: 12px 25px;
        border: none;
        transition: all 0.3s;
    }
    .btn-vault:hover { background: #15803d; transform: scale(1.05); }
    .highlight-text { color: #16a34a; font-weight: 900; }
</style>

<div class="container-fluid px-4 py-5">
    
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-black mb-1">🛡️ Bóveda de Resguardo</h1>
            <p class="text-white opacity-40">Gestión de copias de seguridad y activos críticos de <span class="highlight-text">{{ $empresa->nombre_comercial }}</span></p>
        </div>
        <div class="badge bg-success bg-opacity-20 text-success border border-success px-4 py-2 rounded-pill fw-bold">
            <i class="bi bi-shield-check me-2"></i>SISTEMA PROTEGIDO
        </div>
    </div>

    <div class="row g-4">
        
        {{-- CARD: BASE DE DATOS --}}
        <div class="col-md-4">
            <div class="vault-card p-4 h-100 d-flex flex-direction-column">
                <div class="fs-1 mb-3">🗄️</div>
                <h3 class="fw-black mb-3">Base de Datos</h3>
                <p class="text-muted small flex-grow-1">Descarga el volcado completo de tus productos, ventas, clientes y configuraciones en formato SQL/JSON.</p>
                <button class="btn btn-vault mt-4 w-100">
                    <i class="bi bi-download me-2"></i>DESCARGAR SQL
                </button>
            </div>
        </div>

        {{-- CARD: IMÁGENES Y MEDIA --}}
        <div class="col-md-4">
            <div class="vault-card p-4 h-100 d-flex flex-direction-column">
                <div class="fs-1 mb-3">🖼️</div>
                <h3 class="fw-black mb-3">Archivos & Media</h3>
                <p class="text-muted small flex-grow-1">Acceso a la sincronización con Bunny.net para respaldar imágenes de productos y vídeos originales.</p>
                <button class="btn btn-outline-light border-secondary text-white fw-bold mt-4 w-100 rounded-12 p-2">
                    <i class="bi bi-cloud-arrow-down me-2"></i>SINC BIZARRE
                </button>
            </div>
        </div>

        {{-- CARD: CONFIGURACIÓN SEGURA --}}
        <div class="col-md-4">
            <div class="vault-card p-4 h-100 d-flex flex-direction-column">
                <div class="fs-1 mb-3">🔑</div>
                <h3 class="fw-black mb-3">Keys de Pasarelas</h3>
                <p class="text-muted small flex-grow-1">Respaldo blindado de tus credenciales de Mercado Pago, Stripe y tokens de envíos.</p>
                <button class="btn btn-dark border-secondary text-white fw-bold mt-4 w-100 rounded-12 p-2">
                    <i class="bi bi-lock me-2"></i>VER TOKENS
                </button>
            </div>
        </div>

    </div>

    <div class="mt-5 p-4 rounded-4 bg-dark bg-opacity-50 border border-secondary border-dashed text-center">
        <h5 class="fw-bold mb-2">💡 ¿Sabías qué?</h5>
        <p class="text-muted small mb-0">MultiPOS genera automáticamente un respaldo semanal de tus datos críticos, el cual puedes descargar o restaurar en cualquier momento desde esta bóveda.</p>
    </div>

</div>

@endsection
