@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Motor de Liquidación</h1>
                    <p class="text-muted">Genera un nuevo cierre de pagos para un profesional.</p>
                </div>
                <a href="{{ route('empresa.liquidaciones.index') }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Historial
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">1. Seleccionar Profesional</label>
                            <select id="profesional_id" class="form-select form-select-lg border-2 border-primary-subtle rounded-3">
                                <option value="">-- Elegir Profesional --</option>
                                @foreach($profesionales as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-info border-0 shadow-sm mb-0 d-none" id="info-profesional">
                                <i class="fas fa-info-circle me-2"></i>
                                <span id="info-text">Selecciona un profesional para ver sus turnos pendientes.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form id="form-liquidacion" action="{{ route('empresa.liquidaciones.store') }}" method="POST" class="d-none">
                @csrf
                <input type="hidden" name="user_id" id="hidden_user_id">
                
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="mb-0 text-dark">2. Seleccionar Turnos a Liquidar</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="tabla-turnos">
                                <thead class="bg-light small text-uppercase fw-bold">
                                    <tr>
                                        <th class="px-4 py-3" style="width: 50px;">
                                            <input type="checkbox" class="form-check-input" id="check-all">
                                        </th>
                                        <th class="py-3">Fecha</th>
                                        <th class="py-3">Cliente</th>
                                        <th class="py-3">Servicio</th>
                                        <th class="py-3 text-end">Monto Servicio</th>
                                        <th class="px-4 py-3 text-end">Comisión Profesional</th>
                                    </tr>
                                </thead>
                                <tbody id="turnos-container">
                                    <!-- Se llena por JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body p-4">
                                <label class="form-label fw-bold text-dark">3. Notas y Referencia</label>
                                <textarea name="notas" class="form-control border-light-subtle rounded-3" rows="4" placeholder="Ej: Pago de la primera quincena de Mayo..."></textarea>
                                <input type="hidden" name="periodo" id="periodo_input">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100">
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title mb-4 opacity-75">Resumen de Liquidación</h5>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total Servicios:</span>
                                        <span class="fw-bold" id="total-servicios">$0,00</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-4 border-bottom border-white border-opacity-25 pb-2">
                                        <span>Total Turnos:</span>
                                        <span class="fw-bold" id="total-cantidad">0</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fs-5">Monto a Pagar:</span>
                                        <span class="fs-2 fw-bold" id="total-pagar">$0,00</span>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-light btn-lg w-100 mt-4 rounded-pill fw-bold shadow-sm" id="btn-submit" disabled>
                                    <i class="fas fa-check-circle me-2 text-primary"></i>Confirmar Liquidación
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div id="loading" class="text-center py-5 d-none">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3 text-muted fw-bold">Consultando turnos pendientes...</p>
            </div>

            <div id="no-turnos" class="text-center py-5 d-none">
                <i class="fas fa-calendar-check fa-4x text-gray-300 mb-3"></i>
                <h4 class="text-muted">No hay turnos pendientes para este profesional.</h4>
                <p class="text-muted">Asegúrate de que los turnos estén marcados como 'Finalizados'.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const selectProf = document.getElementById('profesional_id');
    const formLiq = document.getElementById('form-liquidacion');
    const turnosContainer = document.getElementById('turnos-container');
    const loading = document.getElementById('loading');
    const noTurnos = document.getElementById('no-turnos');
    const infoProf = document.getElementById('info-profesional');
    const checkAll = document.getElementById('check-all');
    const btnSubmit = document.getElementById('btn-submit');
    const hiddenUserId = document.getElementById('hidden_user_id');

    // Elementos de totales
    const elTotalServicios = document.getElementById('total-servicios');
    const elTotalCantidad = document.getElementById('total-cantidad');
    const elTotalPagar = document.getElementById('total-pagar');

    selectProf.addEventListener('change', function() {
        const userId = this.value;
        if(!userId) {
            formLiq.classList.add('d-none');
            return;
        }

        hiddenUserId.value = userId;
        fetchTurnos(userId);
    });

    async function fetchTurnos(userId) {
        loading.classList.remove('d-none');
        formLiq.classList.add('d-none');
        noTurnos.classList.add('d-none');
        infoProf.classList.add('d-none');

        try {
            const response = await fetch(`{{ route('empresa.liquidaciones.pendientes') }}?user_id=${userId}`);
            const data = await response.json();

            loading.classList.add('d-none');

            if(data.turnos.length === 0) {
                noTurnos.classList.remove('d-none');
                return;
            }

            renderTurnos(data.turnos);
            formLiq.classList.remove('d-none');
            updateTotales();

        } catch (error) {
            console.error('Error al cargar turnos:', error);
            alert('Hubo un error al conectar con el servidor.');
            loading.classList.add('d-none');
        }
    }

    function renderTurnos(turnos) {
        turnosContainer.innerHTML = '';
        turnos.forEach(turno => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-4">
                    <input type="checkbox" name="turnos_ids[]" value="${turno.id}" class="form-check-input turno-check" checked data-monto="${turno.monto}" data-comision="${turno.comision_monto}">
                </td>
                <td>
                    <span class="d-block fw-bold">${new Date(turno.fecha).toLocaleDateString()}</span>
                    <small class="text-muted">${turno.hora}</small>
                </td>
                <td>${turno.cliente ? turno.cliente.nombre : 'S/D'}</td>
                <td>${turno.servicio ? turno.servicio.nombre : 'S/D'}</td>
                <td class="text-end">$${parseFloat(turno.monto).toLocaleString('es-AR', {minimumFractionDigits: 2})}</td>
                <td class="px-4 text-end fw-bold text-success">$${parseFloat(turno.comision_monto).toLocaleString('es-AR', {minimumFractionDigits: 2})}</td>
            `;
            turnosContainer.appendChild(row);
        });

        // Re-vincular eventos a los nuevos checkboxes
        document.querySelectorAll('.turno-check').forEach(cb => {
            cb.addEventListener('change', updateTotales);
        });
    }

    checkAll.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.turno-check');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateTotales();
    });

    function updateTotales() {
        const checkboxes = document.querySelectorAll('.turno-check:checked');
        let totalServicios = 0;
        let totalComision = 0;
        let count = checkboxes.length;

        checkboxes.forEach(cb => {
            totalServicios += parseFloat(cb.dataset.monto);
            totalComision += parseFloat(cb.dataset.comision);
        });

        elTotalServicios.innerText = `$${totalServicios.toLocaleString('es-AR', {minimumFractionDigits: 2})}`;
        elTotalCantidad.innerText = count;
        elTotalPagar.innerText = `$${totalComision.toLocaleString('es-AR', {minimumFractionDigits: 2})}`;

        btnSubmit.disabled = count === 0;
    }
</script>
@endsection
