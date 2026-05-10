@extends('layouts.empresa')

@section('styles')
<!-- FullCalendar 6 -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<style>
    :root {
        --fc-border-color: #f1f5f9;
        --fc-daygrid-dot-event-bg-color: #3b82f6;
    }

    .calendar-container {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.03);
        padding: 20px;
        border: 1px solid rgba(0,0,0,0.05);
    }

    #calendar {
        min-height: 750px;
        font-family: 'Inter', sans-serif;
    }

    /* BOTONES DE MÉTRICAS (FILTROS) */
    .metric-filter {
        background: white; border: 1px solid #e2e8f0; border-radius: 20px;
        padding: 15px 20px; transition: all 0.3s ease; cursor: pointer;
        display: flex; align-items: center; gap: 15px; height: 100%;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .metric-filter:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border-color: #cbd5e1; }
    .metric-filter.active { border-color: #1e293b; background: #1e293b; color: white !important; }
    .metric-filter.active .m-count, .metric-filter.active .m-label { color: white !important; }
    
    .m-icon { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    .m-count { font-size: 1.5rem; font-weight: 800; line-height: 1; }
    .m-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #64748b; }

    /* DEPARTAMENTOS */
    .dept-btn {
        background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 30px;
        padding: 8px 18px; font-size: 0.8rem; font-weight: 700; color: #475569;
        transition: all 0.2s; cursor: pointer; display: flex; align-items: center; gap: 8px;
    }
    .dept-btn:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .dept-btn.active { background: #1e293b; color: white; border-color: #1e293b; }
    .dept-dot { width: 8px; height: 8px; border-radius: 50%; }

    /* FULLCALENDAR OVERRIDES */
    .fc .fc-toolbar-title { font-size: 1.4rem; font-weight: 800; color: #1e293b; }
    .fc .fc-button-primary { background: #fff; border: 1px solid #e2e8f0; color: #1e293b; font-weight: 700; border-radius: 10px; padding: 6px 14px; }
    .fc .fc-button-primary:hover { background: #f8fafc; color: #000; }
    .fc .fc-button-primary:not(:disabled).fc-button-active { background: #1e293b; border-color: #1e293b; color: #fff; }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 pb-5">

    {{-- 1. BOTONES DE MÉTRICAS (DASHBOARD) --}}
    <div class="row g-3 mb-4 mt-3">
        <div class="col-md-2-4 col-sm-6">
            <div class="metric-filter" onclick="filterBy('realizados', this)">
                <div class="m-icon bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle-fill"></i></div>
                <div>
                    <div class="m-count text-success">{{ $stats['realizados'] }}</div>
                    <div class="m-label">Realizados</div>
                </div>
            </div>
        </div>
        <div class="col-md-2-4 col-sm-6">
            <div class="metric-filter" onclick="filterBy('pendientes', this)">
                <div class="m-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="m-count text-warning">{{ $stats['pendientes'] }}</div>
                    <div class="m-label">Pendientes</div>
                </div>
            </div>
        </div>
        <div class="col-md-2-4 col-sm-6">
            <div class="metric-filter active" onclick="filterBy('hoy', this)">
                <div class="m-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-calendar-event"></i></div>
                <div>
                    <div class="m-count text-primary">{{ $stats['hoy'] }}</div>
                    <div class="m-label">Hoy</div>
                </div>
            </div>
        </div>
        <div class="col-md-2-4 col-sm-6">
            <div class="metric-filter" onclick="filterBy('semana', this)">
                <div class="m-icon bg-info bg-opacity-10 text-info"><i class="bi bi-calendar-week"></i></div>
                <div>
                    <div class="m-count text-info">{{ $stats['semana'] }}</div>
                    <div class="m-label">Semana</div>
                </div>
            </div>
        </div>
        <div class="col-md-2-4 col-sm-6">
            <div class="metric-filter" onclick="filterBy('mes', this)">
                <div class="m-icon bg-dark bg-opacity-10 text-dark"><i class="bi bi-calendar-month"></i></div>
                <div>
                    <div class="m-count text-dark">{{ $stats['mes'] }}</div>
                    <div class="m-label">Mes</div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. FILA DE DEPARTAMENTOS --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-muted x-small fw-bold text-uppercase me-2">Departamentos:</span>
            <button class="dept-btn active" onclick="filterDept('todos', this)">Todos</button>
            @foreach($departamentos as $dept)
                <button class="dept-btn" onclick="filterDept('{{ $dept->categoria }}', this)">
                    <span class="dept-dot" style="background: #3b82f6;"></span>
                    {{ $dept->categoria }}
                    <span class="badge bg-white text-dark border ms-1">{{ $dept->total }}</span>
                </button>
            @endforeach
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('empresa.turnos.create') }}" class="btn btn-dark fw-bold rounded-pill px-4 shadow-lg py-2">
                <i class="bi bi-plus-lg me-2"></i> AGENDAR CITA
            </a>
        </div>
    </div>

    {{-- 3. CALENDARIO PRINCIPAL (FULL WIDTH) --}}
    <div class="calendar-container">
        <div id="calendar"></div>
    </div>

</div>

{{-- MODAL DETALLE --}}
<div class="modal fade" id="modalTurno" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Detalle del Turno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="modalContent"></div>
        </div>
    </div>
</div>

<style>
    .col-md-2-4 { width: 20%; flex: 0 0 20%; }
    @media (max-width: 992px) { .col-md-2-4 { width: 50%; flex: 0 0 50%; } }
    @media (max-width: 576px) { .col-md-2-4 { width: 100%; flex: 0 0 100%; } }
</style>

@endsection

@section('scripts')
<script>
    let calendar;

    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale: 'es',
            slotMinTime: '08:00:00',
            slotMaxTime: '22:00:00',
            allDaySlot: false,
            events: "{{ route('empresa.turnos.events') }}",
            eventClick: function(info) { showTurnoDetails(info.event); }
        });
        calendar.render();
    });

    function filterBy(type, btn) {
        document.querySelectorAll('.metric-filter').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        
        // Cambiar vista del calendario según el filtro
        if(type === 'hoy') calendar.changeView('timeGridDay');
        if(type === 'semana') calendar.changeView('timeGridWeek');
        if(type === 'mes') calendar.changeView('dayGridMonth');
        
        // Aquí podrías añadir lógica extra para filtrar por estado (realizados/pendientes)
    }

    function filterDept(dept, btn) {
        document.querySelectorAll('.dept-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        // Lógica de filtrado por departamento (refetch events con parámetro)
    }

    function showTurnoDetails(event) {
        const props = event.extendedProps;
        const html = `
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-person-check fs-2"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">${props.cliente}</h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 x-small">${props.estado.toUpperCase()}</span>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-6"><label class="x-small fw-bold text-muted d-block">Servicio</label><span class="small fw-bold">${props.servicio}</span></div>
                <div class="col-6"><label class="x-small fw-bold text-muted d-block">Profesional</label><span class="small fw-bold">${props.profesional}</span></div>
                <div class="col-12"><label class="x-small fw-bold text-muted d-block">Notas</label><p class="small mb-0">${props.notas || 'Sin observaciones'}</p></div>
            </div>
        `;
        document.getElementById('modalContent').innerHTML = html;
        new bootstrap.Modal(document.getElementById('modalTurno')).show();
    }
</script>
@endsection
