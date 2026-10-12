@php
    $readonly = $readonly ?? false;
    $initialData = $initialData ?? [];
    $containerId = $containerId ?? 'odontograma_' . uniqid();
    $modoOscuro = (auth()->user()->empresa?->config?->theme === 'dark');
@endphp

<div id="{{ $containerId }}" class="odontograma-wrapper card border-0 shadow-sm rounded-4 overflow-hidden mb-4" style="background: {{ $modoOscuro ? 'rgba(15, 23, 42, 0.75)' : '#ffffff' }}; backdrop-filter: blur(10px);">
    {{-- CABECERA ODONTOGRAMA --}}
    <div class="p-3 p-md-4 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3" style="background: {{ $modoOscuro ? 'rgba(30, 41, 59, 0.4)' : '#f8fafc' }}; border-color: {{ $modoOscuro ? 'rgba(255,255,255,0.08)' : '#e2e8f0' }} !important;">
        <div class="d-flex align-items-center gap-2">
            <span class="fs-4">🦷</span>
            <div>
                <h5 class="fw-bold mb-0 {{ $modoOscuro ? 'text-white' : 'text-dark' }}">Odontograma Digital Interactivo</h5>
                <small class="{{ $modoOscuro ? 'text-muted' : 'text-secondary' }}">Sistema FDI Internacional — 5 Caras Dentales por Pieza</small>
            </div>
        </div>

        @if(!$readonly)
            {{-- SELECTOR DE DENTICIÓN --}}
            <div class="d-flex align-items-center gap-2">
                <div class="btn-group btn-group-sm p-1 rounded-pill bg-light border shadow-sm" role="group">
                    <button type="button" class="btn btn-sm rounded-pill fw-bold btn-denticion active" data-target="permanente" onclick="switchDenticion('{{ $containerId }}', 'permanente')">
                        Permanente (Adultos)
                    </button>
                    <button type="button" class="btn btn-sm rounded-pill fw-bold btn-denticion text-secondary" data-target="temporal" onclick="switchDenticion('{{ $containerId }}', 'temporal')">
                        Temporaria (Niños)
                    </button>
                    <button type="button" class="btn btn-sm rounded-pill fw-bold btn-denticion text-secondary" data-target="ambas" onclick="switchDenticion('{{ $containerId }}', 'ambas')">
                        Ambas
                    </button>
                </div>

                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold" onclick="limpiarOdontograma('{{ $containerId }}')">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reiniciar
                </button>
            </div>
        @endif
    </div>

    @if(!$readonly)
        {{-- BARRA DE HERRAMIENTAS / ESTADOS CLÍNICOS --}}
        <div class="p-3 border-bottom d-flex flex-wrap align-items-center gap-2 justify-content-center" style="background: {{ $modoOscuro ? 'rgba(15, 23, 42, 0.5)' : '#ffffff' }}; border-color: {{ $modoOscuro ? 'rgba(255,255,255,0.06)' : '#f1f5f9' }} !important;">
            <span class="x-small fw-bold text-uppercase text-muted me-2"><i class="bi bi-tools me-1"></i> Estado / Diagnóstico:</span>
            
            <button type="button" class="btn btn-sm btn-tool rounded-pill px-3 py-1 fw-bold active" data-tool="caries" style="border: 2px solid #ef4444; color: #ef4444; background: rgba(239, 68, 68, 0.1);" onclick="setTool('{{ $containerId }}', 'caries', this)">
                <span class="badge rounded-circle p-1 me-1" style="background: #ef4444;">&nbsp;</span> Caries (Rojo)
            </button>

            <button type="button" class="btn btn-sm btn-tool rounded-pill px-3 py-1 fw-bold" data-tool="obturacion" style="border: 2px solid #3b82f6; color: #3b82f6; background: rgba(59, 130, 246, 0.05);" onclick="setTool('{{ $containerId }}', 'obturacion', this)">
                <span class="badge rounded-circle p-1 me-1" style="background: #3b82f6;">&nbsp;</span> Obturación / Resina (Azul)
            </button>

            <button type="button" class="btn btn-sm btn-tool rounded-pill px-3 py-1 fw-bold" data-tool="corona" style="border: 2px solid #f59e0b; color: #d97706; background: rgba(245, 158, 11, 0.05);" onclick="setTool('{{ $containerId }}', 'corona', this)">
                <span class="badge rounded-circle p-1 me-1" style="background: #f59e0b;">&nbsp;</span> Corona / Prótesis (Dorado)
            </button>

            <button type="button" class="btn btn-sm btn-tool rounded-pill px-3 py-1 fw-bold" data-tool="endodoncia" style="border: 2px solid #8b5cf6; color: #8b5cf6; background: rgba(139, 92, 246, 0.05);" onclick="setTool('{{ $containerId }}', 'endodoncia', this)">
                <span class="badge rounded-circle p-1 me-1" style="background: #8b5cf6;">&nbsp;</span> Endodoncia (Púrpura)
            </button>

            <button type="button" class="btn btn-sm btn-tool rounded-pill px-3 py-1 fw-bold" data-tool="ausente" style="border: 2px solid #64748b; color: #64748b; background: rgba(100, 116, 139, 0.05);" onclick="setTool('{{ $containerId }}', 'ausente', this)">
                <i class="bi bi-x-lg text-danger me-1"></i> Extracción / Ausente (Cruz)
            </button>

            <button type="button" class="btn btn-sm btn-tool rounded-pill px-3 py-1 fw-bold" data-tool="implante" style="border: 2px solid #10b981; color: #059669; background: rgba(16, 185, 129, 0.05);" onclick="setTool('{{ $containerId }}', 'implante', this)">
                <span class="badge rounded-circle p-1 me-1" style="background: #10b981;">&nbsp;</span> Implante (Verde)
            </button>

            <button type="button" class="btn btn-sm btn-tool rounded-pill px-3 py-1 fw-bold" data-tool="sano" style="border: 2px solid #94a3b8; color: #64748b; background: rgba(148, 163, 184, 0.05);" onclick="setTool('{{ $containerId }}', 'sano', this)">
                <i class="bi bi-eraser-fill me-1"></i> Borrador / Sano
            </button>
        </div>
    @endif

    {{-- CUERPO VISUAL DEL ODONTOGRAMA (ARCADAS SUPERIOR E INFERIOR) --}}
    <div class="p-3 p-md-4 overflow-auto">
        {{-- DENTICIÓN PERMANENTE (ADULTOS: 18..11, 21..28 Y 48..41, 31..38) --}}
        <div class="denticion-bloque denticion-permanente">
            {{-- ARCADA SUPERIOR ADULTO --}}
            <div class="arcada-row text-center mb-3">
                <span class="badge bg-secondary bg-opacity-10 text-muted px-3 py-1 rounded-pill mb-2 x-small fw-bold text-uppercase">
                    Arcada Superior (Maxilar) — Cuadrantes 1 y 2
                </span>
                <div class="d-flex justify-content-center align-items-center gap-1 gap-md-2 flex-nowrap pb-2">
                    {{-- Cuadrante 1: 18 a 11 --}}
                    @foreach([18, 17, 16, 15, 14, 13, 12, 11] as $pieza)
                        @include('empresa.medical_records.components.pieza_dental', ['pieza' => $pieza, 'tipo' => 'permanente', 'posicion' => 'superior', 'readonly' => $readonly])
                    @endforeach

                    {{-- Separador de línea media --}}
                    <div class="linea-media mx-1 mx-md-2" style="width: 2px; height: 75px; background: {{ $modoOscuro ? 'rgba(255,255,255,0.2)' : '#cbd5e1' }}; border-radius: 2px;"></div>

                    {{-- Cuadrante 2: 21 a 28 --}}
                    @foreach([21, 22, 23, 24, 25, 26, 27, 28] as $pieza)
                        @include('empresa.medical_records.components.pieza_dental', ['pieza' => $pieza, 'tipo' => 'permanente', 'posicion' => 'superior', 'readonly' => $readonly])
                    @endforeach
                </div>
            </div>

            {{-- DENTICIÓN TEMPORARIA (NIÑOS: 55..51, 61..65 Y 85..81, 71..75) --}}
            <div class="denticion-bloque denticion-temporal my-4" style="display: none;">
                <div class="p-3 rounded-4 border border-info border-opacity-25" style="background: {{ $modoOscuro ? 'rgba(6, 182, 212, 0.05)' : '#f0fdfa' }};">
                    <div class="text-center mb-3">
                        <span class="badge bg-info bg-opacity-25 text-info px-3 py-1 rounded-pill x-small fw-bold text-uppercase">
                            👶 Dentición Temporaria (Decidua / Niños) — Cuadrantes 5, 6, 7 y 8
                        </span>
                    </div>

                    {{-- Superior Temporal --}}
                    <div class="d-flex justify-content-center align-items-center gap-1 gap-md-2 flex-nowrap pb-2 mb-2">
                        @foreach([55, 54, 53, 52, 51] as $pieza)
                            @include('empresa.medical_records.components.pieza_dental', ['pieza' => $pieza, 'tipo' => 'temporal', 'posicion' => 'superior', 'readonly' => $readonly])
                        @endforeach
                        <div class="linea-media mx-1 mx-md-2" style="width: 2px; height: 70px; background: rgba(6, 182, 212, 0.3);"></div>
                        @foreach([61, 62, 63, 64, 65] as $pieza)
                            @include('empresa.medical_records.components.pieza_dental', ['pieza' => $pieza, 'tipo' => 'temporal', 'posicion' => 'superior', 'readonly' => $readonly])
                        @endforeach
                    </div>

                    {{-- Inferior Temporal --}}
                    <div class="d-flex justify-content-center align-items-center gap-1 gap-md-2 flex-nowrap pt-2">
                        @foreach([85, 84, 83, 82, 81] as $pieza)
                            @include('empresa.medical_records.components.pieza_dental', ['pieza' => $pieza, 'tipo' => 'temporal', 'posicion' => 'inferior', 'readonly' => $readonly])
                        @endforeach
                        <div class="linea-media mx-1 mx-md-2" style="width: 2px; height: 70px; background: rgba(6, 182, 212, 0.3);"></div>
                        @foreach([71, 72, 73, 74, 75] as $pieza)
                            @include('empresa.medical_records.components.pieza_dental', ['pieza' => $pieza, 'tipo' => 'temporal', 'posicion' => 'inferior', 'readonly' => $readonly])
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ARCADA INFERIOR ADULTO --}}
            <div class="arcada-row text-center mt-3">
                <div class="d-flex justify-content-center align-items-center gap-1 gap-md-2 flex-nowrap pt-2">
                    {{-- Cuadrante 4: 48 a 41 --}}
                    @foreach([48, 47, 46, 45, 44, 43, 42, 41] as $pieza)
                        @include('empresa.medical_records.components.pieza_dental', ['pieza' => $pieza, 'tipo' => 'permanente', 'posicion' => 'inferior', 'readonly' => $readonly])
                    @endforeach

                    {{-- Separador de línea media --}}
                    <div class="linea-media mx-1 mx-md-2" style="width: 2px; height: 75px; background: {{ $modoOscuro ? 'rgba(255,255,255,0.2)' : '#cbd5e1' }}; border-radius: 2px;"></div>

                    {{-- Cuadrante 3: 31 a 38 --}}
                    @foreach([31, 32, 33, 34, 35, 36, 37, 38] as $pieza)
                        @include('empresa.medical_records.components.pieza_dental', ['pieza' => $pieza, 'tipo' => 'permanente', 'posicion' => 'inferior', 'readonly' => $readonly])
                    @endforeach
                </div>
                <span class="badge bg-secondary bg-opacity-10 text-muted px-3 py-1 rounded-pill mt-2 x-small fw-bold text-uppercase">
                    Arcada Inferior (Mandíbula) — Cuadrantes 4 y 3
                </span>
            </div>
        </div>
    </div>

    {{-- LEYENDA CLÍNICA Y RESUMEN AUTOMÁTICO DE HALLAZGOS --}}
    <div class="p-3 p-md-4 border-top" style="background: {{ $modoOscuro ? 'rgba(15, 23, 42, 0.6)' : '#f8fafc' }}; border-color: {{ $modoOscuro ? 'rgba(255,255,255,0.08)' : '#e2e8f0' }} !important;">
        <div class="row g-3 align-items-start">
            <div class="col-md-6">
                <h6 class="fw-bold mb-2 {{ $modoOscuro ? 'text-white' : 'text-dark' }}">
                    <i class="bi bi-list-check me-1 text-primary"></i> Hallazgos y Diagnóstico Dental Detectado:
                </h6>
                <div id="{{ $containerId }}_hallazgos_list" class="d-flex flex-wrap gap-2 min-vh-25 p-2 rounded-3 border" style="background: {{ $modoOscuro ? 'rgba(255,255,255,0.02)' : '#ffffff' }}; min-height: 48px;">
                    <span class="text-muted small italic p-1" id="{{ $containerId }}_sin_hallazgos">Boca sana / Sin anomalías marcadas aún.</span>
                </div>
            </div>

            <div class="col-md-6">
                <h6 class="fw-bold mb-2 {{ $modoOscuro ? 'text-white' : 'text-dark' }}">
                    <i class="bi bi-info-circle me-1 text-info"></i> Leyenda de Convenciones Odontológicas:
                </h6>
                <div class="d-flex flex-wrap gap-3 small {{ $modoOscuro ? 'text-muted' : 'text-secondary' }}">
                    <div class="d-flex align-items-center gap-1"><span class="badge p-1" style="background:#ef4444;">&nbsp;&nbsp;</span> <span>Caries</span></div>
                    <div class="d-flex align-items-center gap-1"><span class="badge p-1" style="background:#3b82f6;">&nbsp;&nbsp;</span> <span>Obturación</span></div>
                    <div class="d-flex align-items-center gap-1"><span class="badge p-1" style="background:#f59e0b;">&nbsp;&nbsp;</span> <span>Corona</span></div>
                    <div class="d-flex align-items-center gap-1"><span class="badge p-1" style="background:#8b5cf6;">&nbsp;&nbsp;</span> <span>Endodoncia</span></div>
                    <div class="d-flex align-items-center gap-1"><span class="badge p-1" style="background:#10b981;">&nbsp;&nbsp;</span> <span>Implante</span></div>
                    <div class="d-flex align-items-center gap-1"><i class="bi bi-x-lg text-danger fw-bold"></i> <span>Ausente</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- INPUT OCULTO CON EL ESTADO SERIALIZADO PARA EL SUBMIT --}}
    <input type="hidden" name="odontograma_data" id="{{ $containerId }}_data" value="{{ json_encode($initialData) }}">
