@extends('layouts.app')

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
<style>
    :root {
        --accent-color: #3b82f6;
        --oled-black: #000000;
        --card-bg: #0a0a0a;
        --input-bg: #111111;
    }

    body { 
        background-color: var(--oled-black) !important; 
        color: #f8fafc; 
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
        border: 1px solid rgba(255, 255, 255, 0.08); 
        border-radius: 28px; 
        padding: 2.5rem 1.5rem; 
        box-shadow: 0 20px 50px rgba(0,0,0,0.9);
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
    }

    .neon-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at center, rgba(59, 130, 246, 0.05), transparent 70%);
        pointer-events: none;
    }

    .huge-input { 
        background: transparent; 
        border: none; 
        font-size: 4.5rem; 
        color: #fff; 
        font-weight: 800; 
        text-align: center; 
        width: 100%; 
        outline: none; 
        letter-spacing: -2px;
    }

    .huge-input::placeholder { color: #1e293b; }

    .category-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 25px;
    }

    .category-btn { 
        background: var(--input-bg); 
        border: 1px solid rgba(255, 255, 255, 0.05); 
        border-radius: 20px; 
        padding: 18px 10px; 
        color: #94a3b8; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        text-align: center; 
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .category-btn i { font-size: 1.5rem; }
    .category-btn small { font-weight: 600; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 1px; }

    .category-btn.active { 
        background: var(--accent-color); 
        color: #fff;
        border-color: #60a5fa; 
        box-shadow: 0 0 30px rgba(59, 130, 246, 0.4); 
        transform: translateY(-3px);
    }

    .field-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #64748b;
        margin-bottom: 10px;
        padding-left: 5px;
    }

    .glass-input {
        background: var(--input-bg) !important;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        color: #fff !important;
        padding: 15px 25px !important;
        border-radius: 50px !important;
        font-weight: 500;
        transition: all 0.3s;
    }

    .glass-input:focus {
        border-color: var(--accent-color) !important;
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.2) !important;
    }

    .photo-upload-zone {
        background: linear-gradient(145deg, #0a0a0a, #111);
        border: 2px dashed rgba(59, 130, 246, 0.3);
        border-radius: 22px;
        padding: 25px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        color: #94a3b8;
    }

    .photo-upload-zone.captured {
        border-color: #10b981;
        background: rgba(16, 185, 129, 0.05);
        color: #10b981;
    }

    .submit-btn { 
        background: linear-gradient(135deg, #2563eb, #1d4ed8); 
        border: none; 
        border-radius: 24px; 
        padding: 20px; 
        color: #fff; 
        font-weight: 800; 
        font-size: 1.25rem; 
        margin-top: 30px; 
        box-shadow: 0 15px 40px rgba(37, 99, 235, 0.4); 
        transition: all 0.3s;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .submit-btn:active { transform: scale(0.98); }

    .back-link {
        color: #475569;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 20px;
    }
</style>
@endsection

@section('content')
<div class="quick-app-container">
    
    <div class="header-section">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="text-primary fw-800" style="font-size: 0.7rem; letter-spacing: 2px;">FIELD APP v2.0</span>
            <span class="text-muted" style="font-size: 0.7rem;">{{ now()->format('H:i') }}</span>
        </div>
        <h2 class="fw-800 mb-0">Registrar Gasto</h2>
    </div>

    <form action="{{ route('empresa.gastos.store-quick') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column h-100">
        @csrf
        
        <div class="neon-card">
            <div class="field-label text-center opacity-50">Monto del Comprobante</div>
            <div class="d-flex align-items-center justify-content-center">
                <span class="fs-1 fw-800 text-primary opacity-50" style="margin-right: -10px;">$</span>
                <input type="number" name="amount" class="huge-input" placeholder="0" required autofocus step="0.01" inputmode="decimal">
            </div>
            <div class="text-center mt-2">
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-600" style="font-size: 0.65rem;">
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

        <!-- Botón de Instalación PWA (dinámico) -->
        <button type="button" id="installAppBtn" onclick="installApp()" class="btn btn-outline-primary btn-lg rounded-pill fw-bold border-2 mb-4" style="display:none; color: #3b82f6; border-color: #3b82f6;">
            <i class="bi bi-phone-fill me-2"></i> INSTALAR APP EN TELÉFONO
        </button>

        <a href="{{ route('empresa.dashboard') }}" class="back-link mx-auto">
            <i class="bi bi-arrow-left"></i> Cancelar y volver
        </a>
    </form>
</div>

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

