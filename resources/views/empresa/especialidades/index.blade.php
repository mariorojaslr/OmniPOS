@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4 pb-5">

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-0">🏥 Especialidades Médicas</h2>
            <p class="text-muted small mb-0">Gestione la nómina de especialidades clínicas de su centro de salud.</p>
        </div>
        <button class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm" style="background: linear-gradient(135deg, #0284c7, #0369a1); border: none;" data-bs-toggle="modal" data-bs-target="#modalEspecialidad">
            <i class="bi bi-plus-lg me-2"></i> NUEVA ESPECIALIDAD
        </button>
    </div>

    {{-- BUSCADOR INTELIGENTE EN TIEMPO REAL (NUEVO) --}}
    <div class="mb-4 d-flex justify-content-end">
        <div style="max-width: 400px; width: 100%;">
            <div class="input-group shadow-sm border-2 rounded-3 overflow-hidden">
                <span class="input-group-text bg-white border-end-0 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="especialidadSearchInput" class="form-control border-start-0 ps-0 form-control-sm py-2" placeholder="Buscar por nombre o descripción...">
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr class="x-small text-muted text-uppercase">
                        <th class="ps-4 py-3">Especialidad</th>
                        <th class="py-3">Descripción</th>
                        <th class="py-3 text-center">Nº de Prácticas</th>
                        <th class="py-3 text-center">Nº de Profesionales</th>
                        <th class="py-3 text-center">Estado</th>
                        <th class="py-3 text-center" width="80">Editar</th>
                        <th class="py-3 text-center pe-4" width="80">Borrar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($especialidadesData as $esp)
                        <tr class="especialidad-main-row">
                            <td class="ps-4 fw-bold text-dark">
                                <span class="d-flex align-items-center gap-2">
                                    <span class="p-2 rounded bg-info bg-opacity-10 text-info d-inline-flex">
                                        <i class="bi bi-heart-pulse-fill" style="font-size: 1.1rem;"></i>
                                    </span>
                                    {{ $esp->nombre }}
                                </span>
                            </td>
                            <td class="text-muted small text-truncate" style="max-width: 300px;">
                                {{ $esp->descripcion ?: 'Sin descripción' }}
                            </td>
                            <td class="text-center fw-bold">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill cursor-pointer hover-badge-action shadow-sm" onclick="toggleEspecialidadAccordion('{{ $esp->id }}', 'prácticas', event)" title="Ver prácticas médicas" style="cursor: pointer;">
                                    {{ $esp->cant_servicios }}
                                    <i class="bi bi-chevron-down ms-1 accordion-chevron-practicas-{{ $esp->id }}" style="transition: transform 0.2s ease;"></i>
                                </span>
                            </td>
                            <td class="text-center fw-bold">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill cursor-pointer hover-badge-action shadow-sm" onclick="toggleEspecialidadAccordion('{{ $esp->id }}', 'médicos', event)" title="Ver médicos asignados" style="cursor: pointer;">
                                    {{ $esp->cant_profesionales }}
                                    <i class="bi bi-chevron-down ms-1 accordion-chevron-medicos-{{ $esp->id }}" style="transition: transform 0.2s ease;"></i>
                                </span>
                            </td>
                            <td class="text-center">
                                @if($esp->activo)
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                        ACTIVO
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                        INACTIVO
                                    </span>
                                @endif
                            </td>
                            <td class="text-center" onclick="event.stopPropagation()">
                                <button class="btn btn-sm text-dark border-0 animate-hover" onclick="editarEspecialidad({{ json_encode($esp) }})" title="Editar">
                                    <i class="bi bi-pencil-square fs-5 text-primary"></i>
                                </button>
                            </td>
                            <td class="text-center pe-4" onclick="event.stopPropagation()">
                                <form action="{{ route('empresa.especialidades.destroy', $esp->id) }}" method="POST" class="d-inline m-0">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm text-danger border-0 animate-hover" onclick="return confirm('¿Está seguro de eliminar esta especialidad?')" title="Borrar">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- Acordeón 1: Lista de Prácticas --}}
                        <tr class="accordion-row border-0" id="accordion-practicas-{{ $esp->id }}" style="display: none; background-color: #fbfcfd;">
                            <td colspan="7" class="p-0 border-0">
                                <div class="px-5 py-4 border-start border-secondary border-4 rounded-end shadow-inner" style="background-color: #f8fafc; margin: 5px 15px 15px 15px;">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                            <i class="bi bi-activity text-secondary"></i>
                                            Prácticas y Prestaciones Médicas ({{ count($esp->servicios) }})
                                        </h6>
                                        <span class="small text-muted">Especialidad: <strong>{{ $esp->nombre }}</strong></span>
                                    </div>

                                    @if(count($esp->servicios) > 0)
                                        <div class="list-group border-0 shadow-sm rounded-3 overflow-hidden bg-white">
                                            @foreach($esp->servicios as $serv)
                                                <div class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2 px-4 border-bottom hover-row-sleek" style="border-color: #f1f5f9;">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="p-1.5 rounded-circle bg-secondary bg-opacity-10 text-secondary d-inline-flex small">
                                                            <i class="bi bi-file-medical-fill"></i>
                                                        </span>
                                                        <span class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $serv['nombre'] }}</span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-4">
                                                        <span class="text-muted small"><i class="bi bi-clock-history me-1"></i> {{ $serv['duracion_minutos'] }} min</span>
                                                        <span class="fw-bold text-success text-end" style="min-width: 100px;">${{ number_format($serv['precio'], 2, ',', '.') }}</span>
                                                        @if($serv['activo'])
                                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-bold" style="font-size: 0.65rem; padding: 2px 8px;">Activo</span>
                                                        @else
                                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill fw-bold" style="font-size: 0.65rem; padding: 2px 8px;">Inactivo</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4 bg-white rounded-3 shadow-sm border border-dashed">
                                            <i class="bi bi-clipboard-x-fill text-secondary opacity-50 fs-2 d-block mb-2"></i>
                                            <span class="small fw-semibold text-secondary">No hay prácticas médicas cargadas en esta especialidad</span>
                                            <p class="x-small text-muted mb-0 mt-1">Cargue nuevas prestaciones/servicios asignando la categoría correspondiente.</p>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- Acordeón 2: Lista de Médicos (Renglones Compactos) --}}
                        <tr class="accordion-row border-0" id="accordion-medicos-{{ $esp->id }}" style="display: none; background-color: #fbfcfd;">
                            <td colspan="7" class="p-0 border-0">
                                <div class="px-5 py-4 border-start border-primary border-4 rounded-end shadow-inner" style="background-color: #f8fafc; margin: 5px 15px 15px 15px;">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                            <i class="bi bi-people-fill text-primary"></i>
                                            Profesionales Médicos Asignados ({{ count($esp->medicos) }})
                                        </h6>
                                        <span class="small text-muted">Especialidad: <strong>{{ $esp->nombre }}</strong></span>
                                    </div>

                                    @if(count($esp->medicos) > 0)
                                        <div class="list-group border-0 shadow-sm rounded-3 overflow-hidden bg-white">
                                            @foreach($esp->medicos as $medico)
                                                <div class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2 px-4 border-bottom hover-row-sleek" style="border-color: #f1f5f9;">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="p-1.5 rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex small">
                                                            <i class="bi bi-person-fill-gear"></i>
                                                        </span>
                                                        <span class="fw-bold text-dark" style="font-size: 0.95rem;">Dr/a. {{ $medico['name'] }}</span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-4">
                                                        <span class="text-muted small"><i class="bi bi-envelope me-1"></i> {{ $medico['email'] }}</span>
                                                        @if($medico['activo'])
                                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-bold d-inline-flex align-items-center gap-1" style="font-size: 0.65rem; padding: 3px 8px;">
                                                                <span style="width: 6px; height: 6px;" class="bg-success rounded-circle d-inline-block"></span>
                                                                Disponible
                                                            </span>
                                                        @else
                                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill fw-bold d-inline-flex align-items-center gap-1" style="font-size: 0.65rem; padding: 3px 8px;">
                                                                <span style="width: 6px; height: 6px;" class="bg-danger rounded-circle d-inline-block"></span>
                                                                No Disponible
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4 bg-white rounded-3 shadow-sm border border-dashed">
                                            <i class="bi bi-person-x-fill text-secondary opacity-50 fs-2 d-block mb-2"></i>
                                            <span class="small fw-semibold text-secondary">No hay profesionales asignados a esta especialidad</span>
                                            <p class="x-small text-muted mb-0 mt-1">Asigne esta especialidad a sus médicos desde la sección de Personal.</p>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-medical fs-1 opacity-25 d-block mb-3"></i>
                                No hay especialidades configuradas.
                            </td>
                        </tr>
                    @endforelse

                    {{-- Fila dinámica para cuando la búsqueda no da resultados --}}
                    <tr id="no-results-row" style="display: none;">
                        <td colspan="100" class="text-center py-5 text-muted bg-light bg-opacity-50">
                            <div class="py-3">
                                <i class="bi bi-search-heart fs-1 text-secondary d-block mb-2"></i>
                                <span class="fw-bold fs-5">No se encontraron especialidades</span>
                                <p class="small text-muted mb-0 mt-1">Prueba escribiendo otras palabras clave (ej: Cardiología, Clínica, Pediatría)</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL CREAR/EDITAR --}}
