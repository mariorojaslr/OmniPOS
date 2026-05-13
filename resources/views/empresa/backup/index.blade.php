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
    <!-- 1. DATOS -->
    <div class="col-md-4">
        <div class="vault-card">
            <div>
                <h4 class="vault-title">🗄️ DATOS</h4>
                <p class="text-secondary small mb-2">Volcado SQL completo con sentencias INSERT de:</p>
                <ul class="text-secondary small mb-4 ps-3" style="line-height: 1.8;">
                    <li>Productos, variantes y stock</li>
                    <li>Ventas y detalle de comprobantes</li>
                    <li>Clientes y proveedores</li>
                    <li>Cuentas corrientes y tesorería</li>
                    <li>Gastos, compras y asistencias</li>
                </ul>
            </div>
            <button class="btn btn-download" onclick="startBackupProcess('sql')">
                <i class="fas fa-database me-2"></i> DESCARGAR DATOS
            </button>
        </div>
    </div>

    <!-- 2. FOTOS -->
    <div class="col-md-4">
        <div class="vault-card">
            <div>
                <h4 class="vault-title">🖼️ FOTOS</h4>
                <p class="text-secondary small mb-2">Descarga todas las imágenes desde Bunny CDN:</p>
                <ul class="text-secondary small mb-4 ps-3" style="line-height: 1.8;">
                    <li>Fotos de productos</li>
                    <li>Logo institucional</li>
                    <li>Comprobantes locales (si existen)</li>
                </ul>
                <div class="alert alert-light border-0 py-2 px-3 small mb-0" style="background: #fef9c3;">
                    <i class="fas fa-clock text-warning me-1"></i> Puede tardar según la cantidad de fotos
                </div>
            </div>
            <button class="btn btn-download" style="background: #3b82f6;" onclick="startBackupProcess('media')">
                <i class="fas fa-images me-2"></i> DESCARGAR FOTOS
            </button>
        </div>
    </div>

    <!-- 3. CERTIFICADOS -->
    <div class="col-md-4">
        <div class="vault-card">
            <div>
                <h4 class="vault-title">🔐 CERTIFICADOS</h4>
                <p class="text-secondary small mb-2">Resguardo de archivos críticos de AFIP/ARCA:</p>
                <ul class="text-secondary small mb-4 ps-3" style="line-height: 1.8;">
                    <li>Certificado digital (.crt)</li>
                    <li>Clave privada (.key)</li>
                    <li>Instrucciones de restauración</li>
                </ul>
                <div class="alert alert-light border-0 py-2 px-3 small mb-0" style="background: #fef2f2;">
                    <i class="fas fa-exclamation-triangle text-danger me-1"></i> Guardá este archivo en lugar seguro
                </div>
            </div>
            <button class="btn btn-download" style="background: #0f172a;" onclick="startBackupProcess('tokens')">
                <i class="fas fa-key me-2"></i> DESCARGAR CERTIFICADOS
            </button>
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
                    <h4 class="fw-bold mb-3">Ubicación de Resguardo</h4>
                    <p class="text-secondary px-4">
                        Elija la carpeta local donde se guardarán los archivos. Se recordará para futuros resguardos.
                    </p>
                    
                    <div class="bg-light p-3 rounded mb-3 text-start mx-auto border shadow-sm" style="max-width: 450px;">
                        <small class="text-muted text-uppercase fw-bold"><i class="fas fa-folder-open text-warning me-1"></i> CARPETA DESTINO:</small>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span id="dirDisplay" class="fw-bold text-primary text-truncate pe-3">Verificando acceso...</span>
                            <button class="btn btn-sm btn-outline-primary fw-bold" onclick="selectDirectory()">CAMBIAR</button>
                        </div>
                    </div>

                    <p class="text-danger fw-bold small mt-3">Volumen estimado: <span class="badge bg-dark fs-6 ms-1" id="estimatedSize">Calculando...</span></p>

                    <div class="d-flex gap-3 justify-content-center mt-4">
                        <button class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-bold" onclick="nextStep(1)">VOLVER</button>
                        <button class="btn btn-success px-5 py-2 rounded-pill fw-bold" id="btnNextStep3" onclick="nextStep(3)" disabled>CONTINUAR <i class="bi bi-arrow-right ms-1"></i></button>
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
    let currentDirHandle = null;

    // --- INDEXED DB LOGIC PARA RECORDAR LA CARPETA ---
    const dbPromise = new Promise((resolve, reject) => {
        const req = indexedDB.open('BackupDB', 1);
        req.onupgradeneeded = (e) => e.target.result.createObjectStore('handles');
        req.onsuccess = () => resolve(req.result);
        req.onerror = () => reject(req.error);
    });

    async function saveDirHandle(handle) {
        const db = await dbPromise;
        db.transaction('handles', 'readwrite').objectStore('handles').put(handle, 'backupDir');
    }

    async function getDirHandle() {
        const db = await dbPromise;
        return new Promise(resolve => {
            const req = db.transaction('handles', 'readonly').objectStore('handles').get('backupDir');
            req.onsuccess = () => resolve(req.result);
            req.onerror = () => resolve(null);
        });
    }

    async function checkSavedDir() {
        try {
            currentDirHandle = await getDirHandle();
            if (currentDirHandle) {
                // Verify permission silently
                const perm = await currentDirHandle.queryPermission({mode: 'readwrite'});
                if (perm === 'granted') {
                    document.getElementById('dirDisplay').innerText = "📂 " + currentDirHandle.name;
                    document.getElementById('btnNextStep3').disabled = false;
                    return;
                } else {
                    document.getElementById('dirDisplay').innerText = "Permiso requerido para " + currentDirHandle.name;
                    document.getElementById('btnNextStep3').disabled = true;
                    // Alerta o indicación de que debe dar clic en cambiar o seleccionar
                }
            } else {
                document.getElementById('dirDisplay').innerText = 'Ninguna carpeta seleccionada';
                document.getElementById('btnNextStep3').disabled = true;
            }
        } catch(e) {
            document.getElementById('dirDisplay').innerText = 'No soportado o denegado';
        }
    }

    async function selectDirectory() {
        try {
            if (!window.showDirectoryPicker) {
                alert("Tu navegador no soporta la selección de carpetas. Se descargará de forma normal.");
                document.getElementById('btnNextStep3').disabled = false;
                return;
            }
            
            // Si ya hay un handle pero requiere permiso
            if (currentDirHandle && await currentDirHandle.queryPermission({mode:'readwrite'}) !== 'granted') {
                const reqPerm = await currentDirHandle.requestPermission({mode: 'readwrite'});
                if (reqPerm === 'granted') {
                    document.getElementById('dirDisplay').innerText = "📂 " + currentDirHandle.name;
                    document.getElementById('btnNextStep3').disabled = false;
                    return;
                }
            }

            const handle = await window.showDirectoryPicker({mode: 'readwrite'});
            await saveDirHandle(handle);
            currentDirHandle = handle;
            document.getElementById('dirDisplay').innerText = "📂 " + handle.name;
            document.getElementById('btnNextStep3').disabled = false;
        } catch(e) {
            console.error("Selección cancelada", e);
        }
    }
    // ---------------------------------------------------

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
        
        checkSavedDir(); // Verificar handle previo
        wizardModal.show();
    }

    function nextStep(step) {
        document.getElementById('step-1').style.display = 'none';
        document.getElementById('step-2').style.display = 'none';
        document.getElementById('step-3').style.display = 'none';
        
        document.getElementById('step-' + step).style.display = 'block';

        if(step === 3) {
            document.getElementById('finalActionBtn').onclick = async function() {
                const btn = this;
                
                if (!window.showDirectoryPicker || !currentDirHandle) {
                    // Fallback clásico
                    window.location.href = "{{ route('empresa.backup.download') }}?type=" + currentType;
                    wizardModal.hide();
                    return;
                }

                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>CREANDO ARCHIVO...';
                
                try {
                    // Re-verificar permiso antes de escribir (Crucial para handles guardados)
                    if (currentDirHandle) {
                        const perm = await currentDirHandle.queryPermission({mode: 'readwrite'});
                        if (perm !== 'granted') {
                            const reqPerm = await currentDirHandle.requestPermission({mode: 'readwrite'});
                            if (reqPerm !== 'granted') {
                                throw new Error("No se otorgaron los permisos necesarios para escribir en la carpeta.");
                            }
                        }
                    }

                    // Descargar el archivo Blob
                    const res = await fetch("{{ route('empresa.backup.download') }}?type=" + currentType);
                    if (!res.ok) {
                        const errorData = await res.json().catch(() => ({}));
                        throw new Error(errorData.error || `Error del servidor (${res.status})`);
                    }
                    
                    const blob = await res.blob();
                    
                    let finalName = `Backup_${currentType}_${Date.now()}.csv`;
                    const disp = res.headers.get('Content-Disposition');
                    if (disp && disp.indexOf('filename=') !== -1) {
                        const regex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        const match = regex.exec(disp);
                        if (match != null && match[1]) {
                            finalName = match[1].replace(/['"]/g, '');
                        }
                    }

                    // Escribir en el disco en la carpeta recordada
                    const fileHandle = await currentDirHandle.getFileHandle(finalName, { create: true });
                    const writable = await fileHandle.createWritable();
                    await writable.write(blob);
                    await writable.close();
                    
                    Swal.fire({
                        icon: 'success',
                        title: '¡Resguardo Exitoso!',
                        text: `El archivo ${finalName} se guardó correctamente en la carpeta seleccionada.`,
                        confirmButtonColor: '#22c55e'
                    });
                    
                    wizardModal.hide();
                } catch(e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Fallo en el Resguardo',
                        text: e.message,
                        footer: 'Si el problema persiste, intente cambiar la carpeta de destino.'
                    });
                    console.error(e);
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = 'SÍ, INICIAR DESCARGA';
                }
            };
        }
    }

</script>
@endsection
