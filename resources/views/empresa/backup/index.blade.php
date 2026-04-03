@extends('layouts.empresa')

@section('content')
<style>
    :root {
        --bg-light: #f4f6f9;
        --card-white: #ffffff;
        --border-color: #e2e8f0;
    }

    body {
        background-color: var(--bg-light) !important;
        color: #1e293b;
    }

    .vault-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .vault-title {
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .btn-download {
        background: #22c55e;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 10px;
        font-weight: 800;
        width: 100%;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .btn-download:hover { background: #16a34a; transform: scale(1.02); color: white; }

</style>

<div class="row align-items-center mb-5">
    <div class="col-md-8">
        <h1 class="fw-bold mb-0">🛡️ Bóveda de Resguardo</h1>
        <p class="text-secondary small">Gestión de copias de seguridad de <span class="fw-bold">{{ $empresa->nombre_comercial }}</span></p>
    </div>
</div>

<div class="row g-4">
    <!-- Base de Datos -->
    <div class="col-md-4">
        <div class="vault-card">
            <h4 class="vault-title">🗄️ Base de Datos</h4>
            <p class="text-secondary small mb-4">Descarga el volcado completo de tus productos, ventas y configuraciones en formato SQL/JSON.</p>
            <button class="btn btn-download">DESCARGAR SQL</button>
        </div>
    </div>

    <!-- Archivos & Media -->
    <div class="col-md-4">
        <div class="vault-card">
            <h4 class="vault-title">🖼️ Media & Bunny</h4>
            <p class="text-secondary small mb-4">Acceso a la sincronización con Bunny.net para respaldar imágenes de productos y videos.</p>
            <button class="btn btn-download" style="background: #3b82f6;">SINC BIZARRE</button>
        </div>
    </div>

    <!-- Pasarelas -->
    <div class="col-md-4">
        <div class="vault-card">
            <h4 class="vault-title">🔑 Keys Pasarelas</h4>
            <p class="text-secondary small mb-4">Respaldo blindado de tus credenciales de Mercado Pago, Stripe y tokens de envíos.</p>
            <button class="btn btn-download" style="background: #0f172a;">VER TOKENS</button>
        </div>
    </div>
</div>

<div class="card mt-5 p-4 border-0 shadow-sm" style="border-left: 5px solid #22c55e; border-radius: 10px;">
    <div class="d-flex align-items-center gap-3">
        <span class="fs-1">💡</span>
        <div>
            <h5 class="fw-bold mb-1">¿Sabías qué?</h5>
            <p class="text-secondary mb-0">MultiPOS genera automáticamente un respaldo semanal de tus datos críticos, el cual puedes descargar o restaurar en cualquier momento desde esta bóveda.</p>
        </div>
    </div>
</div>
@endsection
