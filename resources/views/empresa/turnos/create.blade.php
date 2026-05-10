@extends('layouts.empresa')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Agendar Nuevo Turno</h2>
            <p class="text-muted small mb-0">Completa los datos para reservar una cita.</p>
        </div>
        <a href="{{ route('empresa.turnos.index') }}" class="btn btn-light border rounded-pill px-3">VOLVER</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <form action="{{ route('empresa.turnos.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3 mb-4">
                        {{-- SERVICIO --}}
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">Servicio / Especialidad</label>
                            <select name="servicio_id" id="servicio_id" class="form-select border-2" required onchange="filterProfessionals()">
                                <option value="">Selecciona un servicio...</option>
                                @foreach($servicios->groupBy('categoria') as $cat => $items)
                                    <optgroup label="{{ $cat }}">
                                        @foreach($items as $s)
                                            <option value="{{ $s->id }}" {{ old('servicio_id') == $s->id ? 'selected' : '' }}>{{ $s->nombre }} - ${{ number_format($s->precio, 0) }} ({{ $s->duracion_minutos }} min)</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        {{-- PROFESIONAL --}}
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">Profesional Asignado</label>
                            <select name="user_id" id="user_id" class="form-select border-2" required>
                                <option value="">Selecciona al profesional...</option>
                                @foreach($profesionales as $p)
                                    @php
                                        $especialidades = ($p->profesionalConfig && is_array($p->profesionalConfig->especialidades)) 
                                            ? json_encode($p->profesionalConfig->especialidades) 
                                            : '[]';
                                    @endphp
                                    <option value="{{ $p->id }}" data-especialidades='{{ $especialidades }}' {{ old('user_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- CLIENTE --}}
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">Cliente</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-2"><i class="bi bi-search"></i></span>
                                <input list="list-clientes" id="cliente_search" class="form-control border-2" placeholder="Buscar cliente por nombre..." oninput="updateClientId(this)" value="{{ old('cliente_nombre_manual') }}" required>
                                <input type="hidden" name="client_id" id="client_id" value="{{ old('client_id') }}">
                                <input type="hidden" name="cliente_nombre_manual" id="cliente_nombre_manual" value="{{ old('cliente_nombre_manual') }}">
                                
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalQuickClient" title="Nuevo Cliente">
                                    <i class="bi bi-plus-lg"></i>
                                </button>

                                <datalist id="list-clientes">
                                    @foreach($clientes as $c)
                                        <option data-id="{{ $c->id }}" value="{{ $c->name }} ({{ $c->phone }})"></option>
                                    @endforeach
                                </datalist>
                            </div>
                        </div>

                        {{-- FECHA Y HORA --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Fecha</label>
                            <input type="date" name="fecha" class="form-control border-2" value="{{ old('fecha', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Hora de Inicio</label>
                            <input type="time" name="hora_inicio" class="form-control border-2" value="{{ old('hora_inicio') }}" required>
                        </div>

                        {{-- NOTAS --}}
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">Notas o Pedidos Especiales</label>
                            <textarea name="notas" class="form-control border-2" rows="3" placeholder="Ej: Trae su propio esmalte, alérgica a..., etc.">{{ old('notas') }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow">
                        <i class="bi bi-calendar-check me-2"></i> CONFIRMAR RESERVA
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ALTA RÁPIDA CLIENTE (EXTENDIDO) --}}
<div class="modal fade" id="modalQuickClient" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-plus me-2"></i>Alta Rápida de Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="quickClientAlert" class="alert alert-danger d-none"></div>
                
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold small">Nombre Completo *</label>
                        <input type="text" id="q_name" class="form-control border-2" placeholder="Nombre completo">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Teléfono / WhatsApp</label>
                        <input type="text" id="q_phone" class="form-control border-2" placeholder="Celular">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Email</label>
                        <input type="email" id="q_email" class="form-control border-2" placeholder="correo@ejemplo.com">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small">Dirección de Domicilio</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-2"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" id="q_address" class="form-control border-2" placeholder="Calle, número, barrio...">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold small mb-0">Ubicación Precisa (GPS)</label>
                                <span id="gpsStatus" class="badge bg-secondary">Pendiente</span>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm w-100 fw-bold" onclick="captureGPS()">
                                <i class="bi bi-crosshair me-2"></i> CAPTURAR COORDENADAS ACTUALES
                            </button>
                            <input type="hidden" id="q_lat">
                            <input type="hidden" id="q_lng">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary rounded-pill px-4" onclick="saveQuickClient()">Guardar Cliente</button>
            </div>
        </div>
    </div>
</div>

<script>
    function filterProfessionals() {
        const servicioId = document.getElementById('servicio_id').value;
        const profSelect = document.getElementById('user_id');
        const options = profSelect.querySelectorAll('option');

        // Reset profesional select
        profSelect.value = "";

        options.forEach(opt => {
            if (opt.value === "") {
                opt.style.display = "block";
                return;
            }

            const especs = JSON.parse(opt.getAttribute('data-especialidades') || "[]");
            
            // Si no hay servicio elegido, mostramos todos
            if (!servicioId) {
                opt.style.display = "block";
            } else {
                // Si el servicio está en la lista de especialidades del profesional, lo mostramos
                // Nota: Convertimos a string por si acaso los IDs vienen mezclados
                if (especs.map(String).includes(String(servicioId))) {
                    opt.style.display = "block";
                } else {
                    opt.style.display = "none";
                }
            }
        });
    }

    function updateClientId(input) {
        const val = input.value;
        const opts = document.getElementById('list-clientes').childNodes;
        let found = false;
        for (let i = 0; i < opts.length; i++) {
            if (opts[i].value === val) {
                document.getElementById('client_id').value = opts[i].getAttribute('data-id');
                document.getElementById('cliente_nombre_manual').value = val;
                found = true;
                break;
            }
        }
        if(!found) {
            document.getElementById('client_id').value = '';
            document.getElementById('cliente_nombre_manual').value = val;
        }
    }

    // Lógica para capturar coordenadas GPS
    function captureGPS() {
        const status = document.getElementById('gpsStatus');
        if (!navigator.geolocation) {
            alert("Tu navegador no soporta geolocalización.");
            return;
        }
        
        status.innerText = "Obteniendo...";
        status.className = "badge bg-info";

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                document.getElementById('q_lat').value = pos.coords.latitude;
                document.getElementById('q_lng').value = pos.coords.longitude;
                status.innerText = "Capturada ✅";
                status.className = "badge bg-success";
            },
            (err) => {
                status.innerText = "Error ❌";
                status.className = "badge bg-danger";
                alert("No se pudo obtener la ubicación. Asegúrate de dar permisos.");
            }
        );
    }

    function saveQuickClient() {
        const name = document.getElementById('q_name').value;
        const phone = document.getElementById('q_phone').value;
        const email = document.getElementById('q_email').value;
        const address = document.getElementById('q_address').value;
        const lat = document.getElementById('q_lat').value;
        const lng = document.getElementById('q_lng').value;
        const alertBox = document.getElementById('quickClientAlert');

        if(!name) {
            alertBox.innerText = "El nombre es obligatorio.";
            alertBox.classList.remove('d-none');
            return;
        }

        // Bloquear botón para evitar doble clic
        const btn = event.target;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Guardando...';

        fetch("{{ route('empresa.clientes.quick-store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ name, phone, email, address, lat, lng })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // 1. Inyectar en el buscador principal
                const input = document.getElementById('cliente_search');
                input.value = data.name + (phone ? " (" + phone + ")" : "");
                
                // 2. Establecer IDs ocultos
                document.getElementById('client_id').value = data.id;
                document.getElementById('cliente_nombre_manual').value = data.name;
                
                // 3. Cerrar el modal de forma segura
                const modalEl = document.getElementById('modalQuickClient');
                const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                modal.hide();
                
                // 4. Limpiar campos para la próxima vez
                document.getElementById('q_name').value = '';
                document.getElementById('q_phone').value = '';
                document.getElementById('q_email').value = '';
                document.getElementById('q_address').value = '';
                alertBox.classList.add('d-none');
            } else {
                alertBox.innerText = data.message || "Error al guardar.";
                alertBox.classList.remove('d-none');
            }
        })
        .catch(err => {
            alertBox.innerText = "Error de conexión con el servidor.";
            alertBox.classList.remove('d-none');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerText = 'Guardar Cliente';
        });
    }
</script>
@endsection
