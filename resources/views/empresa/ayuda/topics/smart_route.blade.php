<div class="px-3 pb-5">
    
    <div class="text-center mb-4">
        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
            <i class="bi bi-signpost-split-fill fs-1"></i>
        </div>
        <h4 class="fw-bold text-white mb-2">Manual Smart Route</h4>
        <p class="text-muted small">Maximizá tu tiempo y el de tus choferes.</p>
    </div>

    <div class="alert border-0 bg-info bg-opacity-5 text-info rounded-4 mb-4 small border-start border-4 border-info">
        <i class="bi bi-exclamation-circle-fill me-2"></i> Esta utilidad requiere que los clientes tengan cargada su <strong>Latitud/Longitud</strong> o su <strong>Plus Code</strong> en el perfil.
    </div>

    <div class="mb-5">
        <h6 class="text-white fw-bold mb-4">Pasos para el Recorrido Perfecto:</h6>
        
        {{-- PASO 1 --}}
        <div class="step-card mb-4">
            <div class="d-flex align-items-center gap-2 mb-2 text-primary">
                <span class="fw-800 fs-5">01</span>
                <span class="fw-bold small text-uppercase" style="letter-spacing: 1px;">Selección</span>
            </div>
            <p class="text-muted small">Buscá y agregá los clientes o proveedores que tenés que visitar hoy. Podés agregar tantos como necesites.</p>
        </div>

        {{-- PASO 2 --}}
        <div class="step-card mb-4">
            <div class="d-flex align-items-center gap-2 mb-2 text-primary">
                <span class="fw-800 fs-5">02</span>
                <span class="fw-bold small text-uppercase" style="letter-spacing: 1px;">Optimización</span>
            </div>
            <p class="text-muted small">Tocá el botón <strong>"Calcular Ruta Óptima"</strong>. Nuestro algoritmo procesará las distancias y te las ordenará del 1 al N para que el trayecto sea el más corto.</p>
        </div>

        {{-- PASO 3 --}}
        <div class="step-card">
            <div class="d-flex align-items-center gap-2 mb-2 text-primary">
                <span class="fw-800 fs-5">03</span>
                <span class="fw-bold small text-uppercase" style="letter-spacing: 1px;">Despliegue</span>
            </div>
            <p class="text-muted small">Generá el link de mapas y mandáselo por mensaje al conductor. Él solo tendrá que seguir las indicaciones sin perderse.</p>
        </div>
    </div>

    <div class="card bg-success bg-opacity-5 border-0 rounded-4">
        <div class="card-body p-4">
            <h6 class="text-success fw-bold mb-2 small"><i class="bi bi-currency-dollar me-1"></i> Beneficio Económico:</h6>
            <p class="text-muted small mb-0">
                Un recorrido bien optimizado puede reducir hasta un <strong>30% el gasto de combustible</strong> mensual y aumentar la cantidad de visitas diarias por vehículo.
            </p>
        </div>
    </div>

</div>

<style>
    .fw-800 { font-weight: 800; }
    .step-card { border-left: 1px solid rgba(255,255,255,0.05); padding-left: 1.5rem; }
</style>
