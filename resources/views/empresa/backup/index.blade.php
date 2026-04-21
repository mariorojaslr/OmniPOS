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
        padding: 35px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.3s ease;
    }

    .vault-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .vault-title {
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .btn-download {
        background: #22c55e;
        color: white;
        border: none;
        padding: 15px 25px;
        border-radius: 12px;
        font-weight: 800;
        width: 100%;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .btn-download:hover { background: #16a34a; color: white; }

    /* Modal Styling */
    .modal-content {
        border-radius: 30px;
        border: none;
        overflow: hidden;
    }
    .modal-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 2rem;
    }
    .modal-body {
        padding: 2.5rem;
    }
    .step-badge {
        background: #0d6efd;
        color: white;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 900;
        margin-right: 15px;
    }

</style>

<div class="row align-items-center mb-5">
    <div class="col-md-9">
        <h1 class="fw-bold mb-0">🛡️ Bóveda de Resguardo</h1>
        <p class="text-secondary opacity-75">Gestión de activos y copias de seguridad de <span class="fw-bold text-dark">{{ $empresa->nombre_comercial }}</span></p>
    </div>
</div>

<div class="row g-4">
    <!-- Base de Datos -->
    <div class="col-md-4">
        <div class="vault-card">
            <div>
                <h4 class="vault-title">🗄️ Base de Datos</h4>
                <p class="text-secondary small mb-4">Descarga el volcado completo de tus productos, ventas, clientes y configuraciones operativas.</p>
            </div>
            <button class="btn btn-download" onclick="startBackupProcess('sql')">INICIAR ASISTENTE</button>
        </div>
    </div>

    <!-- Archivos & Media -->
    <div class="col-md-4">
        <div class="vault-card">
            <div>
                <h4 class="vault-title">🖼️ Repositorio Multimedia</h4>
                <p class="text-secondary small mb-4">Sincronización con el servidor central de alto rendimiento para el resguardo de imágenes y media.</p>
            </div>
            <button class="btn btn-download" style="background: #3b82f6;" onclick="startBackupProcess('media')">SINC. REPOSITORIO</button>
        </div>
    </div>

    <!-- Pasarelas -->
    <div class="col-md-4">
        <div class="vault-card">
            <div>
                <h4 class="vault-title">🔑 Tokens de Conexión</h4>
                <p class="text-secondary small mb-4">Respaldo de seguridad de tus credenciales de cobro electrónico (Mercado Pago, Stripe) y envíos.</p>
            </div>
            <button class="btn btn-download" style="background: #0f172a;" onclick="startBackupProcess('tokens')">VER TOKENS</button>
        </div>
    </div>
</div>

<div class="card mt-5 p-4 border-0 shadow-sm" style="border-left: 5px solid #22c55e; border-radius: 12px; background: #fff;">
    <div class="d-flex align-items-center gap-3">
        <span class="fs-1">💡</span>
        <div>
            <h5 class="fw-bold mb-1">¿Sabías qué?</h5>
            <p class="text-secondary mb-0">MultiPOS genera automáticamente un respaldo semanal de tus datos críticos, el cual puedes descargar o restaurar en cualquier momento desde esta bóveda privada.</p>
        </div>
    </div>
</div>

{{-- MODAL ASISTENTE PASO A PASO --}}
<div class="modal fade" id="backupWizard" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h4 class="fw-bold mb-0" id="wizardTitle">Asistente de Resguardo</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" id="wizardContent">
                {{-- Contenido dinámico via JS --}}
                <div id="step-1">
                    <div class="mb-4">
                        <span class="fs-1">🚀</span>
                    </div>
                    <h4 class="fw-bold mb-3">Preparando Información</h4>
                    <p class="text-secondary px-4">Esta acción recopilará todos los datos de su empresa para generar un archivo único de respaldo. El procesamiento puede tardar unos segundos.</p>
                    <button class="btn btn-primary px-5 py-3 rounded-pill fw-bold" onclick="nextStep(2)">ENTENDIDO, CONTINUAR</button>
                </div>

                <div id="step-2" style="display:none;">
                    <div class="mb-4">
                        <span class="fs-1">💾</span>
                    </div>
                    <h4 class="fw-bold mb-3">Espacio en Disco Local</h4>
                    <p class="text-danger fw-bold">⚠️ ADVERTENCIA DE ALMACENAMIENTO</p>
                    <p class="text-secondary px-4">
                        El archivo se descargará en <strong>Su Propia Computadora</strong>. <br>
                        Se estima un volumen de: <span class="badge bg-dark fs-6" id="estimatedSize">Calculando...</span> <br><br>
                        Asegúrese de contar con espacio suficiente antes de proceder.
                    </p>
                    <div class="d-flex gap-3 justify-content-center">
                        <button class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-bold" onclick="nextStep(1)">VOLVER</button>
                        <button class="btn btn-success px-5 py-3 rounded-pill fw-bold" onclick="nextStep(3)">TENGO ESPACIO</button>
                    </div>
                </div>

                <div id="step-3" style="display:none;">
                    <div class="mb-4">
                        <span class="fs-1">🛡️</span>
                    </div>
                    <h4 class="fw-bold mb-3">Confirmación Final</h4>
                    <p class="text-secondary px-4">¿Está seguro de que desea realizar la descarga ahora? Esta acción es segura y no afecta el funcionamiento del sistema en línea.</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <button class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-bold" onclick="nextStep(2)">VOLVER</button>
                        <button class="btn btn-dark px-5 py-3 rounded-pill fw-bold" id="finalActionBtn">SÍ, INICIAR DESCARGA</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const wizardModal = new bootstrap.Modal(document.getElementById('backupWizard'));
    let currentType = '';

    function startBackupProcess(type) {
        currentType = type;
        nextStep(1);
        
        // Simular cálculo de tamaño
        const sizes = {
            'sql': '12.4 MB',
            'media': '450.8 MB',
            'tokens': '0.1 MB'
        };
        document.getElementById('estimatedSize').innerText = sizes[type] || '---';
        
        wizardModal.show();
    }

    function nextStep(step) {
        document.getElementById('step-1').style.display = 'none';
        document.getElementById('step-2').style.display = 'none';
        document.getElementById('step-3').style.display = 'none';
        
        document.getElementById('step-' + step).style.display = 'block';

        if(step === 3) {
            document.getElementById('finalActionBtn').onclick = function() {
                window.location.href = "{{ route('empresa.backup.download') }}?type=" + currentType;
                wizardModal.hide();
            };
        }
    }

</script>
@endsection
