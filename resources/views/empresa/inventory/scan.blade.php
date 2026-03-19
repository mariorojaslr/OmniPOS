@extends('layouts.empresa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            <div class="d-flex justify-content-between align-items-center mb-3 text-white sticky-top py-2" style="background: rgba(0,0,0,0.8); border-radius:10px; padding: 10px; z-index: 1000">
                <h4 class="mb-0 fw-bold">Modo Inventario 📦</h4>
                <a href="{{ route('empresa.products.index') }}" class="btn btn-sm btn-outline-light">Salir</a>
            </div>

            {{-- PANEL ESCÁNER --}}
            <div class="card overflow-hidden border-0 shadow-lg bg-black" style="border-radius:20px;">
                <div id="reader" style="width: 100%; border:0"></div>
                
                {{-- Guía visual superior --}}
                <div class="position-absolute top-50 start-50 translate-middle w-75 h-40 border border-success border-2 rounded-3" 
                     style="pointer-events:none; box-shadow: 0 0 0 1000px rgba(0,0,0,0.6);">
                    <div class="position-absolute top-0 start-50 translate-middle-x bg-success text-white px-2 py-1 small rounded-bottom fw-bold" style="font-size:10px">
                        AJUSTANDO STOCK
                    </div>
                </div>
            </div>

            {{-- CONFIGURACIÓN DE AJUSTE --}}
            <div class="card border-0 shadow mt-3" style="border-radius:15px">
                <div class="card-body">
                    <div class="btn-group w-100 mb-3" role="group">
                        <input type="radio" class="btn-check" name="mode" id="modeSum" value="sum" checked>
                        <label class="btn btn-outline-success py-3 fw-bold" for="modeSum">
                            <i class="bi bi-plus-circle me-1"></i> SUMAR +1
                        </label>

                        <input type="radio" class="btn-check" name="mode" id="modeSet" value="set">
                        <label class="btn btn-outline-primary py-3 fw-bold" for="modeSet">
                            <i class="bi bi-pencil me-1"></i> MANUAL
                        </label>
                    </div>

                    {{-- Este input solo se muestra si el modo es "manual" --}}
                    <div id="manualInput" class="mb-3 d-none">
                        <label class="form-label fw-bold">Cantidad Total</label>
                        <input type="number" id="inputQuantity" class="form-control form-control-lg text-center" value="1" placeholder="Ej: 50">
                    </div>

                    <div id="lastResult" class="text-center p-3 rounded d-none border">
                        <h6 class="text-muted small text-uppercase mb-1">Último ajuste:</h6>
                        <div id="resultName" class="fw-bold fs-5 text-dark">Nombre Producto</div>
                        <div class="mt-2">
                             <span class="badge bg-success fs-6 fw-bold p-2">Nuevo Stock: <span id="resultStock">0</span></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- LOG DE ESCANEOS --}}
            <div class="mt-4">
                <h6 class="text-muted fw-bold small text-uppercase px-2">Actividad Reciente</h6>
                <div id="scanLog" class="list-group list-group-flush shadow-sm rounded-3 overflow-hidden">
                    {{-- Se llena dinámicamente --}}
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Audio de éxito --}}
<audio id="beepSuccess" src="https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3"></audio>

@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrCode = null;
    const reader = document.getElementById('reader');
    const beep = document.getElementById('beepSuccess');
    let isProcessing = false;

    // Al cargar
    document.addEventListener('DOMContentLoaded', () => {
        startScanner();
    });

    // Control de visibilidad del input manual
    document.querySelectorAll('input[name="mode"]').forEach(radio => {
        radio.addEventListener('change', (e) => {
            const manualDiv = document.getElementById('manualInput');
            if(e.target.id === 'modeSet') {
                manualDiv.classList.remove('d-none');
            } else {
                manualDiv.classList.add('d-none');
            }
        });
    });

    function startScanner() {
        html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 250, height: 150 } };
        
        html5QrCode.start(
            { facingMode: "environment" }, 
            config,
            (decodedText) => {
                if(!isProcessing) processScan(decodedText);
            },
            (err) => {}
        ).catch((err) => {
            alert("Error de cámara: " + err);
        });
    }

    async function processScan(code) {
        isProcessing = true;
        
        const mode = document.querySelector('input[name="mode"]:checked').value;
        const qty = (mode === 'sum') ? 1 : document.getElementById('inputQuantity').value;

        try {
            const res = await fetch(`{{ route('inventory.adjust') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    barcode: code,
                    mode: mode,
                    quantity: qty
                })
            });

            const data = await res.json();

            if(data.ok) {
                // Éxito
                beep.play();
                if (navigator.vibrate) navigator.vibrate(100);
                
                showResult(data);
                addToLog(data, code);
            } else {
                alert(data.message);
            }
        } catch (err) {
            console.error(err);
        } finally {
            // Breve delay antes de permitir el próximo escaneo para evitar duplicados accidentales
            setTimeout(() => {
                isProcessing = false;
            }, 1500);
        }
    }

    function showResult(data) {
        const lastResult = document.getElementById('lastResult');
        lastResult.classList.remove('d-none');
        lastResult.classList.add('animate__animated', 'animate__fadeIn');
        document.getElementById('resultName').innerText = data.name;
        document.getElementById('resultStock').innerText = data.stock;
    }

    function addToLog(data, code) {
        const log = document.getElementById('scanLog');
        const item = document.createElement('div');
        item.className = 'list-group-item d-flex justify-content-between align-items-center animate__animated animate__slideInLeft';
        item.innerHTML = `
            <div>
                <small class="text-muted d-block" style="font-size:10px">${new Date().toLocaleTimeString()}</small>
                <div class="fw-bold">${data.name}</div>
                <small class="text-secondary">${code}</small>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-dark border"> Stock: ${data.stock}</span>
            </div>
        `;
        log.prepend(item);
        
        // Limpiar para que el log no crezca infinito en pantalla
        if(log.children.length > 5) log.removeChild(log.lastChild);
    }
</script>

<style>
    body { background: #f8f9fa; }
    #reader video {
        object-fit: cover !important;
        border-radius: 20px;
    }
</style>
@endpush
