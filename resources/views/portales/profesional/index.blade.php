<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Profesional - {{ $empresa->nombre_comercial }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: {{ $empresa->config->color_primary ?? '#1f6feb' }};
            --bg-body: #f4f7fa;
        }
        body { background-color: var(--bg-body); font-family: 'Inter', sans-serif; color: #1e293b; }
        
        .header-app { background: white; border-bottom: 1px solid #e2e8f0; padding: 15px 0; position: sticky; top: 0; z-index: 1000; }
        
        /* BOTONES DE MÉTRICAS */
        .metric-btn {
            background: white; border: 2px solid transparent; border-radius: 20px;
            padding: 15px 10px; text-align: center; transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); cursor: pointer;
            height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;
        }
        .metric-btn.active { border-color: var(--primary-color); background: var(--primary-color); color: white !important; transform: scale(0.95); }
        .metric-btn.active i, .metric-btn.active .m-count { color: white !important; }
        .m-count { font-size: 1.4rem; font-weight: 800; line-height: 1; margin-bottom: 4px; color: var(--primary-color); }
        .m-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.8; }
        
        /* LISTADO DE TURNOS */
        .turno-card {
            background: white; border: none; border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 10px;
            padding: 12px; border-left: 5px solid var(--primary-color);
        }
        .time-badge { background: #f1f5f9; color: #475569; font-weight: 800; font-size: 0.85rem; padding: 6px 12px; border-radius: 10px; }
        .status-pill { font-size: 0.6rem; font-weight: 800; padding: 3px 8px; border-radius: 20px; text-transform: uppercase; }
        
        /* ACORDEONES */
        .day-group { background: white; border-radius: 15px; margin-bottom: 12px; overflow: hidden; border: 1px solid #e2e8f0; }
        .day-header { padding: 15px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #fff; font-weight: 700; }
        .day-header:hover { background: #f8fafc; }
        .day-content { padding: 10px; display: none; background: #f8fafc; border-top: 1px solid #f1f5f9; }
        
        .view-section { display: none; animation: fadeIn 0.3s ease; }
        .view-section.active { display: block; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <div class="header-app shadow-sm mb-3">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                @if($empresa->config && $empresa->config->logo)
                    <img src="{{ $empresa->config->logo_url }}" alt="Logo" style="height: 32px;">
                @endif
                <div>
                    <h6 class="mb-0 fw-bold small text-truncate" style="max-width: 150px;">{{ $user->name }}</h6>
                    <span class="text-muted" style="font-size: 0.6rem;">PORTAL PRESTADOR</span>
                </div>
            </div>
            <div class="text-end">
                <span class="badge bg-dark rounded-pill x-small">{{ now()->translatedFormat('d M') }}</span>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        
        {{-- BOTONES DE FILTRO (CUADRÍCULA) --}}
        <div class="row g-2 mb-4">
            <div class="col-3">
                <div class="metric-btn" onclick="switchView('cumplidos', this)">
                    <div class="m-count">{{ $totalCumplidos }}</div>
                    <div class="m-label">Hechos</div>
                </div>
            </div>
            <div class="col-3">
                <div class="metric-btn active" onclick="switchView('hoy', this)">
                    <div class="m-count">{{ count($turnosHoy) }}</div>
                    <div class="m-label">Hoy</div>
                </div>
            </div>
            <div class="col-3">
                <div class="metric-btn" onclick="switchView('semana', this)">
                    <div class="m-count">{{ $turnosSemana->flatten()->count() }}</div>
                    <div class="m-label">Semana</div>
                </div>
            </div>
            <div class="col-3">
                <div class="metric-btn" onclick="switchView('mes', this)">
                    <div class="m-count">{{ $turnosMes->flatten()->count() }}</div>
                    <div class="m-label">Mes</div>
                </div>
            </div>
        </div>

        {{-- SECCIÓN: HOY --}}
        <div id="view-hoy" class="view-section active">
            <h6 class="fw-bold mb-3 opacity-50 small"><i class="bi bi-clock me-1"></i> TURNOS PARA HOY</h6>
            @forelse($turnosHoy as $t)
                @include('portales.profesional._turno_item', ['turno' => $t])
            @empty
                <div class="text-center py-5 opacity-50">
                    <i class="bi bi-calendar-x fs-1"></i>
                    <p class="small mt-2">Sin turnos para hoy</p>
                </div>
            @endforelse
        </div>

        {{-- SECCIÓN: CUMPLIDOS --}}
        <div id="view-cumplidos" class="view-section">
            <h6 class="fw-bold mb-3 opacity-50 small"><i class="bi bi-check-all me-1"></i> HISTORIAL CUMPLIDOS</h6>
            <div class="alert alert-light border shadow-sm small">
                <i class="bi bi-info-circle me-1"></i> Total histórico: <strong>{{ $totalCumplidos }}</strong> turnos finalizados con éxito.
            </div>
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-award fs-1"></i>
                <p class="small mt-2">¡Sigue así!</p>
            </div>
        </div>

        {{-- SECCIÓN: SEMANA --}}
        <div id="view-semana" class="view-section">
            <h6 class="fw-bold mb-3 opacity-50 small"><i class="bi bi-calendar-week me-1"></i> AGENDA SEMANAL</h6>
            @forelse($turnosSemana as $fecha => $turnos)
                <div class="day-group">
                    <div class="day-header shadow-sm" onclick="toggleDay(this)">
                        <span>{{ \Carbon\Carbon::parse($fecha)->translatedFormat('l d') }}</span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-pill">{{ count($turnos) }}</span>
                            <i class="bi bi-chevron-down opacity-50"></i>
                        </div>
                    </div>
                    <div class="day-content">
                        @foreach($turnos as $t)
                            @include('portales.profesional._turno_item', ['turno' => $t])
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-center py-5 text-muted small">No hay turnos esta semana</p>
            @endforelse
        </div>

        {{-- SECCIÓN: MES --}}
        <div id="view-mes" class="view-section">
            <h6 class="fw-bold mb-3 opacity-50 small"><i class="bi bi-calendar-month me-1"></i> VISTA MENSUAL</h6>
            @forelse($turnosMes as $fecha => $turnos)
                <div class="day-group">
                    <div class="day-header shadow-sm" onclick="toggleDay(this)">
                        <span>{{ \Carbon\Carbon::parse($fecha)->translatedFormat('d/m') }} - {{ \Carbon\Carbon::parse($fecha)->translatedFormat('D') }}</span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-secondary rounded-pill">{{ count($turnos) }}</span>
                            <i class="bi bi-chevron-down opacity-50"></i>
                        </div>
                    </div>
                    <div class="day-content">
                        @foreach($turnos as $t)
                            @include('portales.profesional._turno_item', ['turno' => $t])
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-center py-5 text-muted small">No hay turnos este mes</p>
            @endforelse
        </div>

    </div>

    <script>
        function switchView(viewId, btn) {
            // Desactivar todos los botones
            document.querySelectorAll('.metric-btn').forEach(b => b.classList.remove('active'));
            // Activar el actual
            btn.classList.add('active');

            // Ocultar todas las secciones
            document.querySelectorAll('.view-section').forEach(s => s.classList.remove('active'));
            // Mostrar la seleccionada
            document.getElementById('view-' + viewId).classList.add('active');
            
            // Scroll al top suave
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function toggleDay(header) {
            const content = header.nextElementSibling;
            const icon = header.querySelector('.bi-chevron-down');
            const isOpen = content.style.display === 'block';
            
            if(isOpen) {
                content.style.display = 'none';
                icon.style.transform = 'rotate(0deg)';
            } else {
                content.style.display = 'block';
                icon.style.transform = 'rotate(180deg)';
            }
        }

        function finalizarTurno(id) {
            if(!confirm('¿Has terminado esta tarea?')) return;

            fetch(`/portal/profesional/complete/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert(data.message);
                    location.reload(); 
                }
            });
        }
    </script>
</body>
</html>
