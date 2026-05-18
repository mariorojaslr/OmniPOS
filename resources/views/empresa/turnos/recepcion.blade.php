@extends('layouts.empresa')

@section('content')
<div class="container px-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="mb-1 text-primary fw-bold">🏥 Hub de Recepción y Sala de Espera Activa</h4>
            <p class="text-muted small mb-0">Gestión de admisión en tiempo real, digitalización de documentación pre-consulta y flujo de pacientes hacia Smart TV de Sala de Espera.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <!-- BOTÓN LANZADOR DE TURNERO TV -->
            <a href="{{ route('portal.turnero.index', $empresa->slug ?? 'med-plus') }}" target="_blank" class="btn btn-dark border border-secondary border-opacity-25 rounded-pill px-3 py-2 fw-bold text-white d-flex align-items-center gap-2">
                <i class="bi bi-tv text-warning fs-5"></i> 📺 Abrir Turnero Smart TV
            </a>
            <span id="live_clock" class="badge bg-dark border border-secondary border-opacity-25 text-warning py-2 px-4 rounded-pill fw-bold fs-6 font-monospace">
                🕒 00:00:00
            </span>
        </div>
    </div>

    <!-- BUSCADOR INTELIGENTE EN TIEMPO REAL -->
    <div class="card shadow border-0 bg-white rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-9 col-lg-10">
                    <div class="position-relative">
                        <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-secondary"><i class="bi bi-search fs-5"></i></span>
                        <input type="text" id="fuzzy_search_input" class="form-control border-secondary border-opacity-25 ps-5 py-3 rounded-pill fw-medium" placeholder="Buscar turno por Nombre, DNI, Teléfono, Email o Médico asignado (Búsqueda inteligente fuzzy)..." oninput="debounceSearch()">
                    </div>
                </div>
                <div class="col-md-3 col-lg-2">
                    <button class="btn btn-primary w-100 py-3 rounded-pill fw-bold" onclick="fetchTodayTurnos()">
                        <i class="bi bi-arrow-clockwise me-1"></i> Actualizar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- DUAL PANEL -->
    <div class="row g-4">
        <!-- PANEL IZQUIERDO: TURNOS PROGRAMADOS Y ADMISIÓN -->
        <div class="col-lg-6">
            <div class="card shadow border-0 bg-white rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-calendar-event me-2 text-primary"></i>Turnos de Hoy</h5>
                        <span id="turnos_today_count" class="badge bg-secondary rounded-pill py-1 px-3">0 Turnos</span>
                    </div>

                    <div id="scheduled_list" class="d-flex flex-column gap-3" style="max-height: 600px; overflow-y: auto; padding-right: 5px;">
                        <!-- Se carga asíncronamente -->
                    </div>
                </div>
            </div>
        </div>

        <!-- PANEL DERECHO: SALA DE ESPERA ACTIVA Y CONSULTORIOS -->
        <div class="col-lg-6">
            <div class="card shadow border-0 bg-white rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-people me-2 text-success"></i>Sala de Espera y Flujo</h5>
                        <span id="waiting_room_count" class="badge bg-success rounded-pill py-1 px-3">0 Pacientes</span>
                    </div>

                    <div id="waiting_list" class="d-flex flex-column gap-3" style="max-height: 600px; overflow-y: auto; padding-right: 5px;">
                        <!-- Se carga asíncronamente -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card-turno {
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        padding: 16px;
        transition: all 0.2s ease;
    }
    .card-turno:hover {
        border-color: rgba(59, 130, 246, 0.3);
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.05);
    }
    .badge-compliance {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 5px 12px;
        border-radius: 50px;
    }
    .x-small {
        font-size: 0.75rem;
    }
    .btn-arribo {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        font-weight: 700;
        transition: all 0.2s ease;
    }
    .btn-arribo:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
    }
</style>