<div class="modal fade" id="modalEspecialidad" tabindex="-1">
    <div class="modal-dialog">
        <form id="formEspecialidad" action="{{ route('empresa.especialidades.store') }}" method="POST" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            @csrf
            <div style="height: 6px; background: linear-gradient(90deg, #0284c7, #10b981);"></div>
            <div id="methodField"></div>
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="tituloModal">Nueva Especialidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">Nombre de la Especialidad</label>
                    <input type="text" name="nombre" id="nombre" class="form-control border-slate rounded-3" placeholder="Ej: Cardiología, Pediatría, Traumatología" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="form-control border-slate rounded-3" placeholder="Detalle informativo sobre la especialidad o consultorios..."></textarea>
                </div>
                <div class="mb-3" id="estadoContainer" style="display: none;">
                    <label class="form-label fw-bold small text-secondary">Estado Operativo</label>
                    <select name="activo" id="activo" class="form-select border-slate rounded-3">
                        <option value="1">Activo (Habilitado)</option>
                        <option value="0">Inactivo (Suspendido)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" style="background: linear-gradient(135deg, #0284c7, #0369a1); border: none;">GUARDAR ESPECIALIDAD</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editarEspecialidad(esp) {
        const form = document.getElementById('formEspecialidad');
        form.action = `/empresa/especialidades/${esp.id}`;
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('tituloModal').innerText = 'Editar Especialidad';
        
        document.getElementById('nombre').value = esp.nombre;
        document.getElementById('descripcion').value = esp.descripcion || '';
        document.getElementById('activo').value = esp.activo ? "1" : "0";
        document.getElementById('estadoContainer').style.display = 'block';
        
        new bootstrap.Modal(document.getElementById('modalEspecialidad')).show();
    }

    // Resetear formulario al cerrar modal
    document.getElementById('modalEspecialidad').addEventListener('hidden.bs.modal', function () {
        const form = document.getElementById('formEspecialidad');
        form.action = "{{ route('empresa.especialidades.store') }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('tituloModal').innerText = 'Nueva Especialidad';
        form.reset();
        document.getElementById('estadoContainer').style.display = 'none';
    });

    // MOTOR DE BÚSQUEDA MULTI-PALABRA EN TIEMPO REAL
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('especialidadSearchInput');
        const tableRows = document.querySelectorAll('table tbody tr');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                
                // Esconder todos los acordeones al buscar para evitar desalineación
                const accordions = document.querySelectorAll('.accordion-row');
                accordions.forEach(acc => acc.style.display = 'none');
                const chevrons = document.querySelectorAll('[class*="accordion-chevron-"]');
                chevrons.forEach(chev => chev.style.transform = '');

                if (query === '') {
                    tableRows.forEach(row => {
                        if (row.id !== 'no-results-row' && !row.classList.contains('accordion-row')) {
                            row.style.display = '';
                        }
                    });
                    const noResultsRow = document.getElementById('no-results-row');
                    if (noResultsRow) noResultsRow.style.display = 'none';
                    return;
                }

                // Dividir los términos por espacios
                const searchWords = query.split(/\s+/);
                let visibleRowsCount = 0;

                tableRows.forEach(row => {
                    if (row.id === 'no-results-row' || row.classList.contains('accordion-row')) return;

                    // Capturar el contenido de búsqueda
                    const searchableText = row.textContent.toLowerCase();

                    // CADA término buscado debe estar en la fila
                    const matches = searchWords.every(word => searchableText.includes(word));

                    if (matches) {
                        row.style.display = '';
                        visibleRowsCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Mostrar u ocultar la fila de "sin resultados"
                const noResultsRow = document.getElementById('no-results-row');
                if (noResultsRow) {
                    noResultsRow.style.display = (visibleRowsCount === 0) ? '' : 'none';
                }
            });
        }
    });

    // Toggle interactivo inteligente para acordeones de médicos y prácticas
    function toggleEspecialidadAccordion(espId, tipo, event) {
        event.stopPropagation(); // Evitar comportamientos no deseados

        const accordionPracticas = document.getElementById(`accordion-practicas-${espId}`);
        const accordionMedicos = document.getElementById(`accordion-medicos-${espId}`);
        const chevronPracticas = document.querySelector(`.accordion-chevron-practicas-${espId}`);
        const chevronMedicos = document.querySelector(`.accordion-chevron-medicos-${espId}`);

        if (tipo === 'prácticas') {
            // Cerrar el de médicos si estuviera abierto
            if (accordionMedicos) {
                accordionMedicos.style.display = 'none';
                const chevMed = document.querySelector(`.accordion-chevron-medicos-${espId}`);
                if (chevMed) chevMed.style.transform = '';
            }
            // Toggle de prácticas
            if (accordionPracticas) {
                const isHidden = accordionPracticas.style.display === 'none';
                accordionPracticas.style.display = isHidden ? '' : 'none';
                if (chevronPracticas) chevronPracticas.style.transform = isHidden ? 'rotate(180deg)' : '';
            }
        } else if (tipo === 'médicos') {
            // Cerrar el de prácticas si estuviera abierto
            if (accordionPracticas) {
                accordionPracticas.style.display = 'none';
                const chevPrac = document.querySelector(`.accordion-chevron-practicas-${espId}`);
                if (chevPrac) chevPrac.style.transform = '';
            }
            // Toggle de médicos
            if (accordionMedicos) {
                const isHidden = accordionMedicos.style.display === 'none';
                accordionMedicos.style.display = isHidden ? '' : 'none';
                if (chevronMedicos) chevronMedicos.style.transform = isHidden ? 'rotate(180deg)' : '';
            }
        }
    }
</script>

<style>
    .border-slate {
        border: 1px solid #cbd5e1;
    }
    .border-slate:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
    }
    .animate-hover:hover {
        transform: scale(1.1);
        transition: transform 0.2s ease;
    }
    .border-dashed {
        border: 1px dashed #cbd5e1 !important;
    }
    .hover-row-sleek {
        transition: background-color 0.2s ease;
    }
    .hover-row-sleek:hover {
        background-color: #f8fafc !important;
    }
    .hover-badge-action {
        transition: transform 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-badge-action:hover {
        transform: scale(1.08) !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08) !important;
    }
    .animate-ping-small {
        animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
    }
    @keyframes ping {
        75%, 100% {
            transform: scale(2);
            opacity: 0;
        }
    }
</style>
@endsection