</div>

<style>
    /* ESTILOS DEL ODONTOGRAMA */
    .diente-card {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        margin: 2px;
        transition: transform 0.15s ease;
    }
    .diente-card:hover {
        transform: translateY(-2px);
    }
    .diente-num {
        font-size: 0.72rem;
        font-weight: 800;
        margin-bottom: 2px;
        color: {{ $modoOscuro ? '#cbd5e1' : '#475569' }};
    }
    .diente-svg {
        width: 36px;
        height: 36px;
        cursor: pointer;
    }
    .diente-cara {
        fill: {{ $modoOscuro ? '#1e293b' : '#f8fafc' }};
        stroke: {{ $modoOscuro ? '#475569' : '#94a3b8' }};
        stroke-width: 1.2;
        transition: fill 0.2s ease, stroke 0.2s ease;
    }
    .diente-cara:hover {
        fill: {{ $modoOscuro ? '#334155' : '#e2e8f0' }};
        stroke: #3b82f6;
    }

    /* ESTADOS CLÍNICOS */
    .diente-cara.estado-caries { fill: #ef4444 !important; stroke: #b91c1c !important; }
    .diente-cara.estado-obturacion { fill: #3b82f6 !important; stroke: #1d4ed8 !important; }
    .diente-cara.estado-corona { fill: #f59e0b !important; stroke: #b45309 !important; }
    .diente-cara.estado-endodoncia { fill: #8b5cf6 !important; stroke: #6d28d9 !important; }
    .diente-cara.estado-implante { fill: #10b981 !important; stroke: #047857 !important; }

    /* ESTADOS DE PIEZA COMPLETA */
    .diente-box.estado-ausente .diente-svg {
        opacity: 0.4;
        filter: grayscale(1);
    }
    .diente-box.estado-ausente {
        position: relative;
    }
    .diente-box.estado-ausente::after {
        content: '✕';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 1.8rem;
        font-weight: 900;
        color: #ef4444;
        pointer-events: none;
    }

    .diente-box.estado-corona-total .diente-cara {
        fill: #f59e0b !important;
    }
    .diente-box.estado-implante-total .diente-cara {
        fill: #10b981 !important;
    }

    .btn-tool.active {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: scale(1.05);
    }
</style>

<script>
    (function() {
        const cId = '{{ $containerId }}';
        let currentTool = 'caries';
        let odontogramaState = {};

        // Cargar datos iniciales si existen
        try {
            const raw = document.getElementById(cId + '_data').value;
            if (raw) {
                odontogramaState = JSON.parse(raw);
            }
        } catch(e) {
            odontogramaState = {};
        }

        window['setTool_' + cId] = function(tool, btn) {
            currentTool = tool;
            const container = document.getElementById(cId);
            container.querySelectorAll('.btn-tool').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        };

        window.setTool = function(containerId, tool, btn) {
            if (window['setTool_' + containerId]) {
                window['setTool_' + containerId](tool, btn);
            }
        };

        window.switchDenticion = function(containerId, tipo) {
            const container = document.getElementById(containerId);
            container.querySelectorAll('.btn-denticion').forEach(b => {
                b.classList.remove('active', 'bg-light', 'text-dark');
                b.classList.add('text-secondary');
            });
            const clicked = container.querySelector(`[data-target="${tipo}"]`);
            if (clicked) {
                clicked.classList.add('active');
                clicked.classList.remove('text-secondary');
            }

            const perm = container.querySelector('.denticion-permanente');
            const temp = container.querySelector('.denticion-temporal');

            if (tipo === 'permanente') {
                perm.style.display = 'block';
                temp.style.display = 'none';
            } else if (tipo === 'temporal') {
                perm.style.display = 'none';
                temp.style.display = 'block';
            } else {
                perm.style.display = 'block';
                temp.style.display = 'block';
            }
        };

        // Clic en cara dental
        window.clickCaraDental = function(containerId, pieza, cara, element) {
            if ('{{ $readonly }}' === '1') return;

            if (!odontogramaState[pieza]) {
                odontogramaState[pieza] = { caras: {}, estado_general: 'sano' };
            }

            const box = document.getElementById(`${containerId}_pieza_${pieza}`);

            if (currentTool === 'ausente') {
                // Alternar ausente
                if (odontogramaState[pieza].estado_general === 'ausente') {
                    odontogramaState[pieza].estado_general = 'sano';
                    box.classList.remove('estado-ausente');
                } else {
                    odontogramaState[pieza].estado_general = 'ausente';
                    box.classList.add('estado-ausente');
                }
            } else if (currentTool === 'corona') {
                // Corona en toda la pieza
                if (odontogramaState[pieza].estado_general === 'corona') {
                    odontogramaState[pieza].estado_general = 'sano';
                    box.classList.remove('estado-corona-total');
                } else {
                    odontogramaState[pieza].estado_general = 'corona';
                    box.classList.add('estado-corona-total');
                }
            } else if (currentTool === 'implante') {
                // Implante en toda la pieza
                if (odontogramaState[pieza].estado_general === 'implante') {
                    odontogramaState[pieza].estado_general = 'sano';
                    box.classList.remove('estado-implante-total');
                } else {
                    odontogramaState[pieza].estado_general = 'implante';
                    box.classList.add('estado-implante-total');
                }
            } else if (currentTool === 'endodoncia') {
                // Endodoncia en toda la pieza
                if (odontogramaState[pieza].estado_general === 'endodoncia') {
                    odontogramaState[pieza].estado_general = 'sano';
                    box.classList.remove('estado-endodoncia');
                    element.classList.remove('estado-endodoncia');
                } else {
                    odontogramaState[pieza].estado_general = 'endodoncia';
                    box.classList.add('estado-endodoncia');
                    element.classList.add('estado-endodoncia');
                }
            } else if (currentTool === 'sano') {
                // Limpiar cara y pieza
                delete odontogramaState[pieza].caras[cara];
                element.className.baseVal = 'diente-cara';
                if (Object.keys(odontogramaState[pieza].caras).length === 0) {
                    odontogramaState[pieza].estado_general = 'sano';
                    box.classList.remove('estado-ausente', 'estado-corona-total', 'estado-implante-total');
                }
            } else {
                // Aplicar a cara específica (caries o obturacion)
                odontogramaState[pieza].caras[cara] = currentTool;
                element.className.baseVal = `diente-cara estado-${currentTool}`;
            }

            actualizarHallazgos(containerId);
        };

        // Renderizar el estado inicial
        function renderEstadoInicial() {
            Object.keys(odontogramaState).forEach(pieza => {
                const data = odontogramaState[pieza];
                const box = document.getElementById(`${cId}_pieza_${pieza}`);
                if (!box) return;

                if (data.estado_general === 'ausente') {
                    box.classList.add('estado-ausente');
                } else if (data.estado_general === 'corona') {
                    box.classList.add('estado-corona-total');
                } else if (data.estado_general === 'implante') {
                    box.classList.add('estado-implante-total');
                }

                if (data.caras) {
                    Object.keys(data.caras).forEach(cara => {
                        const estado = data.caras[cara];
                        const el = box.querySelector(`[data-cara="${cara}"]`);
                        if (el) {
                            el.className.baseVal = `diente-cara estado-${estado}`;
                        }
                    });
                }
            });
            actualizarHallazgos(cId);
        }

        function actualizarHallazgos(containerId) {
            const list = document.getElementById(containerId + '_hallazgos_list');
            const sinHallazgos = document.getElementById(containerId + '_sin_hallazgos');
            const inputHidden = document.getElementById(containerId + '_data');
            
            list.innerHTML = '';
            let count = 0;

            const nombresCaras = {
                'c': 'Oclusal',
                'v': 'Vestibular',
                'p': 'Palatino/Lingual',
                'm': 'Mesial',
                'd': 'Distal'
            };

            const labelsEstados = {
                'caries': { text: 'Caries', badge: 'bg-danger' },
                'obturacion': { text: 'Obturado', badge: 'bg-primary' },
                'corona': { text: 'Corona', badge: 'bg-warning text-dark' },
                'endodoncia': { text: 'Endodoncia', badge: 'bg-purple text-white', style: 'background:#8b5cf6;' },
                'ausente': { text: 'Ausente', badge: 'bg-secondary' },
                'implante': { text: 'Implante', badge: 'bg-success' }
            };

            Object.keys(odontogramaState).forEach(pieza => {
                const data = odontogramaState[pieza];
                
                // Si tiene estado general
                if (data.estado_general && data.estado_general !== 'sano') {
                    count++;
                    const info = labelsEstados[data.estado_general] || { text: data.estado_general, badge: 'bg-info' };
                    const item = document.createElement('span');
                    item.className = `badge ${info.badge} px-2 py-1 shadow-sm d-flex align-items-center gap-1`;
                    if (info.style) item.style = info.style;
                    item.innerHTML = `<strong>Pieza ${pieza}:</strong> ${info.text}`;
                    list.appendChild(item);
                }

                // Caras
                if (data.caras) {
                    Object.keys(data.caras).forEach(cara => {
                        const st = data.caras[cara];
                        if (st && st !== 'sano') {
                            count++;
                            const info = labelsEstados[st] || { text: st, badge: 'bg-info' };
                            const caraNombre = nombresCaras[cara] || cara;
                            const item = document.createElement('span');
                            item.className = `badge ${info.badge} px-2 py-1 shadow-sm d-flex align-items-center gap-1`;
                            if (info.style) item.style = info.style;
                            item.innerHTML = `<strong>P.${pieza} (${caraNombre}):</strong> ${info.text}`;
                            list.appendChild(item);
                        }
                    });
                }
            });

            if (count === 0) {
                list.innerHTML = `<span class="text-muted small italic p-1">Boca sana / Sin anomalías marcadas aún.</span>`;
            }

            // Actualizar input oculto para persistencia
            if (inputHidden) {
                inputHidden.value = JSON.stringify(odontogramaState);
            }
        }

        window.limpiarOdontograma = function(containerId, omitConfirm = false) {
            if (!omitConfirm && !confirm('¿Desea restablecer y limpiar todo el odontograma?')) return;
            odontogramaState = {};
            const container = document.getElementById(containerId);
            if (!container) return;
            container.querySelectorAll('.diente-cara').forEach(el => {
                el.className.baseVal = 'diente-cara';
            });
            container.querySelectorAll('.diente-box').forEach(el => {
                el.classList.remove('estado-ausente', 'estado-corona-total', 'estado-implante-total', 'estado-endodoncia');
            });
            actualizarHallazgos(containerId);
        };

        // Método expuesto globalmente para cargar nuevos datos externamente (ej: cambio de paciente vía AJAX)
        window['cargarOdontograma_' + cId] = function(nuevosDatos) {
            odontogramaState = nuevosDatos ? JSON.parse(JSON.stringify(nuevosDatos)) : {};
            const inputHidden = document.getElementById(cId + '_data');
            if (inputHidden) {
                inputHidden.value = JSON.stringify(odontogramaState);
            }
            // Limpiar visuales existentes
            const container = document.getElementById(cId);
            if (container) {
                container.querySelectorAll('.diente-cara').forEach(el => {
                    el.className.baseVal = 'diente-cara';
                });
                container.querySelectorAll('.diente-box').forEach(el => {
                    el.classList.remove('estado-ausente', 'estado-corona-total', 'estado-implante-total', 'estado-endodoncia');
                });
            }
            renderEstadoInicial();
        };

        window.cargarOdontogramaData = function(containerId, nuevosDatos) {
            if (window['cargarOdontograma_' + containerId]) {
                window['cargarOdontograma_' + containerId](nuevosDatos);
            }
        };

        // Renderizado tras carga del DOM
        setTimeout(renderEstadoInicial, 100);
    })();
</script>