<!-- WEB AUDIO CHIMES AND RECEP ACTION JAVASCRIPT -->
<script>
    // Live Clock
    function updateClock() {
        const now = new Date();
        const timeString = now.toTimeString().split(' ')[0];
        document.getElementById('live_clock').innerText = '🕒 ' + timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Debounce search to avoid hammer requests
    let searchTimeout = null;
    function debounceSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(fetchTodayTurnos, 250);
    }

    // Web Audio API Sound Generator
    function playQueueChime(type) {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            
            if (type === 'arribo') {
                const tones = [523.25, 659.25, 783.99]; // C5, E5, G5
                tones.forEach((freq, idx) => {
                    setTimeout(() => {
                        const osc = audioCtx.createOscillator();
                        const gain = audioCtx.createGain();
                        osc.type = 'sine';
                        osc.frequency.value = freq;
                        gain.gain.setValueAtTime(0.01, audioCtx.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.2, audioCtx.currentTime + 0.05);
                        gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.6);
                        osc.connect(gain);
                        gain.connect(audioCtx.destination);
                        osc.start();
                        osc.stop(audioCtx.currentTime + 0.8);
                    }, idx * 120);
                });
            } else if (type === 'llamado') {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'triangle';
                osc.frequency.value = 880; // A5
                gain.gain.setValueAtTime(0.01, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.3, audioCtx.currentTime + 0.05);
                gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 1.2);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 1.4);
            }
        } catch (e) {
            console.warn('Audio Context no disponible en navegador.', e);
        }
    }

    // Traer turnos del servidor y renderizar dinámicamente
    function fetchTodayTurnos() {
        const query = document.getElementById('fuzzy_search_input').value.trim();
        const schedList = document.getElementById('scheduled_list');
        const waitList = document.getElementById('waiting_list');

        fetch(`/empresa/api/recepcion/buscar?search=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    let totalTurnos = data.turnos.length;
                    document.getElementById('turnos_today_count').innerText = `${totalTurnos} Turnos`;

                    let schedHtml = '';
                    let waitHtml = '';
                    let waitingCount = 0;

                    if (totalTurnos === 0) {
                        schedList.innerHTML = `
                            <div class="text-center py-5 text-muted small">
                                <i class="bi bi-calendar-x display-4 opacity-25 d-block mb-2"></i>
                                No se encontraron turnos programados para hoy que coincidan.
                            </div>
                        `;
                        waitList.innerHTML = `
                            <div class="text-center py-5 text-muted small">
                                <i class="bi bi-people display-4 opacity-25 d-block mb-2"></i>
                                Sala de espera vacía en este momento.
                            </div>
                        `;
                        document.getElementById('waiting_room_count').innerText = '0 Pacientes';
                        return;
                    }

                    data.turnos.forEach(turno => {
                        if (turno.estado_cola === 'finalizado') {
                            return; // Ocultar y remover turnos ya atendidos/completados
                        }
                        let studiesHtml = '';
                        if (turno.estudios && turno.estudios.length > 0) {
                            studiesHtml += '<div class="d-flex flex-wrap gap-1 mt-2">';
                            turno.estudios.forEach(st => {
                                studiesHtml += `
                                    <a href="/${st.file_path}" target="_blank" class="badge bg-dark border border-secondary border-opacity-25 text-white text-decoration-none py-1 px-2" style="font-size: 0.68rem;" title="Descargar/Ver documento">
                                        📄 ${st.file_name.substring(0, 15)}... <i class="bi bi-download ms-1 text-info"></i>
                                    </a>
                                `;
                            });
                            studiesHtml += '</div>';
                        }

                        // 1. RENDERIZAR LISTA IZQUIERDA (Admisión y Digitalización)
                        const arriboBtn = turno.hora_arribo 
                            ? `<div class="d-flex flex-column align-items-end gap-1">
                                   <span class="badge-compliance ${turno.puntualidad_class}">${turno.puntualidad}</span>
                                   <span class="x-small text-muted font-monospace">Llegó: ${turno.hora_arribo}</span>
                               </div>`
                            : `<button class="btn btn-sm btn-arribo rounded-pill px-3 py-2" onclick="markArribo(${turno.id})">
                                   <i class="bi bi-check-circle-fill me-1"></i> Fichar Arribo
                               </button>`;

                        const uploadBtn = `
                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-2 mt-1 py-1" style="font-size: 0.7rem; border-style: dashed;" onclick="abrirModalEstudios(${turno.id}, '${turno.paciente_nombre}')">
                                <i class="bi bi-file-earmark-arrow-up text-primary me-1"></i> 📁 Digitalizar Estudio
                            </button>
                        `;

                        const schedCard = `
                            <div class="card-turno bg-light bg-opacity-25 d-flex justify-content-between align-items-center mb-3">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-primary text-white rounded font-monospace">${turno.hora_turno}</span>
                                        <h6 class="fw-bold text-dark mb-0">${turno.paciente_nombre}</h6>
                                    </div>
                                    <div class="x-small text-muted">
                                        <span class="me-2"><i class="bi bi-person-vcard"></i> ${turno.paciente_documento}</span>
                                        <span><i class="bi bi-telephone"></i> ${turno.paciente_telefono}</span>
                                    </div>
                                    <div class="x-small text-dark fw-bold mt-1 d-flex align-items-center gap-1">
                                        👨‍⚕️ Dr. ${turno.doctor_nombre} 
                                    </div>
                                    ${studiesHtml}
                                    <div>
                                        ${uploadBtn}
                                    </div>
                                </div>
                                <div>
                                    ${arriboBtn}
                                </div>
                            </div>
                        `;
                        schedHtml += schedCard;

                        // 2. RENDERIZAR LISTA DERECHA (Sala de Espera Activa y Flujos)
                        const estadosActivos = ['sala_espera', 'llamado', 'en_consulta'];
                        if (estadosActivos.includes(turno.estado_cola)) {
                            waitingCount++;

                            let borderClass = 'border-secondary';
                            let glowClass = '';
                            if (turno.estado_cola === 'llamado') {
                                borderClass = 'border-warning border-2';
                                glowClass = 'shadow-sm animate-pulse';
                            } else if (turno.estado_cola === 'en_consulta') {
                                borderClass = 'border-success border-2';
                            }

                            const stateSelector = `
                                <div class="btn-group btn-group-sm rounded-pill border" role="group">
                                    <input type="radio" class="btn-check" name="status-${turno.id}" id="state-wait-${turno.id}" autocomplete="off" ${turno.estado_cola === 'sala_espera' ? 'checked' : ''} onclick="updateEstadoCola(${turno.id}, 'sala_espera')">
                                    <label class="btn btn-outline-secondary border-0 text-dark small" for="state-wait-${turno.id}" title="Esperando en Sala">🛋️</label>

                                    <input type="radio" class="btn-check" name="status-${turno.id}" id="state-call-${turno.id}" autocomplete="off" ${turno.estado_cola === 'llamado' ? 'checked' : ''} onclick="updateEstadoCola(${turno.id}, 'llamado')">
                                    <label class="btn btn-outline-warning border-0 text-dark small" for="state-call-${turno.id}" title="Llamar al Paciente">🔊</label>

                                    <input type="radio" class="btn-check" name="status-${turno.id}" id="state-office-${turno.id}" autocomplete="off" ${turno.estado_cola === 'en_consulta' ? 'checked' : ''} onclick="updateEstadoCola(${turno.id}, 'en_consulta')">
                                    <label class="btn btn-outline-success border-0 text-dark small" for="state-office-${turno.id}" title="Entrar al Consultorio">🩺</label>

                                    <input type="radio" class="btn-check" name="status-${turno.id}" id="state-done-${turno.id}" autocomplete="off" onclick="updateEstadoCola(${turno.id}, 'finalizado')">
                                    <label class="btn btn-outline-info border-0 text-dark small" for="state-done-${turno.id}" title="Atendido / Finalizar">✅</label>
                                </div>
                            `;

                            const consultorioBtn = `
                                <button class="btn btn-sm btn-dark border border-secondary border-opacity-25 rounded px-2 py-0 x-small text-white" onclick="editarConsultorioMedico(${turno.id}, '${turno.doctor_nombre}')">
                                    📍 Asignar Consultorio / Box
                                </button>
                            `;

                            const waitCard = `
                                <div class="card-turno bg-white border ${borderClass} ${glowClass} d-flex justify-content-between align-items-center mb-3">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="badge bg-secondary font-monospace text-white">${turno.hora_turno}</span>
                                            <h6 class="fw-bold text-dark mb-0">${turno.paciente_nombre}</h6>
                                        </div>
                                        <div class="x-small text-muted">
                                            Médico: <strong>Dr. ${turno.doctor_nombre}</strong>
                                        </div>
                                        <div class="x-small mt-1 text-muted">
                                            Fichado: <strong class="text-primary">${turno.hora_arribo}</strong> (${turno.puntualidad})
                                        </div>
                                        <div class="mt-2">
                                            ${consultorioBtn}
                                        </div>
                                        ${studiesHtml}
                                    </div>
                                    <div class="text-end d-flex flex-column align-items-end gap-2">
                                        ${stateSelector}
                                        <span class="x-small fw-bold text-uppercase ${turno.estado_cola === 'llamado' ? 'text-warning' : (turno.estado_cola === 'en_consulta' ? 'text-success' : 'text-muted')}">
                                            ${turno.estado_cola === 'llamado' ? '🔊 Llamado a Box' : (turno.estado_cola === 'en_consulta' ? '🩺 En Consulta' : '🛋️ Esperando')}
                                        </span>
                                    </div>
                                </div>
                            `;
                            waitHtml += waitCard;
                        }
                    });

                    // Modificar DOM una única vez al final (Cero Parpadeos)
                    schedList.innerHTML = schedHtml || `
                        <div class="text-center py-5 text-muted small">
                            <i class="bi bi-calendar-x display-4 opacity-25 d-block mb-2"></i>
                            No se encontraron turnos programados para hoy que coincidan.
                        </div>
                    `;
                    waitList.innerHTML = waitHtml || `
                        <div class="text-center py-5 text-muted small">
                            <i class="bi bi-people display-4 opacity-25 d-block mb-2"></i>
                            Sala de espera vacía en este momento.
                        </div>
                    `;
                    document.getElementById('waiting_room_count').innerText = `${waitingCount} Pacientes`;
                }
            })
            .catch(err => {
                console.error(err);
                schedList.innerHTML = `<div class="alert alert-danger small">Error de conexión al cargar turnos.</div>`;
            });
    }

    // Modal para digitalizar estudios médicos en recepción
    function abrirModalEstudios(turnoId, pacienteNombre) {
        Swal.fire({
            title: '📁 Digitalizar Estudios Médicos',
            text: `Subir receta, orden o estudio físico presentado por ${pacienteNombre} en recepción`,
            input: 'file',
            inputAttributes: {
                'accept': 'image/*,application/pdf',
                'aria-label': 'Seleccione el estudio digitalizado'
            },
            showCancelButton: true,
            confirmButtonText: '📤 Cargar Documento',
            cancelButtonText: 'Cancelar',
            background: '#0f172a',
            color: '#f3f4f6',
            confirmButtonColor: '#10b981',
            preConfirm: (file) => {
                if (!file) {
                    Swal.showValidationMessage('Debe seleccionar un archivo físico en PDF o Imagen');
                    return false;
                }
                
                const formData = new FormData();
                formData.append('file', file);

                return fetch(`/empresa/api/recepcion/estudios/${turnoId}/subir`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(async res => {
                    if (!res.ok) {
                        if (res.status === 419) {
                            throw new Error('Sesión expirada (Token CSRF). Por favor refresque la página para reautenticar.');
                        }
                        const text = await res.text();
                        try {
                            const errJson = JSON.parse(text);
                            throw new Error(errJson.message || 'Error al procesar subida');
                        } catch(e) {
                            throw new Error('Error de servidor (Código ' + res.status + ')');
                        }
                    }
                    return res.json();
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Error al procesar subida');
                    }
                    return data;
                })
                .catch(err => {
                    Swal.showValidationMessage(`Fallo la subida: ${err.message || err}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                playQueueChime('arribo');
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    background: '#0f172a',
                    color: '#f3f4f6'
                });
                toast.fire({
                    icon: 'success',
                    title: '¡Documento cargado con éxito!'
                });
                fetchTodayTurnos();
            }
        });
    }

    // Modal para asignar y editar consultorio del médico en tiempo real
    function editarConsultorioMedico(turnoId, doctorNombre) {
        Swal.fire({
            title: '📍 Asignar Consultorio / Box',
            text: `Asigna o modifica el número de Box/Consultorio físico del Dr. ${doctorNombre}`,
            input: 'text',
            inputPlaceholder: 'Ej: Consultorio 3, Box B, Planta Alta...',
            showCancelButton: true,
            confirmButtonText: '💾 Guardar Consultorio',
            cancelButtonText: 'Cancelar',
            background: '#0f172a',
            color: '#f3f4f6',
            confirmButtonColor: '#3b82f6',
            preConfirm: (val) => {
                if (!val) {
                    Swal.showValidationMessage('Debe ingresar un Box o Consultorio válido');
                    return false;
                }

                // Obtener ID del doctor desde el turno asignado
                return fetch(`/empresa/api/recepcion/buscar`)
                    .then(res => res.json())
                    .then(data => {
                        const targetTurno = data.turnos.find(t => t.id === turnoId);
                        if (!targetTurno) throw new Error('Turno no encontrado');
                        if (!targetTurno.doctor_id) throw new Error('Médico no asignado a este turno');
                        
                        // Encontrar doctor
                        return fetch(`/empresa/api/recepcion/doctor/${targetTurno.doctor_id}/consultorio`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ consultorio: val })
                        });
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) throw new Error(data.message);
                        return data;
                    })
                    .catch(err => Swal.showValidationMessage(err));
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    background: '#0f172a',
                    color: '#f3f4f6'
                });
                toast.fire({
                    icon: 'success',
                    title: '¡Consultorio guardado!'
                });
                fetchTodayTurnos();
            }
        });
    }

    // Fichar Arribo del Paciente
    function markArribo(id) {
        fetch(`/empresa/api/recepcion/arribo/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                playQueueChime('arribo');
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    background: '#0f172a',
                    color: '#f3f4f6'
                });
                toast.fire({
                    icon: 'success',
                    title: data.message
                });
                fetchTodayTurnos();
            }
        })
        .catch(err => console.error(err));
    }

    // Cambiar estado en Sala de Espera (llamado, en_consulta, finalizado)
    function updateEstadoCola(id, nuevoEstado) {
        fetch(`/empresa/api/recepcion/estado/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ estado_cola: nuevoEstado })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (nuevoEstado === 'llamado') {
                    playQueueChime('llamado');
                }
                
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    background: '#0f172a',
                    color: '#f3f4f6'
                });
                toast.fire({
                    icon: 'success',
                    title: data.message
                });
                
                fetchTodayTurnos();
            }
        })
        .catch(err => console.error(err));
    }

    // Carga inicial
    document.addEventListener('DOMContentLoaded', fetchTodayTurnos);
</script>
@endsection
