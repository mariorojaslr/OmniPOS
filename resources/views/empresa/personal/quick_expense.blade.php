@extends('layouts.app')

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
<style>
    :root {
        --accent-color: #39ff14; /* Neon green */
        --accent-dim: #1a5c10;
        --bg-color: #000000;
        --card-bg: #040404;
        --text-main: #39ff14;
    }

    body { 
        background-color: var(--bg-color) !important; 
        color: var(--text-main); 
        font-family: 'Outfit', sans-serif; 
        margin: 0; 
        padding: 0; 
        overflow-x: hidden;
    }

    .quick-app-container { 
        min-height: 100vh; 
        display: flex; 
        flex-direction: column; 
        padding: 20px; 
        max-width: 500px;
        margin: 0 auto;
    }

    .header-section {
        padding: 20px 0;
        text-align: center;
    }

    .neon-card { 
        background: var(--card-bg); 
        border: 1px solid var(--accent-dim); 
        border-radius: 28px; 
        padding: 2.5rem 1.5rem; 
        box-shadow: 0 0 20px rgba(57, 255, 20, 0.05);
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
    }

    .huge-input { 
        background: transparent; 
        border: none; 
        font-size: 4.5rem; 
        color: var(--accent-color); 
        font-weight: 800; 
        text-align: center; 
        width: 100%; 
        outline: none; 
        letter-spacing: -2px;
        text-shadow: 0 0 10px rgba(57, 255, 20, 0.4);
    }

    .huge-input::placeholder { color: var(--accent-dim); }

    .category-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 25px;
    }

    .category-btn { 
        background: var(--card-bg); 
        border: 1px solid var(--accent-dim); 
        border-radius: 20px; 
        padding: 18px 10px; 
        color: var(--accent-dim); 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        text-align: center; 
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .category-btn i { font-size: 1.5rem; }
    .category-btn small { font-weight: 700; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 1px; }

    .category-btn.active { 
        background: rgba(57, 255, 20, 0.1); 
        color: var(--accent-color);
        border-color: var(--accent-color); 
        box-shadow: 0 0 15px rgba(57, 255, 20, 0.3); 
        transform: translateY(-3px);
    }

    .field-label {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--accent-color);
        margin-bottom: 10px;
        padding-left: 5px;
        opacity: 0.8;
    }

    .glass-input {
        background: #000 !important;
        border: 1px solid var(--accent-dim) !important;
        color: var(--accent-color) !important;
        padding: 15px 25px !important;
        border-radius: 50px !important;
        font-weight: 600;
        transition: all 0.3s;
    }

    .glass-input:focus {
        border-color: var(--accent-color) !important;
        box-shadow: 0 0 15px rgba(57, 255, 20, 0.2) !important;
    }

    .photo-upload-zone {
        background: #000;
        border: 2px dashed var(--accent-dim);
        border-radius: 22px;
        padding: 25px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        color: var(--accent-color);
    }

    .photo-upload-zone.captured {
        border-color: var(--accent-color);
        background: rgba(57, 255, 20, 0.1);
        color: var(--accent-color);
        box-shadow: 0 0 15px rgba(57, 255, 20, 0.2);
    }

    .submit-btn { 
        background: transparent; 
        border: 2px solid var(--accent-color); 
        border-radius: 24px; 
        padding: 20px; 
        color: var(--accent-color); 
        font-weight: 800; 
        font-size: 1.25rem; 
        margin-top: 30px; 
        box-shadow: 0 0 20px rgba(57, 255, 20, 0.2); 
        transition: all 0.3s;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .submit-btn:hover {
        background: rgba(57, 255, 20, 0.1); 
        box-shadow: 0 0 30px rgba(57, 255, 20, 0.4); 
    }

    .submit-btn:active { transform: scale(0.98); }

    .back-link {
        color: var(--accent-dim);
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 25px;
        transition: color 0.2s;
    }
    .back-link:hover {
        color: var(--accent-color);
    }
</style>
@endsection

@section('content')
<div class="quick-app-container">
    
    <div class="header-section">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="fw-800" style="color: var(--accent-color); font-size: 0.7rem; letter-spacing: 2px; text-shadow: 0 0 5px var(--accent-color);">FIELD APP v2.0</span>
            <span class="" style="color: var(--accent-dim); font-size: 0.7rem;">{{ now()->format('H:i') }}</span>
        </div>
        <h2 class="fw-800 mb-0" style="color: var(--accent-color);">Registrar Gasto</h2>
    </div>

    <form action="{{ route('empresa.gastos.store-quick') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column h-100">
        @csrf
        
        <div class="neon-card">
            <div class="field-label text-center">Monto del Comprobante</div>
            <div class="d-flex align-items-center justify-content-center">
                <span class="fs-1 fw-800" style="color: var(--accent-color); margin-right: -10px; opacity: 0.8">$</span>
                <input type="number" name="amount" class="huge-input" placeholder="0" required autofocus step="0.01" inputmode="decimal">
            </div>
            <div class="text-center mt-2">
                <span class="badge rounded-pill px-3 py-2 fw-600" style="background: rgba(57, 255, 20, 0.1); color: var(--accent-color); border: 1px solid var(--accent-color); font-size: 0.65rem;">
                    SESIÓN DE CAJA ACTIVA
                </span>
            </div>
        </div>

        <div class="mb-4">
            <div class="field-label">Rubro / Categoría</div>
            <div class="category-grid">
                <div class="category-btn active" onclick="selectCat('combustible', this)">
                    <i class="bi bi-fuel-pump"></i>
                    <small>Nafta</small>
                </div>
                <div class="category-btn" onclick="selectCat('materiales', this)">
                    <i class="bi bi-tools"></i>
                    <small>Mat.</small>
                </div>
                <div class="category-btn" onclick="selectCat('comida', this)">
                    <i class="bi bi-cup-hot"></i>
                    <small>Comida</small>
                </div>
                <div class="category-btn" onclick="selectCat('limpieza', this)">
                    <i class="bi bi-water"></i>
                    <small>Limpieza</small>
                </div>
                <div class="category-btn" onclick="selectCat('mantenimiento', this)">
                    <i class="bi bi-gear"></i>
                    <small>Mant.</small>
                </div>
                <div class="category-btn" onclick="selectCat('otros', this)">
                    <i class="bi bi-plus-circle"></i>
                    <small>Otros</small>
                </div>
                <input type="hidden" name="category" id="selectedCategory" value="combustible" required>
            </div>
        </div>

        <div class="mb-4">
            <div class="field-label">Comercio / Proveedor</div>
            <input type="text" name="supplier" class="form-control glass-input" placeholder="Ej: Shell, Corralón, etc." list="proveedoresSugeridos">
            <datalist id="proveedoresSugeridos">
                <option value="Shell">
                <option value="Axion">
                <option value="YPF">
                <option value="Pago Fácil">
                <option value="Supermercado">
            </datalist>
        </div>

        <div class="mb-4">
            <div class="field-label">Comprobante (Foto)</div>
            <label class="photo-upload-zone" id="dropzone">
                <input type="file" name="receipt_photo" accept="image/*" capture="camera" class="d-none" onchange="previewFile(this)">
                <i class="bi bi-camera-fill fs-2"></i>
                <span id="fileText" class="fw-600">CAPTURAR FACTURA</span>
            </label>
        </div>

        <button type="submit" class="submit-btn shadow-lg">
            CONFIRMAR PAGO <i class="bi bi-check-circle-fill ms-2"></i>
        </button>

        <!-- Botón de Instalación PWA (Visible siempre) -->
        <button type="button" id="installAppBtn" onclick="installAppPrompt()" class="btn btn-outline-primary btn-lg rounded-pill fw-bold border-2 mb-4 mt-3" style="color: var(--accent-dim); border-color: var(--accent-dim);">
            <i class="bi bi-phone-fill me-2"></i> INSTALAR APP EN TELÉFONO
        </button>

        <a href="{{ route('empresa.dashboard') }}" class="back-link mx-auto">
            <i class="bi bi-arrow-left"></i> Cancelar y volver
        </a>
    </form>
</div>

<script>
    function installAppPrompt() {
        if (typeof deferredPrompt !== 'undefined' && deferredPrompt) {
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('Usuario aceptó la instalación');
                }
                deferredPrompt = null;
            });
        } else {
            // Caso Apple (iOS) o Navegador sin soporte automático
            alert("📱 INSTRUCCIONES PARA TU CELULAR:\n\n1. Toca el botón COMPARTIR (el icono del cuadrado con la flecha arriba en Safari o los 3 puntos en Chrome).\n2. Elige la opción 'AGREGAR A INICIO' o 'Añadir a pantalla de inicio'.\n\n¡Y listo! Ya tendrás el icono instalado.");
        }
    }
</script>

<script>
    function selectCat(cat, btn) {
        document.getElementById('selectedCategory').value = cat;
        document.querySelectorAll('.category-btn').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');
        
        // Haptic Feedback (si está disponible)
        if (window.navigator.vibrate) {
            window.navigator.vibrate(15);
        }
    }
    
    function previewFile(input) {
        if(input.files.length > 0) {
            const zone = document.getElementById('dropzone');
            zone.classList.add('captured');
            document.getElementById('fileText').innerText = "FOTO CAPTURADA ✅";
            
            // Haptic Feedback
            if (window.navigator.vibrate) {
                window.navigator.vibrate([30, 50, 30]);
            }
        }
    }

    // Auto-focus logic for better mobile UX
    document.querySelector('.huge-input').focus();
</script>
@endsection

