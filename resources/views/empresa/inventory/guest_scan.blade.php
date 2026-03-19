<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>MultiPOS - Modo Inventario</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body { background: #000; color: #fff; font-family: 'Inter', sans-serif; overflow: hidden; position: fixed; width: 100%; height: 100%; }
        #reader { width: 100%; height: 100vh !important; border: 0 !important; }
        #reader video { object-fit: cover !important; width: 100% !important; height: 100vh !important; }

        .ui-overlay { position: absolute; z-index: 100; left: 0; width: 100%; }
        .ui-top { top: 0; padding: 15px; background: linear-gradient(to bottom, rgba(0,0,0,0.8), transparent); }
        .ui-bottom { bottom: 0; padding: 20px; background: linear-gradient(to top, rgba(0,0,0,0.9), transparent); }

        .scan-guide {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 250px; height: 180px; border: 2px solid rgba(25, 135, 84, 0.8);
            border-radius: 20px; box-shadow: 0 0 0 2000px rgba(0,0,0,0.5); pointer-events: none;
        }

        .mode-badge { background: #198754; color: white; padding: 10px 20px; border-radius: 50px; font-weight: bold; font-size: 14px; box-shadow: 0 4px 15px rgba(25, 135, 84, 0.4); }
        .last-update { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-bottom: 15px; }
        
        /* Ocultar elementos innecesarios del lector */
        #reader__dashboard, #reader__status_span { display: none !important; }
    </style>
</head>

<body>

    {{-- INTERFAZ SUPERIOR --}}
    <div class="ui-overlay ui-top d-flex justify-content-between align-items-center">
        <div>
            <h6 class="mb-0 fw-bold fs-5">Terminal Inventario 🏷️</h6>
            <small class="text-white-50">Sesión Activa - MultiPOS</small>
        </div>
        <div class="mode-badge animate__animated animate__fadeIn">
            <i class="bi bi-broadcast me-1"></i> EN VIVO
        </div>
    </div>

    {{-- GUÍA VISUAL --}}
    <div class="scan-guide">
        <div class="position-absolute top-0 start-50 translate-middle-x bg-success text-white px-2 py-0 small rounded-bottom fw-bold" style="font-size:10px">
            APUNTA AL CÓDIGO
        </div>
    </div>

    {{-- ESCÁNER --}}
    <div id="reader"></div>

    {{-- INTERFAZ INFERIOR --}}
    <div class="ui-overlay ui-bottom pb-4">
        
        {{-- Resultados rápidos --}}
        <div id="resultBox" class="last-update d-none">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div id="resName" class="fw-bold fs-5">Nombre del Producto</div>
                    <small id="resCode" class="text-white-50">Código: 123456</small>
                </div>
                <div class="text-end">
                    <div class="small opacity-75">NUEVO STOCK</div>
                    <div id="resStock" class="fw-bold fs-1 text-success">0</div>
                </div>
            </div>
        </div>

        <div class="row gx-2">
            <div class="col-8">
                <div class="bg-dark p-3 rounded-pill border border-secondary d-flex align-items-center px-4 h-100 shadow-lg">
                    <span id="labelMode" class="fw-bold text-success me-2">MODO: SUMAR +1</span>
                </div>
            </div>
            <div class="col-4">
                <button onclick="toggleMode()" class="btn btn-outline-light w-100 rounded-pill py-3 h-100">
                    <i class="bi bi-arrow-repeat me-1"></i> CAMBIAR
                </button>
            </div>
        </div>

    </div>

    {{-- MODAL MANUAL (Se activa al cambiar modo) --}}
    <div class="modal fade" id="manualModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered mx-3">
            <div class="modal-content bg-dark text-white border-secondary" style="border-radius:20px">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Modo Manual</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p class="text-white-50">Ingresa la cantidad que deseas establecer:</p>
                    <input type="number" id="manualQty" class="form-control form-control-lg bg-black border-secondary text-white text-center fs-1 fw-bold mb-4" value="50">
                    <button onclick="setManualMode()" class="btn btn-success btn-lg w-100 py-3 rounded-pill fw-bold">CONFIRMAR MODO</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Audio de éxito --}}
    <audio id="beep" src="https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3"></audio>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let currentMode = 'sum';
        let customQty = 1;
        let isProcessing = false;
        const beep = document.getElementById('beep');
        const manualModal = new bootstrap.Modal(document.getElementById('manualModal'));

        function toggleMode() {
            if(currentMode === 'sum') {
                manualModal.show();
            } else {
                currentMode = 'sum';
                document.getElementById('labelMode').innerText = 'MODO: SUMAR +1';
                document.getElementById('labelMode').className = 'fw-bold text-success me-2';
            }
        }

        function setManualMode() {
            currentMode = 'set';
            customQty = document.getElementById('manualQty').value;
            document.getElementById('labelMode').innerText = `MODO: FIJAR EN ${customQty}`;
            document.getElementById('labelMode').className = 'fw-bold text-primary me-2';
            manualModal.hide();
        }

        // Configuración Cámara
        const html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 15, qrbox: { width: 250, height: 180 } };

        html5QrCode.start(
            { facingMode: "environment" }, config,
            (decodedText) => { if(!isProcessing) processScan(decodedText); },
            (err) => {}
        ).catch((err) => alert("Cámara bloqueada: " + err));

        async function processScan(code) {
            isProcessing = true;
            
            try {
                const response = await fetch(`{{ route('inventory.guest-adjust') }}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ barcode: code, mode: currentMode, quantity: customQty })
                });

                const data = await response.json();

                if(data.ok) {
                    beep.play();
                    if(navigator.vibrate) navigator.vibrate(100);
                    showStatus(data, code);
                } else {
                    alert(data.message);
                }
            } catch (err) {
                console.error(err);
            } finally {
                setTimeout(() => { isProcessing = false; }, 1500); // Evitar escaneo rápido doble
            }
        }

        function showStatus(data, code) {
            const box = document.getElementById('resultBox');
            box.classList.remove('d-none');
            box.classList.add('animate__animated', 'animate__headShake');
            
            document.getElementById('resName').innerText = data.name;
            document.getElementById('resCode').innerText = "Cód: " + code;
            document.getElementById('resStock').innerText = data.stock;

            setTimeout(() => { box.classList.remove('animate__headShake'); }, 1000);
        }
    </script>
</body>
</html>
